<?php
require_once __DIR__ . '/../vendor/autoload.php';

#$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
#$dotenv->load();

use MongoDB\Client;
use MongoDB\Collection;

final class MongoClientFactory
{
    private function __construct() {}

    public static function getCollection(string $name): ?Collection
    {

        try {
            $uri    = $_ENV['MONGO_URI'] ?? null;
            $dbName = $_ENV['MONGO_DB']  ?? 'ecoride';
            if (!$uri) {
                throw new \RuntimeException('MONGO_URI manquant dans .env');
            }
            static $client = null;
            static $db     = null;

            if (!$client) {
                $client = new Client($uri);
                $db     = $client->selectDatabase($dbName);
            }

            return $db->selectCollection($name);
        } catch (\Throwable $e) {
            error_log('Mongo error: ' . $e->getMessage());
            return null;
        }
    }
}
