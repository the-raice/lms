<?php

namespace Controllers;

use Raice\Controller as Controller;
use Models\Article as Article;
use Models\User as User;
    
class Articles
    extends Controller
{
    
    
    public static $articles;
    public static $user;
    
    public function index ()
    {
        
        session_start();
        
        self::$articles = Article::getAll();
    
        if ( !empty( $_SESSION['authorized'] ) ) {
               
            self::$user = \Models\User::getOneById( $_SESSION['authorized'] )[0];
               
            parent::getView( $this->getName(), 'Статьи' );
               
        } else {
               
            header('Location: /');
               
        }
        
    }
    
}
