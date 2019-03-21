<?php

namespace Controllers;

use Raice\Controller as Controller;
use Models\User as User;
    
class Notification
    extends Controller
{
    
    
    public static $page;
    public static $user;
    public static $notification;
    public static $id;
    
    public function index ()
    {

        
        
    }
    
    public function delete()
    {

        session_start();
        
        $action = explode('/', $_SERVER['REQUEST_URI'])[2];
        self::$id = explode('/', $_SERVER['REQUEST_URI'])[3];
        self::$notification = \Models\Notifications::getOneById( self::$id )[0];
      
        self::$user = \Models\User::getOneById( $_SESSION['authorized'] )[0];

        if ( !empty( self::$user ) && !empty( self::$notification ) && ( ( self::$user['role'] == 'admin' ) || ( self::$notification['author_id'] == self::$user['id'] ) ) ) {
        
            if ( $action == 'delete' ) {
                
                \Models\Notifications::delete( self::$id, 'id');
                header("Location: /dashboard");
                
            }
        
        } else {
            
            header("Location: /dashboard");
            
        }
        
        
        
    }
    
}
