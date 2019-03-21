<?php

namespace Controllers;

use Raice\Controller as Controller;
use Models\User as User;
    
class Lesson
    extends Controller
{
    
    
    public static $user;
    public static $comments;
    public static $lesson;
    public static $step;
    
    public function __construct ()
    {
        
        session_start();
        
        $lesson_url =  explode('/', stristr( $_SERVER['REQUEST_URI'], '?', true))[4];
        $course_url = explode('/', $_SERVER['REQUEST_URI'])[2];
        
        self::$lesson = \Models\Lessons::getOneByField( $lesson_url, 'url' )[0];     
        
        self::$user = \Models\User::getOneById( $_SESSION['authorized'] )[0];
        
        $user_id = $_SESSION['authorized'];
        $lesson_id = self::$lesson['id'];
        
        if ( $_GET['action'] == 'complete' ) {
            
            if ( empty( \Models\Lessons::getOneByTwoFields( self::$lesson['lesson_order'] + 1, 'lesson_order', self::$lesson['course_id'], 'course_id' ) ) ) {
                
                \Models\Notifications::insert("'', 'прошёл курс', '/course/$course_url', '$date', '$user_id'");
                header("Location: ../../");
                
            } else {
                
                \Models\Notifications::insert("'', 'прошёл урок', '/course/$course_url/lesson/$lesson_url', '$date', '$user_id'");
                \Models\Actions::insert("'', '$lesson_id', '$user_id', '3'");
                header("Location: ../");
            
            }
            
        }
        
        if ( empty( $_GET['step'] ) ) {
        
            $_GET['step'] = 1;
        
        }
        
        
        self::$step = \Models\Steps::getOneByField( self::$lesson['id'], 'lesson_id' )[$_GET['step'] - 1];
        self::$step['content'] = explode("\n", self::$step['content']);
        
        parent::getView( 'lesson', 'Урок ' . self::$lesson['lesson_order'] . '. ' . self::$lesson['title'] );
        
    }
    
}
