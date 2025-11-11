<?php

return [
    'login' => [
        'max_attempts' => 5,        // Numero massimo di tentativi
        'decay_minutes' => 1,       // Tempo di blocco in minuti
        'key' => 'login|:ip',       // Chiave per identificare il rate limiter
    ],
    'two-factor' => [
        'max_attempts' => 5,
        'decay_minutes' => 1,
        'key' => 'two-factor|:ip',
    ],
    'forgot-password' => [
        'max_attempts' => 5,
        'decay_minutes' => 60,
        'key' => 'forgot-password|:ip',
    ],
    'verify-email' => [
        'max_attempts' => 6,
        'decay_minutes' => 1,
        'key' => 'verify-email|:ip',
    ],
]; 