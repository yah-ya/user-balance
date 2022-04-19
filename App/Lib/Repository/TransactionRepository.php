<?php
namespace App\Lib\Repository;
use App\Lib\Interfaces\DBInterface;
use App\Lib\Interfaces\QueryBuilderInterface;
use App\Lib\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use App\Models\User;

class TransactionRepository implements TransactionRepositoryInterface
{
    private $database;
    private $queryBuilder;
    public function __construct(DBInterface $database,QueryBuilderInterface $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $database->connect();
        $this->database=$database;
    }

    public function addToUserId(int $userId,int $amount): string
    {
        $reference = uniqid(rand(10*45,100*98), true);
        $query = $this->queryBuilder->table('transactions')->insert([[
            'user_id'=>$userId,
            'amount'=>$amount,
            'reference_id'=>$reference,
            'created_at'=>date('Y-m-d')
        ]]);
        $result = $this->database->query($query);
        return $reference;
    }

    public function find(string $id): Transaction|bool
    {

        $result = $this->queryBuilder->table('transactions')->select( "*")->where('id='.$id)->get();
        $result = $this->database->fetchData($result);
        if($result==false) return false;
        foreach($result as $item)
        {
            return new Transaction($item['id'],$item['user_id'],$item['reference_id'],$item['amount']);
        }
    }

    public function getByUserId(int $id):array
    {
        $result = $this->queryBuilder->table('transactions')->select( "*")->where('user_id='.$id)->get();
        $result = $this->database->fetchData($result);
        return $result;
    }

    public function getTotalTransactions():array
    {
        $result = $this->queryBuilder->table('transactions')->select( "sum(amount) as total")->get();
        $result = $this->database->fetchData($result);
        return $result[0];
    }

}
