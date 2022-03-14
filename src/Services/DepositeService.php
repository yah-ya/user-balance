<?php
namespace Yahyya\FeeCalulation\Services;

use Yahyya\FeeCalulation\Models\Operation;
use Yahyya\FeeCalulation\Repositories\OperationRepository;

class DepositeService extends OperationService
{
    public function __construct(Operation $op)
    {
        print " Deposit " . $op->id;
        parent::__construct($op);
    }

    public function calcFee(): string
    {
        print_r($this->op->amount) . ' .... ' . $this->op->user->getDepositFee();
        return $this->op->amount * $this->op->user->getDepositFee()/100;
    }
}