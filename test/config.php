<?php
    require_once 'data/strings.php';
    define( 'BASE_PATH', 'http://localhost/test/'); // Path
    define( 'DB_HOST', 'localhost' ); //DataBase Server
    define( 'DB_USERNAME', 'root'); // DataBase Username
    define( 'DB_PASSWORD', 'Rurouni'); // DataBase Password
    define( 'DB_NAME', 'tse_test'); // DataBase Name

    $_SESSION['message'] = "";
    $_SESSION['error_message'] = false; // True: error message, False: success message 

    //Register class in class folder
    function my_autoloader($class){
        require_once 'class/' . $class . ".php";
    }
    spl_autoload_register('my_autoloader');

?>