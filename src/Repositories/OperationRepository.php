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

    public function all(): array
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
    public function filterByWeek($date=null):static
    {
        $result = [];

        //if no date is provided , we will begin from the first item's date
        if(is_null($date)){
            $date = $this->items[0]->date;
        }

        foreach($this->items as $item){
            //check if same week
            if(date('W',strtotime($item->date)) == date('W',strtotime($date))){
                $result[] = $item;
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