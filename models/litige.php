<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;


class Litige
{

    public static function create(Collection $col, $data)
    {
        if (empty($data['message'])) {
            throw new \InvalidArgumentException('Message obligatoire.');
        };
        $now = new UTCDateTime();
        $res = $col->insertOne([
            'reservation_id' => $data['reservation_id'],
            'utilisateur_id' => $data['utilisateur_id'],
            'message' => $data['message'],
            'status' => 'ouvert',
            'createdAt' => $now
        ]);
        return $res->getInsertedId();
    }
    public static function getById(Collection $col, $id)
    {

        $idLitige = new ObjectId($id);
        $res = $col->findOne(['_id' => $idLitige]);
        return $res;
    }
    public static function changeStatut(Collection $col, $id)
    {
        $idLitige = new ObjectId($id);
        $res = $col->updateOne(
            ['_id' => $idLitige],
            ['$set' => ['status' => 'En cours de traitement']]
        );
    }
}
