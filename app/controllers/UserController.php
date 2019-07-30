<?php
namespace App\controllers;

use App\core\Controller;
use App\models\User;

/**
 * User controller that handles all user operations.
 * It is also default controller that is called.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class UserController extends Controller
{
    /**
     * @var string Controller layout for all actions.
     */
    protected $layout = 'User';
    
    /**
     * Redirect user to login action.
     * 
     * @return void
     */
    public function index()
    {
        $this->redirect('/user/login');
    }

    /**
     * Redirect user to login page if user is not logged in.
     * Validate user posted data.
     * Login user if everything is valid.
     * 
     * @return void
     */
    public function login()
    {
        try {
            $this->emptyAlert(); // Remove all old alerts

            /**
             * Check user logged in session.
             * Redirect user to messages page if user is logged in.
             */
            if (User::isLoggedIn()) {
                $this->redirect('/home/index');
            }

            if ($_POST != null) {
                if ($this->validateCsrfToken()) {
                    $user = new User;
                    $userRecord = $user->where('username', '=', $_POST['username'])
                        ->select()
                        ->one();
                        
                    if ($user->validatePasswordHash($_POST['password'], $userRecord['password'])) {
                        if ($user->login($userRecord)) {
                            $this->redirect('/home/index');
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->alert = [
                'type' => 'error',
                'text' => $th->getMessage()
            ];
        } finally {
            $this->render('user/login', [
                'csrfToken' => $this->setCsrfToken(),
                'alert' => $this->alert
            ]);
        }
    }

    /**
     * Redirect user to register page if user is not logged in.
     * Validate user posted data.
     * Create new user with posted data if everything is valid.
     * 
     * @return void
     */
    public function register()
    {
        try {
            $this->emptyAlert(); // Remove all old alerts

            /**
             * Check user logged in session.
             * Redirect user to messages page if user is logged in.
             */
            if (User::isLoggedIn()) {
                $this->redirect('/home/index');
            }

            if ($_POST != null) {
                if ($this->validateCsrfToken()) {
                    $user = new User;
                    if ($user->validate($_POST)) {
                        if ($user->validatePassword($_POST['password'], $_POST['confirm_password'])) {
                            $user = $user->create([
                                'username' => $_POST['username'],
                                'password' => $user->generatePasswordHash($_POST['password']),
                                'email' => $_POST['email'],
                                'name' => $_POST['name'],
                                'family' => $_POST['family']
                            ]);

                            $this->alert = [
                                'type' => 'success',
                                'text' => 'User created successfully'
                            ];
                            
                            $this->redirect('/user/login');
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->alert = [
                'type' => 'error',
                'text' => $th->getMessage()
            ];
        } finally {
            $this->render('user/register', [
                'csrfToken' => $this->setCsrfToken(),
                'alert' => $this->alert
            ]);
        }
    }

    /**
     * Destroy session data to logout user.
     * 
     * @return void
     */
    public function logout()
    {
        $_SESSION = array();
        
        session_destroy();

        $this->redirect('/user/login');
    }
}