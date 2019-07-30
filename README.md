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
Copy `env.example.php` to `env.php`:
```bash
cp env.example.php env.php
```
Open `env.php` and edit `DB` variables.
## Usage

Configure your web server's document / web root to be the  `public` directory. The `index.php` in this directory serves as the front controller for all HTTP requests entering your application.


## License
[MIT](https://choosealicense.com/licenses/mit/)