<?php

    require('db_connect.php');

    $comment_id = filter_input(INPUT_GET, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

    $postidquery = "SELECT comment_postId FROM comment WHERE comment_id = ".$comment_id;
        $stmt2 = $db->prepare($postidquery); // Returns a PDOStatement object.
        $stmt2->execute(); // The query is now executed.
        $postid = $stmt2->fetch();


    $query = "DELETE FROM comment WHERE comment_id = :comment_id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue('comment_id', $comment_id, PDO::PARAM_INT);
    $statement->execute();
    
    header("Location: entry.php?post_id=" .$postid['comment_postId']);
?>