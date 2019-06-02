<?php
// In order to use sessions you have to use "session_start()" on every page
// You can use function "session_destroy()" or unset($_SESSION) to terminate a session
session_start();

// Flash message helper
// EXAMPLE: flash('register_success', 'You are now registered', "alert alert-success");
// DISPLAY: <?php echo flash('register_success');
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            // Check if empty and unset if not
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            
            // Setting them again
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
            
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    }else{
        return false;
    }
}