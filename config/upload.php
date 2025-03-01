<?php

return [
    'max_file_size' => env('UPLOAD_MAX_FILESIZE', '10M'),
    'post_max_size' => env('POST_MAX_SIZE', '10M'),
    'max_execution_time' => env('MAX_EXECUTION_TIME', 300),
    'memory_limit' => env('MEMORY_LIMIT', '128M'),
]; 