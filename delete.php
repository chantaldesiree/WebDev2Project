<?php

    require('db_connect.php');


    $query = "DELETE FROM post WHERE post_id = :post_id LIMIT 1";
    $statement = $db->prepare($query);

    if(isset($_GET['post_id']))
    {
        $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    }
    else 
    {
        $post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    }

    $statement->bindValue('post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();
    
    header("Location: index.php");
?>