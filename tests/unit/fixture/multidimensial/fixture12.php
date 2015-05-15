<?php

/**
 * Fixture for
 *  - Multidimensional::testSuccessValidationInNestedArray
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
            'comments' => [
                '_type' => 'nested_array',
                'comment' => [
                    '_type' => 'required_string'
                ]
            ]
            
        ]
    ]
];

$arr = [
    'name' => [
        'en' => 'Serkin Alexander',
        'ru' => 'Серкин Александр',
        'comments' => [
            [
                'comment' => 'Comment'
            ]
        ],
    ]
];
