# cache test for MemoryDB/redis, DynamoDB and ScyllaDB

after `docker compose up`, attach to the phpcli container by `docker attach phpcli` inside the container, run `composer install` and `aws configure` and put anything for access key Id and access key and region with valid aws region

## test connection
run `php src/connection_test.php`

if you see the following msg, it mean it works
```bash
Redis/MemoryDB connected 
DynamoDB connected 
```

then you can use `$redis` for redis and `$dynamo` for dynamoDB for testing.
