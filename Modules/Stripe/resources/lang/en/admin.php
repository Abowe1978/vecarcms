<?php

return [
    'settings' => [
        'title' => 'Stripe Settings',
        'description' => 'Configure your Stripe integration settings.',
        'fields' => [
            'key' => 'Publishable Key',
            'secret' => 'Secret Key',
            'webhook_secret' => 'Webhook Secret',
            'currency' => 'Currency',
            'sandbox' => 'Test Mode',
            'statement_descriptor' => 'Statement Descriptor',
            'automatic_tax' => 'Automatic Tax',
        ],
        'help' => [
            'key' => 'Your Stripe publishable key (starts with pk_)',
            'secret' => 'Your Stripe secret key (starts with sk_)',
            'webhook_secret' => 'Your Stripe webhook signing secret (starts with whsec_)',
            'currency' => 'The currency to use for payments (e.g. gbp, usd, eur)',
            'sandbox' => 'If enabled, will use Stripe test environment',
            'statement_descriptor' => 'Text that appears on your customer\'s credit card statement',
            'automatic_tax' => 'If enabled, Stripe will automatically calculate taxes',
        ],
        'labels' => [
            'sandbox' => 'Enable test mode (sandbox)',
            'automatic_tax' => 'Enable automatic tax calculation',
        ],
    ],
]; 