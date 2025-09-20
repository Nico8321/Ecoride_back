<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Cloudinary\Cloudinary;

class CloudinaryClient
{
    private static $cloudinary;

    private function __construct() {}

    public static function getInstance(): Cloudinary
    {
        if (!self::$cloudinary) {
            self::$cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                    'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
                    'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
                ]
            ]);
        }
        return self::$cloudinary;
    }
}
