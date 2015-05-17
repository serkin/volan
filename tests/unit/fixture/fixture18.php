<?php

/**
 * Fixture for
 *  - Multidimensional::testErrorOnMissingTypeField
 */

$schema = [
    'root' => [
        'name' => [
            '_type' => 'required_array',
            'en' => [
                'type' => 'required_string' // Missing _type
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
        'ru' => 'Серкин Александр'
    ]
];
