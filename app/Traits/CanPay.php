<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use paytm\paytmchecksum\PaytmChecksum;

trait CanPay{
    public function initiatePayment(User $user,$order_id,$amount,$callbackUrl)
    {
        $MID=env('MID');
        $MKEY = env('MKEY');
        info('number formatted paytm manager => '.number_format($amount, 2,'.','') . '',);
        $paytmParams["body"] = array(
            "requestType" => "Payment",
            "mid" => $MID,
            "websiteName" => env('APP_DEBUG')?'WEBSTAGING':'DEFAULT',
            "orderId" => $order_id,
            "callbackUrl" => $callbackUrl,
            "txnAmount" => array(
                "value" => number_format($amount, 2,'.','') . '',
                "currency" => "INR",
            ),
            "userInfo" => array(
                "custId" => $user->id . '',
                "mobile" => $user?->mobile,
                'email' => $user?->email,
                'first_name' => $user->name,
                'last_name' => $user->name
            ),
        );


        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"]), $MKEY);


        $paytmParams["head"] = array(
            "signature" => $checksum,
            "requestTimestamp" => Carbon::now()->timestamp,
            "channelId" => 'WEB'
        );

        $url = env('APP_DEBUG')
            ? "https://securestage.paytmpayments.com/theia/api/v1/initiateTransaction?mid=" . env('MID') . "&orderId=" . $order_id
            : "https://secure.paytmpayments.com/theia/api/v1/initiateTransaction?mid=" . env('MID') . "&orderId=" . $order_id;

//        dd($url);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, $paytmParams);


        $response->throw();

        $result = json_decode($response->body(), true);


        if ($result['body']['resultInfo']['resultStatus'] === 'S') {
            return $result['body']['txnToken'];
        }

        throw new \Exception($result['body']['resultInfo']['resultMsg'],500);
    }

    public function verifyChecksum(Request $request)
    {
        $MID=env('MID');
        $MKEY = env('MKEY');

        $paytmParams = [];
        $paytmChecksum = '';
        foreach ($request->all() as $key => $value) {
            if ($key == "CHECKSUMHASH") {
                $paytmChecksum = $value;
            } else {
                $paytmParams[$key] = $value;
            }
        }

        $isValidChecksum = PaytmChecksum::verifySignature($paytmParams, $MKEY, $paytmChecksum);
        if (!$isValidChecksum) {
            info('checksum error');
            throw  new \Exception('invalid checksum', 500);
        }
        return $paytmParams;
    }

    public function checkTransaction($orderId)
    {
        $paytmParams = array();
        $paytmParams["body"] = [
            "mid" => env('MID'),
            "orderId" => $orderId,
        ];
        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), env('MKEY'));

        $paytmParams["head"] = array(
            "signature" => $checksum
        );

        $url = env('APP_DEBUG')?"https://securestage.paytmpayments.com/v3/order/status":"https://secure.paytmpayments.com/v3/order/status";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, $paytmParams);

        return json_decode($response->body(), true);
    }

}
