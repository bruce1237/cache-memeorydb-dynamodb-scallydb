# cache test for MemoryDB/redis, DynamoDB and ScyllaDB

after `docker compose up`, attach to the phpcli container by `docker attach phpcli` inside the container, run `composer install` 

## test connection
run `php src/connection_test.php`