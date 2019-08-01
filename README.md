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

```php
'DB_HOST' => 'localhost',
'DB_USERNAME' => 'DBUser',
'DB_PASSWORD' => 'DBPass',
'DB_NAME' => 'DBName',
'DB_PORT' => '3306',
```
Default database host address is `localhost`. Modify it to another host if you want.

Now you can access variables globally in your code using `env()` function. For example:
```php
$dbHost = env('DB_HOST');
```
## Usage
### Initialization

Configure your web server's document / web root to be the  `public` directory. The `index.php` in this directory serves as the front controller for all HTTP requests entering your application.
The main directory of the application is `app` with predefined structure.

## Models
Define your model classes in this directory. You must inherit from `\App\core\Model` base class in order to validate or sanitize data, connect to database etc.

For example:
```php
<?php
namespace App\models;

use App\core\Model;

class Example extends Model
{

}
```
If your model connects to specific table, modify `$table` property of the class to its name.
```php
protected $table = "example";
```

### Validation rules
There are some predefined type validation rules in the base model class that are listed below. You can add additional types as you want.

| Rules         | Definition                                            | 
|:-------------:|:-----------------------------------------------------:|
| **string**    | Data type is string. Value length <= 255 characters   |
| **integer**   | Data type is integer and unsigned                     |
| **text**      | Data type is string. Value length <= 65535 characters |
| **required**  | Value is not null or empty                            |
| **unique**    | Value is unique in model's table                      |

#### Usage
All you need is to define the `rules()` method in your model class that it has to return an array of rules. Each key of this array specifies the rule name that it contains an array of attributes to be validated.


For example the example class has two attributes, username and password that are `string` and `required`:
```php
class Example extends Model
{
    protected $table = "example";

    public function rules(): array
    {
        return [
            'string' => [
                'username',
                'password'
            ],
            'required' => [
                'username',
                'password'
            ],
        ];
    }
}
```



Readme is not complete yet...
## License
[MIT](https://choosealicense.com/licenses/mit/)