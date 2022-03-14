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

    private float $weeklyAmountForFreeOfChargeInEuro = 1000.00;
    private $repo = null;
    public function __construct(Operation $op,OperationRepository $repo)
    {
        parent::__construct($op);
        $this->repo = $repo;
    }

    public function calcFee(): string
    {
        if($this->op->user instanceof PrivateUser){
           return $this->calcFeeForPrivateUser();
        }

        if($this->op->user instanceof BussinessUser){
            return $this->calcFeeForBussinessUser();
        }
    }


    private function calcFeeForBussinessUser()
    {
        return ($this->op->user->getWithdrawFee() * $this->op->amount)/100;
    }

    private function calcFeeForPrivateUser()
    {
        //filter items of the repo
        $itemsOfTheWeekForThisOperation = $this->repo
            ->filterByUserId($this->op->user->id)
            ->filterByType(OperationType::WITHDRAW)
            ->filterByWeek($this->op->date)
            ->get();


        // get total amounts from the filtered items
        $totals = $this->getTotals($itemsOfTheWeekForThisOperation);
        $fee = $this->setFeeRules($totals['totalAmount'],$totals['totalItemsCalculated']);


        return $fee/100 ;
    }

    private function getTotals(array $items)
    {
        $totalItemsCalculated = 0;
        $totalAmount = $this->op->getAmountInEUR();

        foreach($items as $item){
            if($item->id==$this->op->id)
                continue;
            $totalAmount += $item->getAmountInEUR();
            $totalItemsCalculated++;
        }


        return [
            'totalItemsCalculated'=>$totalItemsCalculated,
            'totalAmount'=>$totalAmount
        ];

    }

    // Check if the amount is lower than weekly free of charge
    // or total items is less than weekly limit
    private function setFeeRules($totalAmount, $totalItemsCalculated){

        print_r("Total amount ".$totalAmount);
        print_r("Total ".$totalItemsCalculated.'-');
        $amount = $this->op->getAmountInEUR();
        if($totalAmount >= $this->weeklyAmountForFreeOfChargeInEuro && $totalItemsCalculated<4){
            if ($totalItemsCalculated<1)
                $amount = ($amount - $this->weeklyAmountForFreeOfChargeInEuro);
        }
        if($totalAmount<$this->weeklyAmountForFreeOfChargeInEuro)
            return 0;
        $fee =  $amount * $this->op->user->getWithdrawFee();

        return $fee;
    }



}