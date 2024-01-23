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

