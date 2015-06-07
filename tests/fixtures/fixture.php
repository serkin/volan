<?php


$schema = [
    'root' => [
        'title' => [
            '_type' => 'required_string'
            ],
        'characters' => [
            '_type' => 'array_of_strings'
        ],
        'price' => [
            '_type' => 'number'
            ],
        'tags' => [
            '_type' => 'array'
            ],
        'instock' => [
            '_type' => 'required_boolean'
            ],
        'reserved' => [
            '_type' => 'boolean'
            ],
        'author' => [
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
    'reserved'  => false,
    'price'     => 60,
    'characters' => ['Lev Nikolayevich Myshkin'],
    'author' => ['name' => 'Fyodor Dostoyevsky'],
    'tags'      => ['novel', 'russia'],
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
