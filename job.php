<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
require "vendor/autoload.php";



use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/App/dependencies.php');
$container = $containerBuilder->build();

$transactionManager = $container->get('TransactionManager');

set_time_limit(0);
while (true) {

    $totalTransactions = $transactionManager->getTotalTransactions();
    print $totalTransactions['total'];
    sleep(86400);//15 minutes
}

