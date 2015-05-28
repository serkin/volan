<?php

/**
 * Fixture for
 *  - Driver:::Number::testErrorOnValidation
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_array',
            'sort' => [
                '_type' => 'number'
                ]
            ]
        ]
    ];

$arr = [
    'name' => [
        'sort' => 'Serkin Alexander' // Should be number type
    ]
];
