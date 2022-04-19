<?php
namespace App\Lib\Services;
use App\Lib\Interfaces\OrderRepositoryInterface;
use App\Models\ORder;
use App\Models\Transaction;

class TransactionManager {
    private $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository){
        $this->transactionRepository=$transactionRepository;
    }
    public function add(Transaction $trans): void
    {
        $this->transactionRepository->add($trans);
    }
    public function find(string $id):Transaction
    {
        return $this->transactionRepository->find($id);
    }
}
