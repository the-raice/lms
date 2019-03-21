<?php

namespace Controllers;

use Raice\Controller as Controller;
use Models\Course as Course;
use Models\User as User;
    
class Courses
    extends Controller
{
    
    
    public static $courses;
    public static $user;
    
    public function index ()
    {
        
        session_start();
        
        self::$courses = Course::getAll();
    
        if ( !empty( $_SESSION['authorized'] ) ) {
               
            self::$user = \Models\User::getOneById( $_SESSION['authorized'] )[0];
               
            parent::getView( $this->getName(), 'Курсы' );
               
        } else {
               
            header('Location: /');
               
        }
        
    }
    
}
