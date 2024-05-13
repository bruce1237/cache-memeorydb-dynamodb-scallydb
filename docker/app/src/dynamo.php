<?php

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;

include "/app/vendor/autoload.php";


// DynamoDB
$dynamoEndpoint = getenv("DYNAMODB");

$dynamo = new DynamoDbClient(["endpoint" => "http://$dynamoEndpoint:8000"]);

// create table
$tableName = "tableTest";
$tableConfig = [
    "TableName" => $tableName,
    "AttributeDefinitions" => [
        [
            "AttributeName" => "UserId", // hashKey AKA partition key
            "AttributeType" => "S", // key type: S: string, N: number
        ],
        [
            "AttributeName" => "UserNo", // sortKey, RangeKey
            "AttributeType" => "N",
        ],
    ],
    "KeySchema" => [ // specify which key is which
        [
            "AttributeName" => "UserId",
            "KeyType" => "HASH", // UserId is hashKey
        ],
        [
            "AttributeName" => "UserNo",
            "KeyType" => "RANGE",
        ],
    ],
    "ProvisionedThroughput" => [
        // AWS DynamoDB 表的一个属性，用于指定表格在一定时间内可支持的读取和写入操作的最大速率。
        //它有两个方面：读取吞吐量和写入吞吐量
        "ReadCapacityUnits" => 5,  // reads per seconds
        "WriteCapacityUnits" => 5, // writes per second
    ]
];
// $dynamo->createTable($tableConfig);
$dynamo->listTables();

try {
    $dynamo->describeTable(["TableName"=>$tableName]);
} catch(DynamoDbException $e){
    echo "create table: $tableName \n";
    $dynamo->createTable($tableConfig);
}


// insert records / items in batch

$records = [
    "RequestItems" => [
        $tableName => [
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 1],
                        "UserNo" => ["N" => 11],
                        "FirstName" => ["S" => "FN11"],
                        "LastName" => ["S" => "LN11"],
                        "Age" => ["N" => 11],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 1],
                        "UserNo" => ["N" => 12],
                        "FirstName" => ["S" => "FN12"],
                        "LastName" => ["S" => "LN12"],
                        "Age" => ["N" => 12],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 1],
                        "UserNo" => ["N" => 13],
                        "FirstName" => ["S" => "FN13"],
                        "LastName" => ["S" => "LN13"],
                        "Age" => ["N" => 13],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 2],
                        "UserNo" => ["N" => 21],
                        "FirstName" => ["S" => "FN21"],
                        "LastName" => ["S" => "LN21"],
                        "Age" => ["N" => 21],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 2],
                        "UserNo" => ["N" => 22],
                        "FirstName" => ["S" => "FN22"],
                        "LastName" => ["S" => "LN22"],
                        "Age" => ["N" => 22],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 2],
                        "UserNo" => ["N" => 23],
                        "FirstName" => ["S" => "FN23"],
                        "LastName" => ["S" => "LN23"],
                        "Age" => ["N" => 23],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 3],
                        "UserNo" => ["N" => 31],
                        "FirstName" => ["S" => "FN31"],
                        "LastName" => ["S" => "LN31"],
                        "Age" => ["N" => 31],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 3],
                        "UserNo" => ["N" => 32],
                        "FirstName" => ["S" => "FN32"],
                        "LastName" => ["S" => "LN32"],
                        "Age" => ["N" => 32],
                    ],
                ],
            ],
            [
                "PutRequest" => [
                    "Item" => [
                        "UserId" => ["S" => 3],
                        "UserNo" => ["N" => 33],
                        "FirstName" => ["S" => "FN33"],
                        "LastName" => ["S" => "LN33"],
                        "Age" => ["N" => 33],
                    ],
                ],
            ],
        ],
    ],
];


$dynamo->batchWriteItem($records);


$items = $dynamo->scan(["TableName"=>$tableName]);



foreach($items['Items'] as $item) {
    // var_dump($item);
    echo "{$item['UserId']["S"]} ({$item['UserNo']["N"]}) - {$item['FirstName']["S"]}, {$item['LastName']["S"]} @ {$item['Age']["N"]}";
    echo PHP_EOL.PHP_EOL;
}


$deleteKey=3;

$searchQuery = [
    "TableName" => $tableName,
    "KeyConditionExpression" => 'UserId = :id',
    "ExpressionAttributeValues" => [
        ':id' => ["S"=>$deleteKey],
    ],
];

$searchResult = $dynamo->query($searchQuery);

foreach($searchResult['Items'] as $item) {
    $key = [
        'UserId'=>$item['UserId'],
        'UserNo'=>$item['UserNo'],
    ];

    var_dump($key);

    $delPara = [
        "TableName" => $tableName,
        "Key"=> $key
    ];

    $dynamo->deleteItem($delPara);

}

