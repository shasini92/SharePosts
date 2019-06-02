<?php
/*The bootstrap files contains all the "require" files from the app, and then the main index.php file "requires" the bootstrap file*/

// Load Config
require_once 'config/config.php';

// Load Helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

/*// Load libraries
require_once 'libraries/Core.php';
require_once 'libraries/Controller.php';
require_once 'libraries/Database.php';*/

// AutoLoad Core Libraries (load all the libraries in the libraries directory at once)
spl_autoload_register(function ($className){
    require_once 'libraries/' . $className . '.php';
})


?>