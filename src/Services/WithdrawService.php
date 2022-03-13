<?php
namespace Yahyya\FeeCalulation\Services;
use Yahyya\FeeCalculation\Models\BussinessUser;
use Yahyya\FeeCalculation\Models\PrivateUser;
use Yahyya\FeeCalulation\Enum\OperationType;
use Yahyya\FeeCalulation\Models\Operation;
use Yahyya\FeeCalulation\Repositories\OperationRepository;
use Yahyya\Helpers\Helpers;

class WithdrawService extends OperationService
{

    private $comitionFee = 0;
    private $weeklyAmountForFreeOfChargeInEuro = 1000.00;
    private $repo = null;
    public function __construct(Operation $op)
    {
        parent::__construct($op);
        $this->repo = new OperationRepository();
    }

    public function calcFee(): string
    {

        if($this->op->user instanceof PrivateUser){
            $itemsOfTheWeek = $this->repo
                ->filterByUserId($this->op->user->id)
                ->filterByType(OperationType::WITHDRAW)
                ->filterByWeek();

            $fee = $this->calcItemsFee($itemsOfTheWeek);
            return $fee * $this->op->amount;
        }

        if($this->op->user instanceof BussinessUser){
            return $this->op->user->getWithdrawFee() * $this->op->amount;
        }
    }


    private function calcItemsFee($items)
    {
        $totalItemsCalculated = 0;
        $totalAmount = 0;

        foreach($items as $item){
            $totalAmount += $this->convertToEu($item->amount);
            $totalItemsCalculated++;
        }

        return $this->checkFeeRules($totalAmount,$totalItemsCalculated);
    }

    private function checkFeeRules($totalAmount, $totalItemsCalculated){

        if($totalAmount <= $this->weeklyAmountForFreeOfChargeInEuro){
            $fee =  $totalAmount * $this->op->user->withdrawFee;
        }

        if($totalAmount > $this->weeklyAmountForFreeOfChargeInEuro && $totalItemsCalculated<4){
            $fee = ($totalAmount - $this->weeklyAmountForFreeOfChargeInEuro) * $this->op->user->withdrawFee;
        }

        return $fee;
    }

    private function convertToEu($amount)
    {
        return Helpers::convertToEur( $amount,$this->op->currency);
    }


}