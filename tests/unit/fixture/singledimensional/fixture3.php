<?php

/**
 * Fixture for Volan::testErrorOnMissingTypeField
 */

$schema = [
    'root' => [
        'name' => [
            'type' => 'required_tring'  // Missing _type
            ]
        ]
    ];

$arr = [
    'name' => 'I am a correct string'
];