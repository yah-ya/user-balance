<?php
namespace App\DB;
require "vendor/autoload.php";

new Seed();
class Seed
{

     public function __construct(){
        $query = $this->createUsersTable();
        $query .= $this->createTransactionsTable();

         $sqlite = new \App\Lib\Services\MySql\MySql();
         $sqlite->connect();
         $sqlite->query($query);
//        $this->seed();
    }

    private function createUsersTable()
    {
        $queryBuilder = new \App\Lib\Services\MySql\MySqlQueryBuilder();
        $query = $queryBuilder->createTable("users",
            [
                "id INTEGER PRIMARY KEY",
                "name TEXT NOT NULL",
                "last_name TEXT not null"
            ]);
        return $query;
    }

    private function createTransactionsTable()
    {
        $queryBuilder = new \App\Lib\Services\MySql\MySqlQueryBuilder();
        return $queryBuilder->createTable("transactions",
            [
                "id INTEGER PRIMARY KEY",
                "reference_id INTEGER NOT NULL",
                "user_id INTEGER NOT NULL",
                "amount INTEGER NOT NULL",
                "created_at DATETIME NOT NULL",
            ]);
    }

    private function seed($number=100)
    {

        $queryBuilder = new \App\Lib\Services\MySql\MySqlQueryBuilder();

        $mysql = new \App\Lib\Services\MySql\MySql();
        $mysql->connect();

        $query = $queryBuilder->table('transactions')->insert([]);
        $query .= $queryBuilder->table('users')->insert([]);
        $mysql->query($query);

    }

}
