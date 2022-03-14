<?php
namespace Yahyya\FeeCalulation\Repositories;
use Yahyya\FeeCalulation\Interfaces\OperationRepositoryInterface;

class OperationRepository implements OperationRepositoryInterface
{

    private $items = [];
    public function __construct(array $items=[])
    {
        $this->items = $items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function get(): array
    {

        return $this->items;
    }

    /**
     * @param $userId
     */
    public function filterByUserId($userId): static
    {

        $result = [];
        foreach($this->items as $item){

            if($item->user->id == $userId){
                $result[] = $item;
            }
        }
        $this->items = $result;

        return $this;
    }

    /**
     * @param array $items - List Of Operations Model
     * @param $date (Starting date of the week)
     */
    public function filterByWeek($date):static
    {
        $result = [];

        foreach($this->items as $item){
            //check if previous transactions
            if(strtotime((string)$item->date) < strtotime($date)) {
                //check if same week
                if (gmdate('oW', strtotime($item->date)) == gmdate('oW', strtotime($date))) {
                    $result[] = $item;
                }
            }
        }

        $this->items = $result;
        return $this;
    }


    /**
     * @param $type type of the operation
     * @return $this
     */
    public function filterByType($type):static
    {

        $result = [];

        foreach($this->items as $item){
            if($item->type == $type)
                $result[] = $item;
        }

        $this->items = $result;
        return $this;
    }

}