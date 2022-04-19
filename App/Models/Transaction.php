<?php
namespace App\Models;
class Transaction {
    private $id;
    private $userId;
    private $referenceId;
    private $amount;

    public function __construct(int $id, int $userId, int $referenceId, int $amount)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->refrenceId = $referenceId;
        $this->amount = $amount;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getReferenceId()
    {
        return $this->refrenceId;
    }

}
