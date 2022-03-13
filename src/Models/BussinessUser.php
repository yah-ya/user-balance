<?php
namespace Yahyya\FeeCalculation\Models;

class BussinessUser extends user {
    protected float $depositFee = 0.03;
    protected float $withdrawFee = 0.5;
}