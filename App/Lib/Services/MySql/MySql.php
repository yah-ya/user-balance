<?php
namespace App\Lib\Services\MySql;
use App\Lib\Interfaces\DBInterface;

class MySql implements DBInterface {


    private $pdo = null;
    private $serverName = "localhost";
    private $dbName = "user-balance";
    private $username = "root";
    private $password = "";
    public function connect(): \PDO
    {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("mysql:host=$this->serverName;dbname=$this->dbName",$this->username,$this->password);
        }
        return $this->pdo;
    }

    public function query(string $query)
    {
        $this->pdo->exec($query);
    }

    public function fetchData(string $query):array
    {
        $stmt = $this->pdo->query($query);
        $res = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }
        return $res;
    }

    public function getTables(): array
    {
        $stmt = $this->pdo->query("SHOW TABLES");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            $tables[] = $row['Tables_in_user-balance'];
        }
        return $tables;
    }
}
