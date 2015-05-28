<?php

/**
 * Fixture for
 *  - Multidimensional::testErrorOnInvalidNode
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_array',
            'en' => [
                '_type' => 'required_string'
                ],
            'ru' => [
                '_type' => 'required_string'
                ],
            ]
        ]
    ];

$arr = [
    'name' => [
        'en' => 'Serkin Alexander',
        'ru' => 9 // It has to be string
    ]
];
