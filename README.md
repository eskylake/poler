# Poler

This is a simple PHP model-view-controller (MVC) framework with user authentication.

## Installation

Download or clone the project:

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
All of the environment variable options are in `env.php.example` file.
Copy `env.php.example` to `env.php`:
```bash
cp env.php.example env.php
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
```bash
app
├── controllers
│   ├── HomeController.php
│   └── UserController.php
├── core
│   ├── Autoload.php
│   ├── Controller.php
│   ├── DB.php
│   ├── Model.php
│   ├── Path.php
│   └── TokenGenerator.php
├── interfaces
│   └── DatabaseRepository.php
├── models
│   └── User.php
├── repositories
│   └── DatabaseRepository.php
└── views
    ├── home
    │   └── index.php
    ├── layouts
    │   ├── Main.php
    │   └── User.php
    └── user
        ├── login.php
        └── register.php
```

## Models
Create your model classes in `models/` directory. You must inherit from `\App\core\Model` base class in order to validate or sanitize data, connect to database etc.

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
There are some predefined validation rules in the base model class that are listed below. You can add additional types as you want.

| Rules         | Definition                                            | 
|:-------------:|:-----------------------------------------------------:|
| **string**    | Data type is string. Value length <= 255 characters   |
| **integer**   | Data type is integer and unsigned                     |
| **text**      | Data type is string. Value length <= 65535 characters |
| **required**  | Value is not null or empty                            |
| **unique**    | Value is unique in model's table                      |

#### Usage
All you need is to define the `rules()` method in your model class that it has to return an array of rules. Each key of this array specifies the rule name that it contains an array of attributes to be validated.


For example the example class has three attributes, username, password and age:
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
            'integer' => ['age']
        ];
    }
}
```
## Views
Create your own view files under the `views/` directory. It is better to have separated directories for each of your controller classes, but there is no compulsion for this. All you have to do is that to create a `.php` file, e.g. `example.php`, with specific namespace and all the rest of your front codes.

For example we need a view file for our example class, we create the `example.php` file under the `example` directory:
```bash
app/views
└── example
    └── example.php
```
It looks like:
```php
<?php
namespace App\views\example;
?>

All of the front codes must be written here :)
```
#### Layouts
Based on [Yii 2.0 definition](https://www.yiiframework.com/doc/guide/2.0/en/structure-views#layouts "Yii 2.0 Layouts"), layouts are a special type of views that represent the common parts of multiple views. For example, the pages for most Web applications share the same page header and footer. While you can repeat the same page header and footer in every view, a better way is to do this once in a layout and embed the rendering result of a content view at an appropriate place in the layout.

Creating layouts is similar to views, but they have to be created under `views/layouts/` directory.

For example:
```bash
app/views
└── layouts
    └── Example.php
```
It looks like:
```php
<?php
namespace App\views\layouts;
?>

All of the front codes must be written here :)
```
## Controllers
Create your controller classes in `controllers/` directory. You must inherit from `\App\core\Controller` base class that it contains some useful methods such as `redirect()` and `render()`.

There are two steps needed to create a controller:
1. Choose a name for your class :) e.g. `Example`
2. Concatenate it with `Controller` keyword


Now we have `ExampleController` class. It looks like:
```php
<?php
namespace App\controllers;

use App\core\Controller;

class ExampleController extends Controller
{
    protected $layout = 'Example';
}
```
As you can see you can specify which layout must be loaded for all methods inside the controller by defining `$layout` property.

There are two useful methods that you can call in your controllers, `redirect()` and `render()`:

#### redirect()
This method redirects to a specific URL route. All you have to do is that to pass the route as the first argument of this method.

For example in our example class we have an index method that redirects to `/home/index`:
```php
public function index()
{
    $this->redirect('/home/index');
}
```
#### render()
This method renders a specific view. It accepts two parameters:
1. View file name
2. Array of data you need in the view

For example in our example class we have a hello method that renders `example/hello.php` view file whit 'Hello World' message:
```php
public function hello()
{
    $this->render('example/hello', [
        'message' => 'Hello World'
    ]);
}
```
It will fill the `$data` variable with the array you have passed to the view. Now you can access to the `message` just like this:
```php
<?= $data['message'] ?>
```
## Queries
There are some simple query methods that you have access into your models if your model extends `\App\core\Model` base class.

#### create()
This method creates new record for specific data passed as the first argument to this method. For example:
```php
(new \App\models\Example)->create([
    'username' => 'AliTavafi',
    'password' => 'thisIsmyp@ssword',
    'age' => 21
]);
```
#### select()
This method select all or specific columns from the table. You can pass the array of columns you want as the first argument.

#### one()
This method returns only one record.

#### all()
This method returns all records.

For example:
```php
(new \App\models\Example)->select()->one();
```
Or
```php
(new \App\models\Example)->select()->all();
```
#### where()
This method apply conditions to the query. It has three main parameters:
```php
->where(column, operator, value);
```
For example:
```php
(new \App\models\Example)->where('username', '=', 'AliTavafi')
    ->where('age', '=', 21)
    ->select()
    ->one();
```
It will return the first record of the example table with 'AliTavafi' username.

#### update()
This method updates a record with specific data. It accepts an array of `column => value` that updates table's column with the value. For example:
```php
(new \App\models\Example)->where('username', '=', 'AliTavafi')
    ->update([
        'username' => 'Tavafi',
        'password' => 'thisIsmynewp@ssword'
    ]);
```
#### orderBy()
This methods sorts query result based on a column and an order type.

Order types:
1. ASC (default type that means ascending)
2. DESC (descending)

It has two main parameters:
```php
->orderBy(column, 'ASC' or 'DESC');
```
For example:
```php
(new \App\models\Example)->where('age', '>=', 20)
    ->orderBy('id', 'DESC')
    ->select()
    ->all();
```

#### getByColumn()
This method returns only one record with specific condition. It has three main parameters:
```php
->getByColumn(column, value, order type); // Default order type is 'ASC'
```

For example:
```php
(new \App\models\Example)->getByColumn('age', 21, 'DESC');
```
It will sort the records descending and return the first record with age 21.

#### getAllByColumn()
This method returns all records with specific condition. It has three main parameters:
```php
->getAllByColumn(column, value, order type); // Default order type is 'ASC'
```

For example:
```php
(new \App\models\Example)->getAllByColumn('age', 21, 'DESC');
```
It will sort the records descending and return all records with age 21.

## Feedback
All bugs, feature requests, pull requests, feedback, etc., are welcome. [Create an issue](https://github.com/Tavafi/poler/issues).

## License
[MIT](https://github.com/Tavafi/poler/blob/master/LICENSE)