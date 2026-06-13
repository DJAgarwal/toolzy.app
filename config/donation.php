<?php

return [
    'paytm' => [
        'number' => env('DONATION_PAYTM_NUMBER'),
        'upi_id' => env('DONATION_UPI_ID'),
        'qr_image' => env('DONATION_UPI_QR_IMAGE'),
    ],
    'kofi' => [
        'url' => env('DONATION_KOFI_URL'),
    ],
];
