<?php
namespace App\Lib\Interfaces;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function addToUserId(int $userId,int $amount): string;
    public function find(string $id):Transaction|bool;
}
