<?php
namespace App\controllers;

use App\core\Controller;

/**
 * Home controller.
 * User is redirected to this controller after login.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class HomeController extends Controller
{
    /**
     * @var string Controller layout for all actions.
     */
    protected $layout = 'Main';
    
    /**
     * Index action.
     * 
     * @return void
     */
    public function index()
    {
        $message = "Hi :)";

        $this->render('home/index', [
            'message' => $message
        ]);
    }
}