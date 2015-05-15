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
    'noname' => 'What is -noname- key doing here?'  // Expecting 'name' here
];