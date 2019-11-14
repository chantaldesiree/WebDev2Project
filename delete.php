<?php
    session_start();

    /*
    * Purpose: Lets the webpage connect to the database.
    */
    require('db_connect.php');


    $query = "DELETE FROM post WHERE post_id = :post_id LIMIT 1";
    $statement = $db->prepare($query);

    $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    $statement->bindValue('post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
    
?>