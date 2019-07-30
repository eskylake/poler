<?php
namespace App\core;

/**
 * Autoload is the base class that is called when a request is triggered.
 * It has base methods to get and separate url.
 * Find and call the action that is requested.
 * 
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class Autoload
{
    /**
     * The controller keyword.
     */
    const CONTROLLER = "Controller";

    /**
     * @var string The default controller namespace.
     */
    protected $namespace = Path::CONTROLLER_GLOBAL_NAMESPACE;

    /** 
     * @var string the default controller class.
     */
    protected $controller = "User" . self::CONTROLLER;

    /** 
     * @var string The default action name.
     */
    protected $method = "index";

    /** 
     * @var array The default method parameters.
     */
    protected $params = [];

    /**
     * Load and require called class.
     * Check if the called class file exists in controllers directory.
     * If the file is founded, it will be loaded.
     * If the file is not founded, the default controller will be loaded.
     * 
     * @param string $className the classs name to be loaded.
     */
    public static function loader(string $className)
    {
        if (file_exists(Path::CONTROLLER_PATH . $className . self::CONTROLLER . ".php")) {
            require_once Path::CONTROLLER_PATH . $className . self::CONTROLLER . ".php";
        } else {
            require_once Path::CONTROLLER_PATH . (new self)->controller . ".php";
        }
    }
    
    /**
     * Get `url` parameter (specified in .htaccess) from url.
     * Parse it into controller/action/params.
     * Sanitize it by php default url filter.
     * Explode and separate it by slash symbol to be used in run() method.
     * 
     * @return array|null separated url.
     */
    public static function parseUrl(): ?array
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $url = parse_url("{$protocol}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", PHP_URL_PATH);
 
        if ($url) {
            $url = explode('/', filter_var(ltrim(rtrim($url, '/'), '/'), FILTER_SANITIZE_URL));
        }

        return $url;
    }

    /**
     * Parse the url and call the requested route.
     * 
     * Check if the controller file exists or not. If the file is founded, its name will be stored in controller property.
     * Check if the method exists in the controller class or not. If the method is defined, its name will be stored in method property.
     * Get all the parameter values from url and pass them to the method.
     * Call the requested method with requested parameters.
     */
    public function run()
    {
        $url = self::parseUrl(); // Get sanitized url array

        if (file_exists(Path::CONTROLLER_PATH . ucfirst($url[0]) . self::CONTROLLER . ".php")) {
            $this->controller = ucfirst($url[0]) . self::CONTROLLER; // Capitalize firts letter of controller file
            unset($url[0]); // Remove controller name from url
        }
        
        // Create a new instance of founded controller.
        $className = $this->namespace . $this->controller;
        $this->controller = new $className;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]); // Remove method name from url
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }
}