<?php

/*
 * This file is part of the Laravel Cloudinary package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL (Primary Method)
    |--------------------------------------------------------------------------
    | Jika CLOUDINARY_URL di-set di .env atau environment server, ini akan digunakan.
    | Jika tidak, ia akan mencoba membangunnya dari CLOUDINARY_KEY, SECRET, dan CLOUD_NAME.
    | Namun, kita juga akan menyediakan key individual di bawah sebagai fallback yang lebih kuat.
    */
    'cloud_url' => env('CLOUDINARY_URL', 
        (env('CLOUDINARY_API_KEY') && env('CLOUDINARY_API_SECRET') && env('CLOUDINARY_CLOUD_NAME')) ?
        'cloudinary://'.env('CLOUDINARY_API_KEY').':'.env('CLOUDINARY_API_SECRET').'@'.env('CLOUDINARY_CLOUD_NAME') :
        null
    ),

    /*
    |--------------------------------------------------------------------------
    | Individual Cloudinary Credentials (Sangat Direkomendasikan)
    |--------------------------------------------------------------------------
    | Service Provider akan mencoba membaca ini jika cloud_url parsing bermasalah
    | atau sebagai sumber utama tergantung implementasi package.
    | Pastikan environment variable-nya sudah di-set di Render.
    */
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', ''),
    'api_key'    => env('CLOUDINARY_API_KEY', ''),
    'api_secret' => env('CLOUDINARY_API_SECRET', ''),

    /**
     * Upload Preset From Cloudinary Dashboard
     */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /**
     * Route to get cloud_image_url from Blade Upload Widget
     */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),

    /**
     * Controller action to get cloud_image_url from Blade Upload Widget
     */
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),
];