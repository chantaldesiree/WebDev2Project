<?php

        /*
    * Purpose: Lets the webpage connect to the database.
    */
    require('db_connect.php'); 
    session_start();

    /*
    * Purpose: Requires the user to log-in to reach the page.
    */

            $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
            $post_author = $_SESSION['user']['user_id'];
            $post_date = date('Y-m-d h:i:sa');
            $post_title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_SPECIAL_CHARS);
            $post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_SPECIAL_CHARS);
    
            $query = "UPDATE post SET post_id = :post_id, post_author = :post_author, post_date = :post_date, post_title = :post_title, post_content = :post_content WHERE post_id = :post_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':post_id', $post_id);
            $statement->bindValue(':post_author', $post_author);
            $statement->bindValue(':post_date', $post_date);
            $statement->bindValue(':post_title', $post_title);
            $statement->bindValue(':post_content', $post_content);
    
            $statement->execute();
    
   header("Location: welcome.php");
    
?>