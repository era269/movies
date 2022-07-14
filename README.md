# Use
1. init project 
```shell
git clone https://github.com/era269/movies.git
cd movies
docker-compose up
docker exec -it movies_php_1 doctrine:database:drop
docker exec -it movies_php_1 doctrine:database:create
docker exec -it movies_php_1 bin/console doctrine:migrations:migrate
docker exec -it movies_php_1 bin/console doctrine:fixtures:load 
```

2. run tests
```shell
docker exec -it movies_php_1 doctrine:database:drop --env=test
docker exec -it movies_php_1 doctrine:database:create --env=test
docker exec -it movies_php_1 bin/console doctrine:migrations:migrate --env=test
docker exec -it movies_php_1 bin/console doctrine:fixtures:load --env=test 
docker exec -it movies_php_1 bin/phpunit

```
3. add movie:
```shell
curl -k --location --request POST 'https://localhost/api/v1/movies' \
--header 'Authorization: Basic dGVzdEBlbWFpbC5jb206MQ==' \
--header 'Content-Type: application/json' \
--header 'Cookie: PHPSESSID=0cm6kpbemlqf3t1ukg26pp32l7' \
--data-raw '{
    "name": "The Titanic",
    "casts":[
        "DiCaprio",
        "Kate Winslet"
    ],
    "release_date": "18-01-1998",
    "director": "James Cameron",
    "ratings": {
        "imdb": 7.8,
        "rotten_tomatto": 8.2
    }
}'
```
4. get movie 
```shell
curl -k --location --request GET 'https://localhost/api/v1/movies/The%20Titanic' \
--header 'Authorization: Basic dGVzdEBlbWFpbC5jb206MQ==' \
```
5. get movies 
```shell
curl -k --location --request GET 'https://localhost/api/v1/movies' \
--header 'Authorization: Basic dGVzdEBlbWFpbC5jb206MQ=='
```
