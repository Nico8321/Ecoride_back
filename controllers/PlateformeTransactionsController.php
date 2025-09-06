<?php

require_once __DIR__ . '/../models/PlateformeTransactions.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/securisationSortie.php';


class PlateformeTransactionsController
{
    private $pdo;

    public function __construct()    //connection a la bdd
    {
        $this->pdo = Database::getConnection();
    }

    public function getPdo()
    {
        return $this->pdo;
    }
    public function addCreditPf($montant)
    {
        $success = PlateformeTransactions::addCredit($this->pdo, $montant);

        if ($success) {
            return true;
        } else {
            return false;
        }
    }

    public function getHistorique()
    {
        $data = PlateformeTransactions::historiqueTransaction($this->pdo);

        if (!$data || count($data) === 0) {
            http_response_code(200);
            echo json_encode([]);
            return;
        }

        http_response_code(200);
        echo json_encode(securisationSortie($data));
        return;
    }
}
