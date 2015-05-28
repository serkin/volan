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
                '_type' => 'required_number'
                ]
            ]
        ]
    ];

$arr = [
    'name' => [
        'sold' => 'yes'
    ]
];
