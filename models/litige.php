<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;


class Litige
{

    private static function oid(string $id): ObjectId
    {
        try {
            return new ObjectId($id);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Id invalide : $id");
        }
    }

    //Creer Litige

    public static function create(Collection $col, $data)
    {
        if (empty($data['message'])) {
            throw new \InvalidArgumentException('Message obligatoire.');
        };

        $res = $col->insertOne([
            'reservation' => [
                'id' => $data['reservation_id'],
                'nb_places' => $data['nb_places']
            ],
            'redacteur' => [
                'id' => $data['utilisateur_id'],
                'pseudo' => $data['utilisateur_pseudo'],
                'mail' => $data['utilisateur_mail'],
            ],
            'conducteur' => [
                'id' => $data['conducteur_id'],
                'pseudo' => $data['conducteur_pseudo'],
                'mail' => $data['conducteur_mail'],
            ],
            'covoiturage' => [
                'date_depart' => $data['date_depart'],
                'prix' => $data['prix'],
                'ville_depart' => $data['ville_depart'],
            ],
            'message' => $data['message'],
            'status'    => 'en_attente',
            'suivi'     => [],
            'createdAt' => new UTCDateTime(),
            'updatedAt' => new UTCDateTime(),
        ]);
        return $res->getInsertedId();
    }

    //Recuperer litige par l'id

    public static function getById(Collection $col, $id)
    {


        return $col->findOne(['_id' => self::oid($id)]);
    }

    //Ajout d'une note de suivi au litige

    public static function addNote(Collection $col, $id, $note)
    {
        return $col->updateOne(
            ['_id' => self::oid($id)],
            [
                '$push' => ['suivi' => [
                    'note' => $note,
                    'at'      => new UTCDateTime()
                ]],
                '$set'  => ['status' => 'en_traitement', 'updatedAt' => new UTCDateTime()]
            ]
        );
    }

    // Récuperation des litiges en attente 

    public static function getLitigeEnAttente(Collection $col)
    {
        return $col->find(['status' => 'en_attente'], ['sort' => ['createdAt' => -1]]);
    }
    // Récuperation des litiges en traitement

    public static function getLitigeEnTraitement(Collection $col)
    {
        return $col->find(['status' => 'en_traitement'], ['sort' => ['createdAt' => -1]]);
    }

    //Cloture d'un litige

    public static function cloture(Collection $col, $id)
    {
        return $col->updateOne(
            ['_id' => self::oid($id)],
            [
                '$set' => [
                    'status'    => 'clos',
                    'closedAt'  => new UTCDateTime(),
                    'updatedAt' => new UTCDateTime()
                ]
            ]
        );
    }
}
