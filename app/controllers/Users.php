<?php
 class Users extends Controller {
     public function __construct()
     {
        $this->userModel = $this->model('User');
     }


     public function register()
     {
         // Check for POST
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            //Sanitize Post data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


            //Process form
            $data=[
                'name' =>trim($_POST['name']),
                'email' =>trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password'=>trim($_POST['confirm_password']),
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_err'=>'' 
            ];
            // Validate Email
            if(empty($data['email']))
            {
                $data['email_err'] = 'Please enter email';
            }
            else
            {
                //Check email
                if($this->userModel->findUserByEmail($data['email']))
                {
                    $data['email_err']='Email already exists';
                }
            }

            // Validate Name
            if(empty($data['name']))
            {
                $data['name_err'] = 'Please enter name';
            }
            // Validate Password
            if(empty($data['password']))
            {
                $data['password_err'] = 'Please enter password';
            }
            else if(strlen($data['password']) < 6)
            {
                $data['password_err'] = 'Password must be at least 6 characters';
            }
            
            // Validate confirm Password
            if(empty($data['confirm_password']))
            {
                $data['confirm_password_err'] = 'Please confirm password';
            }
            else if($data['password']!= $data['confirm_password'])
            {
                $data['confirm_password_err']='Passwords do not match';
            }

            //Make sure errors are empty

            if(empty($data['email_err']) &&empty($data['name_err'])&& empty($data['password_err']) && empty($data['confirm_password_err']))
            {
                //Validated
                
                //Hash Password
                $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);

                // Register User
                if($this->userModel->register($data))
                {
                    flash('register_success','You are registerd and you can log in');
                    redirect('users/login');
                }else
                {
                    die('Something went wrong');
                }

            }
            else
            {
                //Load view with errors
                $this->view('users/register',$data);
            }

        }
        else
        {
            //init data
            $data=[
                'name' =>'',
                'email' =>'',
                'password' => '',
                'confirm_password'=>'',
                'name_err'=>'',
                'email_err'=>'',
                'password_err'=>'',
                'confirm_password_err'=>'' 
            ];
            //Load view
            $this->view('users/register',$data);
        }
        
        
     }


     public function login()
     {
         // Check for POST
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            //process form
             //Sanitize Post data
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

             $data=[
                'email' =>trim($_POST['email']),
                'password' => trim($_POST['password']),
                'check'=>(isset($_POST['check']))?$_POST['check']:'',
                'email_err'=>'',
                'password_err'=>'',
            ];
            // Validate Email
            if(empty($data['email']))
            {
                $data['email_err'] = 'Please enter email';
            }
            

           
            // Validate Password
            if(empty($data['password']))
            {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if($this->userModel->findUserByEmail($data['email']))
            {
                //Userfound
            }
            else
            {
                //user not found
                $data['email_err']="No user found";
            }
            
            

            //Make sure errors are empty

            if(empty($data['email_err']) && empty($data['password_err']))
            {
                //Validated
                //Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'],$data['password']);
                if($loggedInUser){
                            $this->createUserSession($loggedInUser,$data['check']);
                }
                        
                else
                {
                    
                    $data['password_err']='Password incorrect';
                    $this->view('users/login',$data);
                }
            }
            else
            {
                //Load view with errors
                $this->view('users/login',$data);
            }

        }
        else
        {
            //init data
            $data=[
                'email' =>'',
                'password' => '',
                'check'=>'off',
                'email_err'=>'',
                'password_err'=>'',
            ];
            //Load view
            $this->view('users/login',$data);
        }
        
        
     }
     public function createUserSession($user,$check){

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email']=$user->email;
        $_SESSION['user_name']=$user->name;
        // if remember checkbox was checked set cookie for one month
        if($check=='on')
        {
            setcookie('login',$_SESSION['user_name'],time()+30*24*60*60,'/');
            setcookie('user_id',$_SESSION['user_id'],time()+30*24*60*60,'/');
        }
        redirect('posts');

    }

     public function logout()
     {
         // unset cookie when check remember
        if (isset($_COOKIE['login'])) 
        {
            unset($_COOKIE['login']); 
            unset($_COOKIE['user_id']);
            setcookie('login', null, -1, '/'); 
            setcookie('user_id', null, -1, '/'); 
        }
       
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
     }
    

 }