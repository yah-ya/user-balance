<?php
namespace Yahyya\FeeCalulation\Controllers;

use Yahyya\FeeCalulation\Enum\OperationType;
use Yahyya\FeeCalulation\Models\Operation;
use Yahyya\FeeCalulation\Repositories\OperationRepository;
use Yahyya\FeeCalulation\Services\DepositeService;
use Yahyya\FeeCalulation\Services\WithdrawService;

class OperationController
{

    public function getFees()
    {
        $items = $this->readFileAndGetItems();

        $repo = new OperationRepository($items);

        $fees = [];
        foreach($items as $item){
            $repo->setItems($items);
            if($item->type==OperationType::DEPOSIT)
                $service = new DepositeService($item);
            else
                $service = new WithdrawService($item, $repo);


            $fees[] = number_format($service->calcFee(),2);
        }

        return $fees;

    }

    private function readFileAndGetItems(): array
    {
        $items = [];
        $handle = fopen("test.csv", "r");
        for ($i = 0; $row = fgetcsv($handle ); ++$i) {
            $row[0] = str_replace('﻿','',$row[0]);
            $item = new Operation($i+1,$row[0],$row[1],$row[2],$row[3],$row[4],$row[5]);
            $items[] = $item;
        }
        fclose($handle);
        return $items;
    }
}