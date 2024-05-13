<?php
use Aws\DynamoDb\DynamoDbClient;

// create network
// docker network create --driver bridge cache-test
// create cache services
// docker run -tid --name redis --network cache-test redis
// docker run -tid --name dynamodb --network cache-test amazon/dynamodb-local
// docker run --name scylla --network cache-test --hostname some-scylla -d scylladb/scylla --smp 1

// docker run -tid --name php-cli -v ./testCode/cache-test:/app --network cache-test php:8.2 bash


include "/app/vendor/autoload.php";
// connection test
$redisEndpoint = getenv("REDISDB");

$redis = new Redis();
$redis->connect($redisEndpoint);
$redis->set('A',"a");
if ("a" == $redis->get("A")) {
    echo "Redis/MemoryDB connected \n";
}

// DynamoDB
$dynamoEndpoint = getenv("DYNAMODB");

$dynamo = new DynamoDbClient(["endpoint" => "http://$dynamoEndpoint:8000"]);
$tables = $dynamo->listTables();
$tables =  $tables->get("TableNames");
echo "DynamoDB connected \n";

// scyllaDB

