<?php

namespace Raice;

function autoload ($class) {
    
    $class = str_replace('\\', '/', $class);
        
    if ( file_exists( '../protected/' . $class . '.php' ) ) {
      
        require_once ROOT_PATH . 'protected/' . $class . '.php';
        return true;
     
    } else {
        
        throw new \Exception('The ' . $class . ' class doesn\'t exist!');
        return false;
          
    }
        
}
    
spl_autoload_register('Raice\autoload');
