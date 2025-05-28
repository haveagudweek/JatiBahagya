<?php

return [
    /**
     * Struktur ini menyediakan array 'cloud' secara eksplisit,
     * yang dicari oleh CloudinaryServiceProvider.
     */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        // Jika CLOUDINARY_URL valid dan ingin digunakan juga di dalam 'cloud' array,
        // Anda bisa uncomment baris di bawah dan pastikan CLOUDINARY_URL di-set di Render ENV.
        // Untuk memaksa penggunaan individual keys, biarkan ini dikomen atau pastikan CLOUDINARY_URL kosong di ENV.
        // 'url'        => env('CLOUDINARY_URL')
    ],

    /**
     * CLOUDINARY_URL sebagai top-level key juga bisa tetap ada
     * jika ada bagian lain dari SDK yang mungkin masih merujuk ke sini.
     * Pastikan CLOUDINARY_URL TIDAK di-set di Render ENV jika Anda ingin
     * memaksa penggunaan individual keys di atas untuk bagian 'cloud'.
     */
    'cloud_url' => env('CLOUDINARY_URL'), 

    /*
     * Pengaturan lain
     */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
    // 'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'), // Komen jika tidak dipakai
    // 'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'), // Komen jika tidak dipakai
];