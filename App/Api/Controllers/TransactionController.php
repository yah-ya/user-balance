<?php
namespace App\Api\Controllers;

use App\Lib\Repository\TransactionRepository;
use App\Models\Transaction;
use App\Models\User;

class TransactionController
{
    private $transactionRepository;
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository=$transactionRepository;
    }

    public function getUserBalance(User $user)
    {
        $transactions = $this->transactionRepository->getByUserId($user->getId());
        $total = 0;
        foreach($transactions as $tr)
        {
            $total += $tr['amount'];
        }
        print "Total Balance:" .$total;
    }

    public function setTransactionAmount(User $user,int $amount):string
    {
        return $this->transactionRepository->addToUserId($user->getId(),$amount);
    }
}
