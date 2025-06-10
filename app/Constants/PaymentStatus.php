<?php
namespace App\Constants;

class PaymentStatus{
    const PAID="TXN_SUCCESS";
    const ATTEMPTED='Attempted';
    const FAILED = 'TXN_FAILURE';
    const PENDING = 'PENDING';
    const REFUNDED = 'Refunded';
}
