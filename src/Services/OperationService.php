<?php
namespace Yahyya\FeeCalulation\Services;
use Yahyya\FeeCalculation\Models\User;
use Yahyya\FeeCalulation\Enum\OperationType;
use Yahyya\FeeCalulation\Models\Currency;
use Yahyya\FeeCalulation\Interfaces\OperationInterface;
use Yahyya\FeeCalulation\Models\Operation;
use Yahyya\FeeCalulation\Repositories\OperationRepository;

abstract class OperationService implements OperationInterface
{
    protected Operation $op;

    public function __construct(Operation $op)
    {
        $this->op = $op;
    }

    public function calcFee(): string
    {
    }

}