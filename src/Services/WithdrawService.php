<?php
namespace Yahyya\FeeCalulation\Services;
use Yahyya\FeeCalculation\Models\BussinessUser;
use Yahyya\FeeCalculation\Models\PrivateUser;
use Yahyya\FeeCalulation\Enum\OperationType;
use Yahyya\FeeCalulation\Models\Operation;
use Yahyya\FeeCalulation\Repositories\OperationRepository;

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
        $fee = $this->setFeeRules($totals['totalAmountCalculated'],$totals['totalItemsCalculated']);


        return $fee/100 ;
    }

    private function getTotals(array $items)
    {
        $totalItemsCalculated = 0;
        $totalAmountCalculated = 0;

        foreach($items as $item){
            if($item->calculated) {
                $totalAmountCalculated += $item->getAmountInEUR();
                $totalItemsCalculated++;
            }
        }


        return [
            'totalItemsCalculated'=>$totalItemsCalculated,
            'totalAmountCalculated'=>$totalAmountCalculated
        ];

    }

    // Check if the amount is lower than weekly free of charge
    // or total items is less than weekly limit
    private function setFeeRules($totalAmountCalculated, $totalItemsCalculated){

        $amount = $this->op->getAmountInEUR();

        if($amount >= $this->weeklyAmountForFreeOfChargeInEuro &&
            $totalAmountCalculated < $this->weeklyAmountForFreeOfChargeInEuro &&
            $totalItemsCalculated<4)
        {
            $this->op->calculated=true;
            $amount = ($amount - $this->weeklyAmountForFreeOfChargeInEuro);
        }
        return $amount * $this->op->user->getWithdrawFee();

    }



}