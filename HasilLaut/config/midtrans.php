<?php
return [
    'merchant_id' => env('M749674204'),
    'client_key' => env('Mid-client-ZVgRWePOtm7Av41M'),
    'server_key' => env('M749674204-DEFAULT-PAYMENT-CLIENT'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];