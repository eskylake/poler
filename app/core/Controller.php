<?php
namespace App\core;

use Exception;

/**
 * The base Controller class that every other controller classes must inherits it.
 * It handles all common operations in all controllers.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class Controller
{
    /**
     * @var string Layout file name.
     */
    protected $layout;

    /**
     * @var array The status messages to show in views.
     */
    protected $alert = [
        'type' => null,
        'text' => null
    ];

    /**
     * Path of the classes that don't need login.
     */
    const CLASS_WITHOUT_LOGIN = [
        Path::CONTROLLER_NAMESPACE . 'UserController'
    ];

    /**
     * If user is not logged in or is trying to call an action from a class that needs logged in session data, this method will redirect it back to the base url.
     * If there is no session id, this method will start the session.
     */
    public function __construct()
    {
        if (! session_id()) {
            session_start();
        }

        if (!isset($_SESSION['loggedin']) && !isset($_SESSION['user_id'])  && !in_array(static::class, self::CLASS_WITHOUT_LOGIN)) {
            $this->redirect('/');
        }
    }

    /**
     * Redirect user to the given url.
     * 
     * @param string $url redirect url route.
     * 
     * @return void
     */
    public function redirect(string $url)
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * Render the layout file defined in the controller.
     * Render the view page located in the given path with given data inside the layout.
     * 
     * @param string $path view path.
     * @param array|null $data data to be shown in the view.
     * 
     * @return void
     */
    public function render(string $path, ?array $data = [])
    {
        require_once Path::LAYOUT_PATH . $this->layout . '.php';
    }

    /**
     * Set csrfToken if it's not stored in session.
     * 
     * @return string the generated csrfToken which is stored in session.
     */
    protected function setCsrfToken(): string
    {
        if (!isset($_SESSION['csrfToken'])) {
            if (empty($_SESSION['csrfToken'])) {
                $_SESSION['csrfToken'] =  TokenGenerator::csrfToken();
            }
        }

        return $_SESSION['csrfToken'];
    }

    /**
     * Validate and compare posted csrfToken with the token in session.
     * 
     * @return bool the posted token matches the stored token, or false if all the conditions fail.
     * 
     * @throws Exception if posted token and stored token don't match.
     */
    protected function validateCsrfToken(): bool
    {
        if (isset($_POST['csrfToken']) && isset($_SESSION['csrfToken'])) {
            if (hash_equals($_POST['csrfToken'], $_SESSION['csrfToken'])) {
                // Remove token from session and $_POST global variable
                unset($_SESSION['csrfToken']);
                unset($_POST['csrfToken']);

                return true;
            } else {
                throw new Exception("Form is not valid!");
            }
        } else {
            throw new Exception("Form is not valid!");
        }

        return false;
    }

    /**
     * Empty all alerts.
     * 
     * @return void
     */
    protected function emptyAlert()
    {
        $this->alert = [
            'type' => null,
            'text' => null
        ];
    }
}