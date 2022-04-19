<?php
$request = explode('/',$_SERVER['REQUEST_URI']);
$request = end($request);

switch ($_GET['dir']) {
    case 'get-balance' :

        $userManager = $container->get('UserManager');
        $user = $userManager->find($_GET['user_id']);

        $transactionManager = $container->get('TransactionManager');
        $transactionController = new \App\Api\Controllers\TransactionController($transactionManager);

        $transactionController->getUserBalance($user);
        break;

    case 'add-money' :
        $userManager = $container->get('UserManager');
        $user = $userManager->find($_GET['user_id']);
        if(!$user){
            print "User Not Found!";
            exit;
        }
        $amount = $_GET['amount'];

        $transactionManager = $container->get('TransactionManager');
        $transactionController = new \App\Api\Controllers\TransactionController($transactionManager);

        $res = $transactionController->setTransactionAmount($user,$amount);
        print "Reference Id : ".$res;

}
