<?php

/**
 * Fixture for
 *  - Driver:::Boolean::testErrorOnValidation
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_array',
            'sold' => [
                '_type' => 'required_boolean'
                ]
            ]
        ]
    ];

$arr = [
    'name' => [
        'sold' => 'yes' // Should be boolean type
    ]
];
