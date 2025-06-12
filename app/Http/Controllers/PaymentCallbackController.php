<?php

namespace App\Http\Controllers;

use App\Constants\PaymentStatus;
use App\Models\Transaction;
use App\Traits\CanPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentCallbackController extends Controller
{
    //
    use CanPay;
    public function callback(Request $request)
    {
        $params=$this->verifyChecksum($request);
        $transaction=Transaction::query()->where('order_id', $params['ORDERID'])->first();
        $bet = $transaction->bet()->first();

        if ($params['STATUS']===PaymentStatus::PAID) {

            $updated=DB::transaction(function () use ($bet, $params, $transaction) {
                $transaction->msg = $params['RESPMSG'];
                $transaction->transaction_id = $params['TXNID'];
                $transaction->extra = json_encode($params);
                $transaction->status = PaymentStatus::PAID;
                $transaction->transaction_date =now();
                $transaction->save();

                $bet->transaction_id = $params['TXNID'];
                $bet->transaction_status = PaymentStatus::PAID;
                $bet->save();

                return $transaction;
            });

            return inertia('Backend/Player/Event/Receipt',[
                'data' => $transaction->load(['user', 'bet.option', 'bet.event'])
            ]);
        }else{
            $transaction->msg = $params['RESPMSG'];
            $transaction->transaction_id = $params['TXNID'];
            $transaction->extra = json_encode($params);
            $transaction->status = $params['STATUS'];
            $transaction->transaction_date =now();
            $transaction->save();

            $bet->transaction_id = $params['TXNID'];
            $bet->transaction_status = $params['STATUS'];
            $bet->save();

            return inertia('Backend/Apply/Receipt', [
                'data' => $transaction->load(['user', 'bet.option', 'bet.event'])
                ]);
        }
    }
}
