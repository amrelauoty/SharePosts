<?php
    Class Posts extends Controller{

        public function __construct()
        {
            if(!isLoggedIn())
            {
                redirect('users/login');
            }
            if(!isset($_SESSION['user_id']))
            {
                isfoundcookie();
            }
            $this->postModel = $this->model('Post');
            $this->userModel = $this->model('User');
        }

        public function index()
        {
            $posts = $this->postModel->getPosts();
            $data=[
                'posts'=>$posts
            ];
            $this->view('posts/index',$data);
        }

        public function add(){
            if($_SERVER['REQUEST_METHOD']=='POST')
            {
                //sanitize
                $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
                $data = [
                    'title'=>trim($_POST['title']),
                    'body'=>trim($_POST['body']),
                    'user_id'=>$_SESSION['user_id'],
                    'title_err'=>'',
                    'body_err'=>''
                ];
                if(empty($data['title'])){
                    $data['title_err'] = "Please enter title";
                }
                if(empty($data['body'])){
                    $data['body_err'] = "Please enter body text";
                }
                if(empty($data['title_err']) && empty($data['body_err'])){
                    

                    if($this->postModel->addPost($data))
                    {
                        
                        flash('added_success','Post Added');
                        redirect('posts');
                    }else
                    {
                        die('Something went wrong');
                    }
                }
                else
                {
                    //reload view with errors
                    $this->view('posts/add',$data);
                }
            }
            else
            {
                $data=[
                    'title'=>'',
                    'body'=>''
                ];
                $this->view('posts/add',$data);
            }
           
            
        }



        public function show($id)
        {
           
           $post = $this->postModel->getPostById($id);
           $user = $this->userModel->getUserById($post->user_id);
            
           $data=[
            'post'=>$post,
            'user'=>$user
            ];
            $this->view('posts/show',$data);


        }


        public function edit($id){
            if($_SERVER['REQUEST_METHOD']=='POST')
            {
                //sanitize
                $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);
                $data = [
                    'title'=>trim($_POST['title']),
                    'body'=>trim($_POST['body']),
                    'user_id'=>$_SESSION['user_id'],
                    'id'=>$id,
                    'title_err'=>'',
                    'body_err'=>''
                ];
                if(empty($data['title'])){
                    $data['title_err'] = "Please enter title";
                }
                if(empty($data['body'])){
                    $data['body_err'] = "Please enter body text";
                }
                if(empty($data['title_err']) && empty($data['body_err'])){
                    

                    if($this->postModel->updatePost($data))
                    {
                        
                        flash('added_success','Post Updated');
                        redirect('posts');
                    }else
                    {
                        die('Something went wrong');
                    }
                }
                else
                {
                    //reload view with errors
                    $this->view('posts/edit',$data);
                }
            }
            else
            {
                $post  = $this->postModel->getPostById($id);

                //Check for owner
                if($post->user_id != $_SESSION['user_id'])
                {
                    redirect('posts');
                }
                $data=[
                    'id'=>$id,
                    'title'=>$post->title,
                    'body'=>$post->body
                ];
                $this->view('posts/edit',$data);
            }
           
            
        }

        public function delete($id){
            if($_SERVER['REQUEST_METHOD'] =='POST')
            {
                $post  = $this->postModel->getPostById($id);

                //Check for owner
                if($post->user_id != $_SESSION['user_id'])
                {
                    redirect('posts');
                }
                if($this->postModel->deletePost($id))
                {
                        
                    flash('added_success','Post Deleted');
                    redirect('posts');
                }else
                {
                        die('Something went wrong');
                }
            }
            else
            {
                redirect('posts');
            }
            
        }
    }