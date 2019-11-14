<?php 
/*
 * Chantal Wiebe
 * September 29, 2019
 * Assignment 4
 *
*/

    /*
    * Purpose: Lets the webpage connect to the database.
    */
    require('db_connect.php'); 

    /*
    * Purpose: Requires the user to log-in to reach the page.
    */

    if(isset($_SESSION['user'])) 
    {
        
        $query = "SELECT * FROM post WHERE post_id = :post_id LIMIT 1";
        $statement = $db->prepare($query);

        $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);

        $statement->bindValue('post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        $post = $statement->fetch();

        if ($_POST && !empty($_POST['post_title']) && !empty($_POST['post_content']))
        {

            $post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
            $post_author = $_SESSION['user']['user_id'];
            $post_date = date('Y-m-d h:i:sa');
            $post_title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_SPECIAL_CHARS);
            $post_content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_SPECIAL_CHARS);
    
            $query = "INSERT INTO post (post_id, post_author, post_date, post_title, post_content) VALUES (:post_id, :post_author, :post_date, :post_title, :post_content) WHERE post_id = :post_id";
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
        else
        {
            header("Location: signin.php");
        }
    }

?>

<?php include('nav.php'); ?>

    <div class="main-content">
        <div class="content">
            <form method="post" action="edit.php">
                <div class="row">
                    <label for="post_title"><h3>Title: </h3></label>
                </div>
                <div class="row form-element">
                    <input type="text" id="post_title" name="post_title" value="<?= $post['post_title'] ?>">
                </div>
                <div class="row">
                    <label for="post_content"><h3>Content: </h3></label>
                </div>
                <div class="row form-element">
                    <textarea class="form-element" id="post_content" name="post_content" row="100" cols="200"><?= $post['post_content'] ?></textarea>
                </div>
                <div class="row form-element">
                    <input type="submit" value="Edit Post">
                </div>
            </form>
            <form method="post" action="delete.php">
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <div class="row form-element">
                    <input type="submit" value="Delete Post">
                </div>
            </form>
            <h5><a href="index.php">Home</a></h5>
        </div>
    </div>

<?php include('footer.php'); ?>