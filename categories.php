<?php
/**
 * Created by PhpStorm.
 * User: Tosh
 * Date: 28/08/2017
 * Time: 11:14
 */

$items = [
    [
        'id'        => 1,
        'name'      => 'Europe News',
        'image_url' => ''
    ],
    [
        'id'        => 2,
        'name'      => 'Asia News',
        'image_url' => ''
    ],
    [
        'id'        => 3,
        'name'      => 'Business Reuters',
        'image_url' => ''
    ],
    [
        'id'        => 4,
        'name'      => 'Business NASDAQ',
        'image_url' => ''
    ],
    [
        'id'        => 5,
        'name'      => 'Technology CNN',
        'image_url' => ''
    ],
    [
        'id'        => 6,
        'name'      => 'Technology UK',
        'image_url' => ''
    ],
    [
        'id'        => 7,
        'name'      => 'Technology Reuters',
        'image_url' => ''
    ],
    [
        'id'        => 8,
        'name'      => 'General News',
        'image_url' => ''
    ]
];


header('Content-Type: application/json');
echo json_encode($items);
