<?php

/**
 * Fixture for Volan::testErrorOnMissingValidatorClaa
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_tring' // Validator 'required_tring' doesn't exist
            ]
        ]
    ];

$arr = [
    'name' => 'I am a correct string'
];