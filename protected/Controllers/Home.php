<?php

namespace Controllers;

use Raice\Controller as Controller;
    
class Home
    extends Controller
{
    
    public static $user;
    public static $articles;
    public static $pages;
    
    public function index ()
    {

        session_start();
        
        $id = $_SESSION['authorized'];
        
        self::$user = \Models\User::getOneById( $id )[0];
        
        self::$pages = \Models\Page::getAll();
        self::$articles = \Models\Article::getAll();
        
        parent::getView( $this->getName(), 'The Raice CMS' );
        
    }
    
}
