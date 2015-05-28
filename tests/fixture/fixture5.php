<?php

/**
 * Fixture for Volan::testErrorOnInvalidNode
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_string'
            ]
        ]
    ];

$arr = [
    'name' => 9 // Has to be string
];