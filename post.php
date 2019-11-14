<?php
    session_start();

    require('db_connect.php');
    
    if (!empty($_POST['post_title']) && !empty($_POST['post_content']))
    {
        $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
        $post_author = $_SESSION['user']['user_id'];
        $post_date = date('Y-m-d h:i:sa');
        $post_title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_SPECIAL_CHARS);
        $post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_SPECIAL_CHARS);

        $query = "INSERT INTO post (post_id, post_author, post_date, post_title, post_content) VALUES (:post_id, :post_author, :post_date, :post_title, :post_content)";
        $statement = $db->prepare($query);
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':post_author', $post_author);
        $statement->bindValue(':post_date', $post_date);
        $statement->bindValue(':post_title', $post_title);
        $statement->bindValue(':post_content', $post_content);

        $statement->execute();

        header("Location: welcome.php");

        exit();

    }

    /*
    * Purpose: If it doesn't validate, you will see this error.
    */
    $errorMessage = "The blog post could not be created.";
?>

<?php include('nav.php'); ?>

    <div class="main-content">
        <form method="post" action="post.php">
            <div class="row">
                <label for="post_title"><h3>Title: </h3></label>
            </div>
            <div class="row form-element">
                <input type="text" id="post_title" name="post_title">
            </div>
            <div class="row">
                <label for="post_content"><h3>Content: </h3></label>
            </div>
            <div class="row form-element">
                <textarea class="form-element" id="post_content" name="post_content" row="100" cols="200"></textarea>
            </div>
            
            <input type="hidden" id="post_id" name="post_id">
            <input type="hidden" id="post_date" name="post_date">
            
            <div class="row form-element">
                <input type="submit" value="Post">
            </div>
        </form>
    </div>

<?php include('footer.php'); ?>