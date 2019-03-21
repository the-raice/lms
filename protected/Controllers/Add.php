<?php

namespace Controllers;

use Raice\Controller as Controller;
use Models\User as User;
    
class Add
    extends Controller
{

    public static $user;
    public static $error;
    
    public function index ()
    {
        
        session_start();
        
        if ( !empty( $_SESSION['authorized'] ) ) {
            
            self::$user = \Models\User::getOneById( $_SESSION['authorized'] )[0];
    
            $url = explode('/', $_SERVER['REQUEST_URI'])[2];
            
            if ( !empty( $url ) ) {
               
                $this->$url();
                
            } else {
                
                header("Location: /");
                
            }
            
        } else {
           
           header("Location: /");
           
        }
        
    }
    
    public function article ()
    {
        
        session_start();
        
        self::$user = $_SESSION['authorized'];
        
        if ( !empty( self::$user ) ) {
        
            if ( !empty( $_POST ) ) {
                
                $title = $_POST['title'];
                $content = trim( strip_tags( str_replace( '</h3>', "\n", $_POST['content'] ) ) );
                $date = date("Y-m-d");
                $id = $_SESSION['authorized'];
                $url =  str_replace( '', ' ', strip_tags( $_POST['url'] ) );
                
                $find = array(
                    '~\[b\](.*?)\[/b\]~s',
                    '~\[i\](.*?)\[/i\]~s',
                    '~\[u\](.*?)\[/u\]~s',
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
                    '<i>$1</i>',
                    '<span style="text-decoration:underline;">$1</span>',
                    '<del>$1</del>',
                    '<pre>$1</'.'pre>',
                    '<span style="font-size:$1px;">$2</span>',
                    '<span style="color:$1;">$2</span>',
                    '<a href="$1">$2</a>',
                    '<a href="$1">$1</a>',
                    '<img src="$1" alt="" />'
                );
                
                $content = preg_replace( $find, $replace, $content );

                if ( !preg_match("/^[\w\d\s.,-]*$/", $title) && $url == "nazvanie-statyi" ) {

                    $cyr = [
                        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
                        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
                        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
                        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
                    ];
                    
                    $lat = [
                        'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
                        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
                        'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
                        'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
                    ];
                    
                    $url = strtolower( str_replace( $cyr, $lat, $title ) );
                    
                } elseif ( preg_match("/^[\w\d\s.,-]*$/", $title) && $url == "nazvanie-statyi" ) {
                    
                    $url = strtolower( $title );

                } elseif ( !preg_match("/^[\w\d\s.,-]*$/", $url) && $url != "nazvanie-statyi" ) {
                    
                    $cyr = [
                        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
                        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
                        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
                        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
                    ];
                    
                    $lat = [
                        'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
                        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
                        'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
                        'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
                    ];
                    
                    $url = strtolower( str_replace( $cyr, $lat, $url ) );
                    
                }
                
                $url = str_replace( ' ', '-', str_replace( '-', ' ', $url ) );
                
                $article = \Models\Article::getOneByField( $url, 'url' );

                if ( !empty( $article ) ) {

                    return false;
                    
                } else {
                
                    echo '/article/' . $url;
                    \Models\Article::insert("'', '$title', '$content', '$date', '$id', '$url'");
                    \Models\Notifications::insert("'', 'создал статью', 'article/$url', '$date', '$id'");
                    
                    return true;
                    
                }
                
                
            } else {
            
                parent::getView( 'AddArticle', 'Добавить статью' );
            
            }
        
        } else {
            
            header("Location: /");
            
        }
        
    }
    
    public function page ()
    {
        
        session_start();
        
        self::$user = $_SESSION['authorized'];
        
        if ( !empty( self::$user ) ) {
        
            if ( !empty( $_POST ) ) {
                
                $title = $_POST['title'];
                $content = strip_tags( $_POST['content'] );
                $date = date("Y-m-d");
                $id = $_SESSION['authorized'];
                $url = str_replace( '', ' ', $_POST['url'] );
                
                $find = array(
                    '~\[b\](.*?)\[/b\]~s',
                    '~\[i\](.*?)\[/i\]~s',
                    '~\[u\](.*?)\[/u\]~s',
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
                    '<i>$1</i>',
                    '<span style="text-decoration:underline;">$1</span>',
                    '<del>$1</del>',
                    '<pre>$1</'.'pre>',
                    '<span style="font-size:$1px;">$2</span>',
                    '<span style="color:$1;">$2</span>',
                    '<a href="$1">$2</a>',
                    '<a href="$1">$1</a>',
                    '<img src="$1" alt="" />'
                );
                
                $content = preg_replace( $find, $replace, $content );

                if ( !preg_match("/^[\w\d\s.,-]*$/", $title) && $url == "nazvanie-stranitsy" ) {

                    $cyr = [
                        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
                        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
                        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
                        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
                    ];
                    
                    $lat = [
                        'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
                        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
                        'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
                        'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
                    ];
                    
                    $url = strtolower( str_replace( $cyr, $lat, $title ) );
                    
                } elseif ( preg_match("/^[\w\d\s.,-]*$/", $title) && $url == "nazvanie-stranitsy" ) {
                    
                    $url = strtolower( $title );

                } elseif ( !preg_match("/^[\w\d\s.,-]*$/", $url) && $url != "nazvanie-stranitsy" ) {
                    
                    $cyr = [
                        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
                        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
                        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
                        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
                    ];
                    
                    $lat = [
                        'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
                        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
                        'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
                        'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
                    ];
                    
                    $url = strtolower( str_replace( $cyr, $lat, $url ) );
                    
                }
                
                $url =  str_replace( ' ', '-', preg_replace( "|[^\d\w ]+|i", "", str_replace( '-', ' ', $url ) ) );
                $class = ucfirst( str_replace( '-', '', $url ) );
                $article = \Models\Page::getOneByField( $url, 'url' );
                
                $filename = str_replace( '/', '', $class );
    
                if ( !empty( $article ) ) {
                    
                    return false;
                    
                } elseif ( file_exists( "../protected/Controllers/$filename.php" ) ) {

                    return false;
                    
                } else {
                
                    echo '/' . $url;
                    
                    \Models\Page::insert("'', '$title', '$content', '$date', '$id', '$url'");
                    \Models\Notifications::insert("'', 'создал страницу', '$url', '$date', '$id'");
                    
                    $content = str_replace( '$this->getName()', 'Page', str_replace( 'class Page', "class $class", file_get_contents('../protected/Controllers/Page.php') ) );
                    
                    $fp = fopen("../protected/Controllers/$filename.php","w+");
                    fwrite($fp,$content);
                    fclose($fp);
                    
                    return true;
                    
                }
                
                
            } else {
            
                parent::getView( 'AddPage', 'Добавить страницу' );
            
            }
            
        } else {
            
            header("Location: /");
            
        }
        
    }
    
}
