<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Util\AppUtil;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    //
    use AuthorizesRequests, ValidatesRequests;


}
