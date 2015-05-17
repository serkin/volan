<?php

/**
 * Fixture for
 *  - Driver:::Boolean::testSuccessValidation
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_array',
            'sold' => [
                '_type' => 'boolean'
                ]
            ]
        ]
    ];

$arr = [
    'name' => [
        'sold' => true
    ]
];
