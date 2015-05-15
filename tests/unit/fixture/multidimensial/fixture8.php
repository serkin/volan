<?php

/**
 * Fixture for
 *  - Multidimensional::testErrorOnMissingRequiredField
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
        'en' => 'Serkin Alexander'
        // Expeting 'ru' => 'Серкин Александр' here

    ]
];
