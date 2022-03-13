<?php
namespace Yahyya\FeeCalulation\Interfaces;

use Yahyya\FeeCalculation\Models\User;
use Yahyya\FeeCalulation\Models\Currency;

interface OperationInterface{
    public function calcFee():string;
}
