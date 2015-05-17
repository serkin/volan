<?php

/**
 * Fixture for
 *  - Driver:::Number::testSuccessValidation
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
        'sort' => 2
    ]
];
