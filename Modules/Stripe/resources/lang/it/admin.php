<?php

return [
    'settings' => [
        'title' => 'Impostazioni Stripe',
        'description' => 'Configura le impostazioni di integrazione con Stripe.',
        'fields' => [
            'key' => 'Chiave Pubblica',
            'secret' => 'Chiave Segreta',
            'webhook_secret' => 'Segreto Webhook',
            'currency' => 'Valuta',
            'sandbox' => 'Modalità Test',
            'statement_descriptor' => 'Descrizione Estratto Conto',
            'automatic_tax' => 'Calcolo Automatico Tasse',
        ],
        'help' => [
            'key' => 'La tua chiave pubblica Stripe (inizia con pk_)',
            'secret' => 'La tua chiave segreta Stripe (inizia con sk_)',
            'webhook_secret' => 'Il tuo segreto webhook Stripe (inizia con whsec_)',
            'currency' => 'La valuta da utilizzare per i pagamenti (es. gbp, usd, eur)',
            'sandbox' => 'Se abilitato, verrà utilizzato l\'ambiente di test di Stripe',
            'statement_descriptor' => 'Testo che appare sull\'estratto conto della carta di credito del cliente',
            'automatic_tax' => 'Se abilitato, Stripe calcolerà automaticamente le tasse',
        ],
        'labels' => [
            'sandbox' => 'Abilita modalità test (sandbox)',
            'automatic_tax' => 'Abilita calcolo automatico delle tasse',
        ],
    ],
]; 