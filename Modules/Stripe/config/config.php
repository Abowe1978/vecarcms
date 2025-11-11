<?php

return [
    'name' => 'Stripe',
    'enabled' => false,
    
    /*
    |--------------------------------------------------------------------------
    | Stripe Keys
    |--------------------------------------------------------------------------
    |
    | The Stripe publishable key and secret key give you access to Stripe's
    | API. The "publishable" key is typically used when interacting with
    | Stripe.js while the "secret" key accesses private API endpoints.
    |
    */
    'key' => env('STRIPE_KEY', ''),
    'secret' => env('STRIPE_SECRET', ''),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Stripe Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency that will be used when generating charges
    | from your application. Of course, you are welcome to use any of the
    | various world currencies that are currently supported via Stripe.
    |
    */
    'currency' => env('STRIPE_CURRENCY', 'gbp'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Payment Mode
    |--------------------------------------------------------------------------
    |
    | This option defines if the application should use Stripe in test mode
    | or live mode. The test mode is useful for development and testing.
    |
    */
    'sandbox' => env('STRIPE_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | Stripe Payment Methods
    |--------------------------------------------------------------------------
    |
    | Here you can specify the payment methods that your application supports.
    | Out of the box, Stripe supports a variety of payment methods.
    |
    */
    'payment_methods' => [
        'card',
        'bacs_debit',
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe Webhooks
    |--------------------------------------------------------------------------
    |
    | Your Stripe webhook settings. This URL needs to be exposed to the internet
    | and configured in your Stripe dashboard. The tolerance is the number of
    | seconds the timestamp can differ from the current time.
    |
    */
    'webhooks' => [
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe Statement Descriptor
    |--------------------------------------------------------------------------
    |
    | This is the text that appears on your customer's credit card statement.
    | Statement descriptors are limited to 22 characters, cannot use the
    | special characters <, >, ', ", or *, and must not consist solely of numbers.
    |
    */
    'statement_descriptor' => env('STRIPE_STATEMENT_DESCRIPTOR', 'RREC Membership'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Automatic Tax
    |--------------------------------------------------------------------------
    |
    | When enabled, Stripe will automatically calculate tax rates for your charges
    | based on the customer's location and your tax registrations.
    |
    */
    'automatic_tax' => env('STRIPE_AUTOMATIC_TAX', false),
];
