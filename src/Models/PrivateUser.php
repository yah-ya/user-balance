<?php
namespace Yahyya\FeeCalculation\Models;

class PrivateUser extends User {
    protected float $depositFee = 0.03;
    protected float $withdrawFee = 0.3;
}