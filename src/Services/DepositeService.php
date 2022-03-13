<?php
namespace Yahyya\FeeCalulation\Services;

use Yahyya\FeeCalulation\Models\Operation;
use Yahyya\FeeCalulation\Repositories\OperationRepository;

class DepositeService extends OperationService
{
    public function __construct(Operation $op)
    {
        parent::__construct($op);
    }

    public function calcFee(): string
    {
        return $this->op->amount * $this->op->user->getDepositeFee();
    }
}