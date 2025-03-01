<?php

return [
    'max_file_size' => '100M',
    'post_max_size' => '100M',
    'memory_limit' => '256M',
    'max_execution_time' => 300,
    'allowed_mimes' => ['jpeg', 'jpg', 'png'],
    'max_dimensions' => [
        'width' => 5000,
        'height' => 5000
    ],
    'quality' => [
        'default' => 80,
        'min' => 1,
        'max' => 100
    ]
]; 