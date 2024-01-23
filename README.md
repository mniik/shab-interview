Shab Market Platform interview Task

## installation:
this project has ready installation process with laravel sail, clone the project and run:<br/>
`install.sh`<br/>
if your internet provider has a restriction on docker domains, you may need change in dns or
Virtual private network access to download respecting volumes.<br/>
volumes:<br/>
laravel/mysql/redis/mailpit/phpmyadmin/elasticsearch<br/>
* application access http://localhost<br/>
* mailpit access http://localhost:8025<br/>
* phpmyadmin access http://localhost:8080<br/>
* elasticsearch access http://localhost:9200<br/>

container up and down with `./vendor/bin/sail up -d` and `./vendor/bin/sail down` <br/>

for testing authenticated routes, first login and then set bearer token in postman<br/>

## description
git hooks file is included in project, code style fix with laravel/pint and run php artisan test pre commit<br/>
<br/>
this project apis are exported with postman collection.<br/>

this project provides these features:<br/>
* sanctum authentication login/register with tests
* Product store/delete with tests
* Product addMedia is implemented with laravel/spatie with resize feature with tests
* Product search title, filter by maxPrice and sort by lowest price with elastic search and fallback with eloquent query,
* elastic can be disabled by environment variable `ELASTICSEARCH_ENABLED`
* Cart add, remove to cart and submit cart which inform admin with email and return final price to user with tests

## Note
I was considering adding delivery_price to product table, and wanted to make an index with those columns, but it seems not an good option.</br>
so after mentioning this issue with shab team, they said it should be real time, and after response i didnt have enough time to elaborate best solution.</br>
there is huge trade off between performance of filtering products with non indexed data column, so i check elastic search for scripts dynamics,
fulltext indexing, virtual column and view table for mysql, but i didnt conclude a solution in time.</br>
anyway i just add delivery price to orders, and not inculded in search and filters 

