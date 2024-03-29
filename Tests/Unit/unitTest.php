<?php
use PHPUnit\Framework\TestCase;

class unitTest extends TestCase {

    // check if db is connected
    // check if can do queries
    // check if tables are there


    function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    function test_connected_to_db()
    {
        $pdo = (new \App\Lib\Services\MySql\MySql())->connect();
        $this->assertNotNull($pdo);
    }

    function test_can_query()
    {
        $queryBuilder = new \App\Lib\Services\MySql\MySqlQueryBuilder();
        $query = $queryBuilder->createTable("users",
            [
                "id INTEGER PRIMARY KEY",
                "name TEXT NOT NULL",
                "last_name TEXT not null"
            ]);

        $mysql = new \App\Lib\Services\MySql\MySql();
        $mysql->connect();
        $mysql->query($query);
        $tables = $mysql->getTables();
        $this->assertEquals(['transactions','users'],$tables);

        $query = $queryBuilder->table('users')->select('*')->get();

        $users = $mysql->fetchData($query);
        $this->assertIsArray($users);
    }

    function test_can_add_and_get_users()
    {
        $queryBuilder = new \App\Lib\Services\MySql\MySqlQueryBuilder();
        $mysql = new \App\Lib\Services\MySql\MySql();
        $userRepository = new \App\Lib\Repository\UserRepository($mysql,$queryBuilder);

        $newUser = new \App\Models\User(1,'Yahyya','Taashk','y.t.15132@gmail.com');
        $userRepository->add($newUser);

        $user = $userRepository->find(1);
        $this->assertIsObject($user);
    }

    function test_can_get_user_balance()
    {
        $queryBuilder = new \App\Lib\Services\MySql\MySqlQueryBuilder();
        $mysql = new \App\Lib\Services\MySql\MySql();
        $repo = new \App\Lib\Repository\TransactionRepository($mysql,$queryBuilder);
        $userRepository = new \App\Lib\Repository\UserRepository($mysql,$queryBuilder);

        $user = $userRepository->find(1);
        $res = $repo->getByUserId($user->getId());
        $this->assertIsArray($res);
    }

    function test_can_add_to_user_wallet()
    {
        $queryBuilder = new \App\Lib\Services\MySql\MySqlQueryBuilder();
        $mysql = new \App\Lib\Services\MySql\MySql();
        $repo = new \App\Lib\Repository\TransactionRepository($mysql,$queryBuilder);
        $userRepository = new \App\Lib\Repository\UserRepository($mysql,$queryBuilder);

        $user = $userRepository->find(1);
        $res = $repo->getByUserId($user->getId());

        $this->assertIsArray($res);
        $total = 0;
        foreach($res as $bl)
        {
            $total += $bl['amount'];
        }

        $repo->addToUserId($user->getId(),100);
        $userNewBalance = $repo->getByUserId($user->getId());
        $totalNew = 0;
        foreach($userNewBalance as $bl)
        {
            $totalNew += $bl['amount'];
        }

        $this->assertEquals($total+100,$totalNew);

    }
}
