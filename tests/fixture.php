<?php


$schema = [
    'root' => [
        'title' => [
            '_type' => 'required_string'
            ],
        'author' => [
            '_type' => 'required_string'
            ],
        'price' => [
            '_type' => 'number'
            ],
        //'tags' => ['_type' => 'array'],
        'instock' => [
            '_type' => 'required_boolean'
            ],
        'reserved' => [
            '_type' => 'boolean'
            ],
        'protagonist' => [
            '_type' => 'required_array',
            'name' => [
                '_type' => 'required_string'
                ],
            ],
        'comments' => [
            '_type' => 'nested_array',
            'comment' => [
                '_type' => 'required_string'
            ],
            'userid' => [
                '_type' => 'required_number'
            ],
            'rating' => [
                '_type' => 'number'
            ]
        ]
    ]
];

$arr = [
    'title'     => 'The Idiot',
    'instock'   => true,
    'author'    => 'Leo Tolstoy',
    'reserved'  => false,
    'price'     => 60,
    'protagonist' => ['name' => 'Lev Nikolayevich Myshkin'],
    //'tags'      => ['novel', 'Fyodor Dostoyevsky'],
    'comments'  => [
        [
            'comment'   => 'Good book',
            'userid'    => 1,
            'rating'    => 10
        ],
        [
            'comment'   => 'I love it',
            'userid'    => 2,
            'rating'    => 10
        ],
    ]
];
