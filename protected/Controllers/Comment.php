<?php

namespace Controllers;

use Raice\Controller as Controller;
use Models\User as User;
    
class Comment
    extends Controller
{
    
    
    public static $page;
    public static $user;
    public static $comment;
    public static $id;
    
    public function index ()
    {
        
        session_start();
        
        $action = explode('/', $_SERVER['REQUEST_URI'])[3];
        self::$id = explode('/', $_SERVER['REQUEST_URI'])[2];
        self::$comment = \Models\Comments::getOneById( self::$id )[0];
      
        self::$user = \Models\User::getOneById( $_SESSION['authorized'] )[0];
        
        if ( !empty( self::$user ) && !empty( self::$comment ) ) {
        
            if ( $action == 'edit' || $action == 'delete' ) {
                
                $this->$action();
                
            }
        
        } else {
            
            header("Location: /");
            
        }
        
    }
    
    public function delete()
    {
        
        if ( !empty( $_POST ) ) {

                \Models\Comments::delete( self::$id, 'id');

                
        } else {
            
            parent::getView('CommentDelete', 'Удаление комментария');
            
        }
        
    }
    
    public function edit()
    {

        if ( !empty( $_POST ) ) {

            $comment = $_POST['comment'];
            $date = date("Y-m-d");
            var_dump(\Models\Comments::update("content='" . $comment . "',date='" . $date . "'", self::$id, 'id'));

                
        } else {
            
            parent::getView('CommentEdit', 'Редактирование комментария');
            
        }
        
    }
    
}
