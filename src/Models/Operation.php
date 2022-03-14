<?php
namespace Yahyya\FeeCalulation\Models;
use Yahyya\FeeCalculation\Models\BussinessUser;
use Yahyya\FeeCalculation\Models\PrivateUser;
use Yahyya\Helpers\Helpers;

class Operation
{

    public $id;
    public string $date;
    public $type;
    public $currency;
    public $user;
    public float $amount;

    public function __construct($id,string $date,int $userId,string $userType,string $type,float $amount,$currency)
    {
        $this->id = $id;
        $this->date = $date;
        $user = null;
        if($userType=='private')
        {
            $user = new PrivateUser();
        }
        if($userType=='business')
        {
            $user = new BussinessUser();
        }
        $user->id = $userId;

        $this->user = $user;
        $this->type = $type;
        $this->amount = $amount;
        $this->currency = $currency;

    }

    public function getAmountInEUR()
    {

        return Helpers::convertToEur($this->amount,$this->currency);
    }
}