<?php
namespace Yahyya\FeeCalculation\Models;

abstract class User {
    public int $id;
    private string $name;
    protected float $depositFee = 0;
    protected float $withdrawFee = 0;

    public function getDepositFee(): float
    {
        return $this->depositFee;
    }

    public function getWithdrawFee(): float
    {
        return $this->withdrawFee;
    }
}