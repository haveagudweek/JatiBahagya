<?php

/*
 * This file is part of the Laravel Cloudinary package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// config/cloudinary.php
return [
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    'cloud_url' => env('CLOUDINARY_URL', 
        (env('CLOUDINARY_API_KEY') && env('CLOUDINARY_API_SECRET') && env('CLOUDINARY_CLOUD_NAME')) ?
        'cloudinary://'.env('CLOUDINARY_API_KEY').':'.env('CLOUDINARY_API_SECRET').'@'.env('CLOUDINARY_CLOUD_NAME') :
        null
    ),

    // ---- BAGIAN INI SANGAT PENTING ----
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', ''),
    'api_key'    => env('CLOUDINARY_API_KEY', ''),
    'api_secret' => env('CLOUDINARY_API_SECRET', ''),
    // ------------------------------------

    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'), // Bisa dikomen jika tidak dipakai
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'), // Bisa dikomen jika tidak dipakai
];