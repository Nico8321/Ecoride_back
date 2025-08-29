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
                'id'         => $data['reservation']['id'],
                'nb_places'  => $data['reservation']['nb_places'],
            ],
            'redacteur' => [
                'id'     => $data['redacteur']['id'],
                'pseudo' => $data['redacteur']['pseudo'],
                'mail'   => $data['redacteur']['mail'],
            ],
            'conducteur' => [
                'id'     => $data['conducteur']['id'],
                'pseudo' => $data['conducteur']['pseudo'],
                'mail'   => $data['conducteur']['mail'],
            ],
            'covoiturage' => [
                'date_depart'    => $data['covoiturage']['date_depart'],
                'prix'           => $data['covoiturage']['prix'],
                'ville_depart'   => $data['covoiturage']['ville_depart'],
                'ville_arrivee'  => $data['covoiturage']['ville_arrivee'],
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
