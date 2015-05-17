<?php

/**
 * Fixture for Volan::testErrorOnMissingRequiredField
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_string'
            ]
        ]
    ];

$arr = [
     // Expecting 'name' here
];