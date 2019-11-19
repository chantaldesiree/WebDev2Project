<?php 

session_start();
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

        $query = "SELECT * FROM post WHERE post_id = :post_id LIMIT 1";
        $statement = $db->prepare($query);

        $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);

        $statement->bindValue('post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        $post = $statement->fetch();

    if($_SESSION['user'] == null)
    {
        header("Location: index.php");
    }


?>

<?php include('nav.php'); ?>

    <div class="main-content">
        <div class="content">

            <form method="post" action="update.php">
                
                <input type="hidden" name="post_id" value ="<?= $post_id ?>">
                <input type="hidden" name="post_date">


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