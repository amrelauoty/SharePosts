<?php
    class Post {
        private $db;
        public function __construct()
        {
            $this->db = new Database;
        }
        public function getPosts(){

            $this->db->query('SELECT *,
                            posts.created_at as post_created,
                            users.created_at as user_created,
                            users.id as userId,
                            posts.id as postId
                            FROM users JOIN posts ON users.id = posts.user_id
                            ORDER BY posts.created_at DESC'
                            );
            $result = $this->db->resultSet();
            return $result;
        }

        public function addPost($data)
        {

            $this->db->query('INSERT INTO posts (title,user_id , body) VALUES (:title, :user_id,  :body)');
            $this->db->bind(':title',$data['title']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':body',$data['body']);
            if($this->db->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        public function getPostById($id)
        {
            $this->db->query('SELECT * FROM posts WHERE id = :id');
            $this->db->bind(':id',$id);
            $result = $this->db->single();
            return $result;
        }


        public function updatePost($data)
        {

            $this->db->query('UPDATE posts SET title =:title , body = :body where id = :id');
            $this->db->bind(':title',$data['title']);
            $this->db->bind(':body',$data['body']);
            $this->db->bind(':id', $data['id']);
            if($this->db->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        public function deletePost($id)
        {

            $this->db->query('DELETE FROM posts WHERE id = :id ');
            $this->db->bind(':id', $id);
            if($this->db->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }

    }