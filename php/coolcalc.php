<?php
// Load up all the thrift stuff
function my_autoloader($class) {
    require_once
        '/usr/local/lib/php/' . str_replace('\\', '/', $class) . '.php';
}

spl_autoload_register('my_autoloader');

// thrift compiler generated these modules
require_once '/Users/joeldodge/thriftdemo/php/gen-php/tutorial/Calculator.php';
require_once '/Users/joeldodge/thriftdemo/php/gen-php/tutorial/Types.php';

use Thrift\Transport, Thrift\Protocol;

try {
    // Create a thrift connection (Boiler plate)
    $socket = new Transport\TSocket('localhost', '9090');
    $transport = new Transport\TFramedTransport($socket);
    $protocol = new Protocol\TBinaryProtocol($transport);

    // Create a calculator client
    $client = new tutorial\CalculatorClient($protocol);

    // Open up the connection
    $transport->open();

    // First, lets do something simple
    // Create a simple arithmatic operation (99 / 3)
    $work = new tutorial\Work();
    $work->op = tutorial\Operation::DIVIDE;
    $work->num1 = 99;
    $work->num2 = 0;
    $work->comment = 'time to divide';
    var_dump($work);

    // Perform operation on the server
    $sum = $client->calculate(5, $work);
    var_dump($sum);

    // And finally, we close the thrift connection
    $transport->close();

} catch (tutorial\InvalidOperation $ex) {
    // performed an illegal operation, like 10/0
    echo "InvalidOperation: ".$ex->why."\r\n";

} catch (Thrift\Exception\TException $tx) {
    // a general thrift exception, like no such server
    echo "ThriftException: ".$tx->getMessage()."\r\n";
}
?>
