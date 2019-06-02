<?php
/*
App Core Class
Creates URL and loads core controller
URL FORMAT - /controller/method/params
 */

class Core {
    // The controller and the method will change as the URL changes
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];
    
    public function __construct() {
        $url = $this->getUrl();
        
        // Look in controllers for first value of the url array
        if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            // If exists, set as controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 index
            unset($url[0]);
        }
        
        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';
        
        // Instantiate controller class
        $this->currentController = new $this->currentController;
        
        // Check for second part of URL
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }
        
        // Get remaining params
        $this->params = $url ? array_values($url) : [];
        
        // Call a callback with array of params (takes the current controller and current method and passes params)
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }
    
    public function getUrl() {
        if (isset($_GET['url'])) {
            // Strip the URL of any unnecessary / at the end of the string
            $url = rtrim($_GET['url'], '/');
            
            // Prevent any characters that a URL shouldn't have
            $url = filter_var($url, FILTER_SANITIZE_URL);
            
            // Explode function creates an array from a string using a "delimiter" such as "/"
            $url = explode('/', $url);
            return $url;
        }
    }
}

?>