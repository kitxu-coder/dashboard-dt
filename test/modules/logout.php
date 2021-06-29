<?php
    // Destroy Session
    
    session_start();
    require_once '../config.php';
    $user = new User();
    $close = $user->logout();

?>