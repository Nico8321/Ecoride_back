<?php

class PlateformeTransactions
{
    private $id;
    private $credit;
    private $date_transaction;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->credit = $data['credit'];
        $this->date_transaction = $data['date_transaction'];
    }

    public static function addCredit(PDO $pdo, $montant)
    {
        $stmt = $pdo->prepare("INSERT INTO plateforme_transactions (credit) VALUES (?)");

        return $stmt->execute([
            $montant
        ]);
    }
    public static function historiqueTransaction(PDO $pdo)
    {
        $stmt = $pdo->prepare('SELECT DATE(date_transaction) AS jour,
            CAST(SUM(credit) AS UNSIGNED) AS total
            FROM plateforme_transactions
            GROUP BY DATE(date_transaction)
            ORDER BY jour ASC;');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
