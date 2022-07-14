# Use
1. init project 
```shell
git clone https://github.com/era269/movies.git
cd movies
docker-compose up
docker exec -it movies_php_1 bin/console doctrine:migrations:migrate
docker exec -it movies_php_1 bin/console doctrine:fixtures:load 
```

2. run tests
```shell
docker exec -it movies_php_1 bin/console doctrine:fixtures:load --env=test 
docker exec -it movies_php_1 bin/phpunit

```
3. add movie:
```shell
curl 
```
4. get movie 
```shell
curl 
```
5. get movies 
```shell
curl 
```
