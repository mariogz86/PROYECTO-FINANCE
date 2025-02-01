<?php

    spl_autoload_register(function($clase){
        // if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        //     $file = './';
        //     $p = explode('\\', $clase);
        //     $len = count($p);
    
        //     for($i = 0; $i < $len; $i++) {
    
        //         // exclude final element
        //         if ($i != ($len - 1)) {
        //             $lower = strtolower($p[$i]);
        //             $file .= $lower . '/';
        //         } else {
        //             $file .= $p[$i];
        //         }
        //     }
    
        //     include $file . '.php';
        // } else {
        //     include $clase . '.php';
        // }

        $archivo= __DIR__."/".$clase.".php";
        $archivo=str_replace("\\","/",$archivo);
     
        if(is_file($archivo)){
            require_once $archivo;
        } 

    
    });


  