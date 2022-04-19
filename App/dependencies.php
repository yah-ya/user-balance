<?php

return [
    'TransactionManager' => DI\create(\App\Lib\Repository\TransactionRepository::class)
        ->constructor(DI\get(\App\Lib\Services\MySql\MySql::class),DI\get(\App\Lib\Services\MySql\MySqlQueryBuilder::class)),

    'UserManager' => DI\create(\App\Lib\Repository\UserRepository::class)
        ->constructor(DI\get(\App\Lib\Services\MySql\MySql::class),DI\get(\App\Lib\Services\MySql\MySqlQueryBuilder::class)),

];
