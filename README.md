# Poler

This is a simple PHP model-view-controller (MVC) framework with user authentication.

## Installation

Download the project or clone it:

```git
git clone git@github.com:Tavafi/poler.git
```

Instructs composer to create autoloader:

```composer
composer dump-autoload
```
Import `User.sql` into your database:
```mysql
mysql -u root -p -h localhost YourDataBase < User.sql
```
All of the environment variable options are in `env.example.php` file.
Copy `env.example.php` to `env.php`:
```bash
cp env.example.php env.php
```
You can add additional options to `$variables` array as you want. Edit `DB_` values in order to connecting to database:

```bash
'DB_HOST' => 'localhost',
'DB_USERNAME' => 'DBUser',
'DB_PASSWORD' => 'DBPass',
'DB_NAME' => 'DBName',
'DB_PORT' => '3306',
```
Default database host address is `localhost`. Modify it to another host if you want.

Now you can access variables globally in your code using `env()` function. For example:
```bash
$dbHost = env('DB_HOST');
```
## Usage
### Initialization

Configure your web server's document / web root to be the  `public` directory. The `index.php` in this directory serves as the front controller for all HTTP requests entering your application.
The main directory of the application is `app` with predefined structure:

### Models
Define your model classes in this directory that must extends `\App\core\Model` base class in order to validate or sanitize data, connect to database etc.  


## License
[MIT](https://choosealicense.com/licenses/mit/)