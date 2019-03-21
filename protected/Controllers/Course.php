<?php

namespace Controllers;

use Raice\Controller as Controller;
use Models\User as User;
    
class Course
    extends Controller
{
    
    
    public static $course;
    public static $user;
    public static $comments;
    public static $lessons;
    public static $step;
    
    public function index ()
    {
        
        session_start();
        
        $url = explode('/', $_SERVER['REQUEST_URI'])[2];
        
        self::$course = \Models\Course::getOneByField( $url, 'url' )[0];
        self::$course['content'] = explode("\n", self::$course['content']);
        self::$course['preview'] = explode("\n", self::$course['preview']);
        
        self::$user = \Models\User::getOneById( $_SESSION['authorized'] )[0];

        if ( explode('/', $_SERVER['REQUEST_URI'])[3] == 'edit' && \Models\User::isEditor( $_SESSION['authorized'], self::$course['author_id'] ) ) {
            
            $find = array(
            '[b]$1[/b]',
            '[u]$1[/u]',
            '[i]$1[/i]',
            '[s]$1[/s]',
            '[quote]$1[/quote]',
            '[size=$1]$1[/size]',
            '[color=$1]$1[/color]',
            '[url=$1]$2[/url]',
            '[url]$1[/url]',
            '[img]$1[/img]'
            );
            
            $replace = array(
                '~\<b\>(.*?)\</b\>~s',
                '~\<span style="text-decoration:underline;"\>(.*?)\</span\>~s',
                '~\<i\>(.*?)\</i\>~s',
                '~\<del\>(.*?)\</del\>~s',
                '~\<pre\>(.*?)\</'.'pre\>~s',
                '~\<span style="font-size:(.*?)px;"\>(.*?)\</span\>~s',
                '~\<span style="color:(.*?);"\>(.*?)\</span\>~s',
                '~\<a href="(.*?)"\>(.*?)\</a\>~s',
                '~\<a href="(.*?)"\>(.*?)\</a\>~s',
                '~\<img src="(.*?)" alt="" /\>~s'
            );
            
            self::$course['content'] = preg_replace( $replace, $find, self::$course['content'] );
            
            if ( !empty( $_POST ) ) {
                
                $title = strip_tags( $_POST['title'] );
                $content = trim( strip_tags( str_replace( '</h3>', "\n", $_POST['content'] ) ) );

                $find = array(
                    '~\[b\](.*?)\[/b\]~s',
                    '~\[u\](.*?)\[/u\]~s',
                    '~\[i\](.*?)\[/i\]~s',
                    '~\[s\](.*?)\[/s\]~s',
                    '~\[quote\](.*?)\[/quote\]~s',
                    '~\[size=(.*?)\](.*?)\[/size\]~s',
                    '~\[color=(.*?)\](.*?)\[/color\]~s',
                    '~\[url=(.*?)\](.*?)\[/url\]~s',
                    '~\[url\](.*?)\[/url\]~s',
                    '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
                );
                
                $replace = array(
                    '<b>$1</b>',
                    '<span style="text-decoration:underline;">$1</span>',
                    '<i>$1</i>',
                    '<del>$1</del>',
                    '<pre>$1</'.'pre>',
                    '<span style="font-size:$1px;">$2</span>',
                    '<span style="color:$1;">$2</span>',
                    '<a href="$1">$2</a>',
                    '<a href="$1">$1</a>',
                    '<img src="$1" alt="" />'
                );
                
                $content = preg_replace( $find, $replace, $content );
                
                $date = date("Y-m-d");
                $id = $_SESSION['authorized'];
     
                \Models\Course::update("title='" . $title . "',content='" . $content . "', date='" .$date . "'", $url, 'url');
                \Models\Notifications::insert("'', 'изменил статью', '/course/$url', '$date', '$id'");
                
                $location = '/course/' . $url;
                echo $location;               
                
            } else {
                
                parent::getView( 'courseEdit', 'Редактирование курса' );
                
            }
            
        } elseif ( explode('/', $_SERVER['REQUEST_URI'])[3] == 'delete' && \Models\User::isEditor( $_SESSION['authorized'], self::$course['author_id'] ) ) {
         
             if ( !empty( $_POST ) ) {
                    
                    $agreement = $_POST['agreement'];
                    
                    \Models\Course::delete($url, 'url');
                    \Models\Comments::delete(self::$course['id'], 'course_id');
                    \Models\Notifications::delete("/course/$url", 'action_url');    
                    
                } else {
                    
                    parent::getView('courseDelete', 'Удаление курса');
                    
                }
            
        } else {
            
                if ( $_GET['action'] == 'buy' && empty( $_SESSION['authorized'] ) ) {
                    
                    $_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
                    header("Location: /signin");
                    
                        
                } elseif ( $_GET['action'] == 'buy' && !empty( $_SESSION['authorized'] ) ) {
                    
                    
                    $url = stristr( $url, '?', true );
                    $course_id = \Models\Course::getOneByField( $url, 'url' )[0]['id'];
                    $user_id = self::$user['id'];
                    
                    \Models\Purchases::insert("'', '$user_id', '$course_id', 'trial'");
                    
                    header("Location: " . stristr( $_SERVER['REQUEST_URI'], '?', true ));
                    
                }
                
            
            if ( self::$course['access'] == self::$user['role'] || 'admin' == self::$user['role'] ) {
                
                if ( self::$course['price'] == 0 || !empty( \Models\Purchases::getOneByTwoFields( self::$course['id'], 'course_id', self::$user['id'], 'user_id' ) ) || self::$course['author_id'] == self::$user['id'] ) {
                   
                    if ( !empty( explode('/', $_SERVER['REQUEST_URI'])[3] ) ) {

                        //\Controllers\Lesson::index();
                        $lesson = new \Controllers\Lesson();
                        
                    } else {
                   
                        self::$lessons = \Models\Lessons::getOneByField( self::$course['id'], 'course_id' );
                        
                        if ( !empty( $_POST ) ) {
                            
                            $comment = strip_tags( $_POST['comment'] );
                            $stars = $_POST['stars'];
                            $date = date("Y-m-d");
                            $author_id = $_SESSION['authorized'];
                            $course_id = self::$course['id'];
                            $url = self::$course['url'];
                            $rating;
                            for ( $i = 0; $i <= 4; $i++ ) {

                                if ( $stars[$i] == 'true' ) {
                                       
                                    $rating = ( $i + 1);
                                    break;
                                    
                                }
                                
                            }
                            var_dump($rating);
                            \Models\Comments::insert("'', '', '', '$course_id', '$author_id', '$comment', '$rating', '$date'");
                            \Models\Notifications::insert("'', 'добавил отзыв к курсу', '/course/$url', '$date', '$author_id'");
                            
                            echo true;
                            
                        }
                        
                        parent::getView( 'course', self::$course['title'] );
                    
                    }
                    
                } else {
                    
                    parent::getView( 'buycourse', 'Купить курс "' . self::$course['title'] . '"');
                    
                }
                
            } else {
                
                parent::getView( 'buycourse', 'Купить курс "' . self::$course['title'] . '"');
                
            }
            
        
        }
        
    }
    
}
