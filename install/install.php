<?php

$step = $_GET['step'];

if ( ( $step <= 3 ) && ( $step >= 2 ) ) {
    
    require '../protected/config/database.php';

    $dbh = new \PDO('mysql:host=' . $database['host'] . ';dbname=' . $database['name'], $database['user'], $database['password']);

    
}

if ( $step == 1 && !empty( $_POST ) ) {
        
    $data = $_POST;
    
    try {
        
        $dbh = new \PDO('mysql:host=' . $data['host'] . ';dbname=' . $data['name'], $data['user'], $data['password']);
            
    } catch ( \PDOException $e ) {
            
        $error = 'Ошибка соединения с базой данных!';
            
    }
    
    if ( empty( $error ) ) {
        
        $file = file_get_contents('../protected/config/database.php');
        
        foreach ( $data as $k => $v ) {

            
            $file = str_replace('_' . $k, "'" . $v . "'", $file);
                
        }
        
        $fp = fopen("../protected/config/database.php", "w+");
        fwrite($fp, $file);
        fclose($fp);
        
        $sql = file_get_contents('table.sql');

        $sth = $dbh->prepare($sql);
        
        $sth->execute();
        
        header("Location: /install/install?step=2");
        
    }
    
}

if ( $step == 2 && !empty( $_POST ) ) {
    
    $data = $_POST;
    
    foreach ( $data as $k => $v ) {

        $$k = strip_tags(trim($v));
            
    }
    
    if ( $password !== $repassword ) {
        
        $error = 'Введённые пароли не совпадают!';
        
    } else {
        
        if ( strlen( $password ) < 6 ) {
          
            $error = 'Длина пароля должная быть не менее 6 символов!';
            
        } else {
    
            if ( !preg_match("/^[\w\d\s.,-]*$/", $username) ) {
                
                $error = 'Имя пользователя должно быть написано латинскими буквами!';
                
            } else {
                
                $password = sha1( 'g64%$^*&*_' . $password . 'nHGH6654$%^' );
                
                $sth = $dbh->prepare("INSERT INTO users VALUES('', 'admin', '', '', '$email', '/assets/images/user.png', '$username', '$password')");
        
                $sth->execute();
                
                $sth = $dbh->prepare("INSERT INTO settings VALUES('', '$title', '$email')");
        
                $sth->execute();

                session_start();
                
                $_SESSION['authorized'] = 1;
                
                $date = date("Y-m-d");
                
                $sth = $dbh->prepare("INSERT INTO notifications VALUES('', 'зарегистрировался', '/user/$username', '$date', '$_SESSION[authorized]')");
        
                $sth->execute();     
                
                header("Location: /install/install?step=3");
            
            }
            
        }
    
    }

    if ( empty( $error ) ) {
        
        $sql = file_get_contents('table.sql');
    
        $sth = $dbh->prepare($sql);
        
        $sth->execute();
        
        header("Location: /install/install?step=3");
        
    }
    
}

if ( $step == 4 ) {
    
    unlink('../.htaccess');
    rename('../_.htaccess', '../.htaccess');
    header('Location: /dashboard');
    
} else {

    if ( isset( $step ) ) {
        
        require 'views/header.php';
        require 'views/step-' . $step . '.php';
        require 'views/footer.php';
        
    } else {
        
        header("Location: /install/install?step=0");
        
    }

}
    
?>
