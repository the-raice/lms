<?php

namespace Controllers;

use Raice\Controller as Controller;
    
class Logout
    extends Controller
{
    
    public function index ()
    {

        session_start();
    
       if ( !empty( $_SESSION['authorized'] ) ) {
           
           unset($_SESSION['authorized']);
           session_destroy();
           
           header('Location: /');
           
       } else {
           
           header('Location: /');
           
       }
        
    }
    
}
