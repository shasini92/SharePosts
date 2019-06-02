<?php

class Users extends Controller {
    public function __construct() {
        // Check the model directory for a file called user.php and load it
        $this->userModel = $this->model('User');
    }
    
    // register method handles showing the form and submitting it
    public function register() {
        // Check for POST (form submit)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init Data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];
            
            // Validation
            if (empty($data['email'])) {
                $data['email_err'] = "Please enter email";
            } else {
                // Check email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = "Email is already taken";
                }
            }
            if (empty($data['name'])) {
                $data['name_err'] = "Please enter name";
            }
            if (empty($data['password'])) {
                $data['password_err'] = "Please enter password";
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = "Password must be at least 6 characters";
            }
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = "Please enter confirm password";
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = "Passwords do not match";
                }
            }
            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated
                
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Register User
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registered and can log in');
                    redirect('users/login');
                } else {
                    die("Something went wrong.");
                }
                
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }
            
        } else {
            // Init data (to preserve the form if there's an error)
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];
            
            // Load form
            $this->view('users/register', $data);
        }
    }
    
    public function login() {
        // Check for POST (form submit)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init Data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];
            
            // Validation
            if (empty($data['email'])) {
                $data['email_err'] = "Please enter email";
            }
            if (empty($data['password'])) {
                $data['password_err'] = "Please enter password";
            }
            
            // Check for user/email
            if($this->userModel->findUserByEmail($data['email'])){
                // User Found
            }else{
                // User Not Found
                $data['email_err'] = "No user found";
            }
            
            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if($loggedInUser){
                    // Create Session
                    $this->createUserSession($loggedInUser);
                    
                    
                    
                }else{
                    $data['password_err'] = "Password incorrect";
                    
                    // Reload the view (pass the data into fileds)
                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
            
        } else {
            // Init data (to preserve the form if there's an error)
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'confirm_password_err' => '',
            ];
            
            // Load form
            $this->view('users/login', $data);
        }
    }
    
    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        redirect('posts');
    }
    
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }
}