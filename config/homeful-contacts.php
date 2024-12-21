<?php

return [
    'minimum_income' => env('MINIMUM_INCOME', 12500),
    'callback' => env('CALLBACK'),
    'show_gmi' => (bool) env('SHOW_GMI', FALSE),
];
