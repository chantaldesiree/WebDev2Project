<?php 
session_start();

    require('db_connect.php'); 

    $query = "SELECT * FROM post WHERE post_id = :post_id LIMIT 1";
    $statement = $db->prepare($query);

    $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);

    //Binds the selected values to the database values.
    $statement->bindValue('post_id', $post_id, PDO::PARAM_INT);
    $statement->execute();

    $post = $statement->fetch();

    if (!empty($_POST['comment_content']))
    {
        $comment_postId = $post['post_id'];
        $comment_author = $_SESSION['user']['user_id'];
        $comment_date = date('Y-m-d h:i:sa');
        $comment_content = filter_input(INPUT_POST, 'comment_content', FILTER_SANITIZE_SPECIAL_CHARS);

        $query2 = "INSERT INTO comment (comment_postId, comment_author, comment_date, comment_content) VALUES (:comment_postId, :comment_author, :comment_date, :comment_content)";
        $commentstatement = $db->prepare($query2);
        $commentstatement->bindValue(':comment_postId', $comment_postId, PDO::PARAM_INT);
        $commentstatement->bindValue(':comment_author', $comment_author);
        $commentstatement->bindValue(':comment_date', $comment_date);
        $commentstatement->bindValue(':comment_content', $comment_content);

        $commentstatement->execute();
        
        $setpostcommentquery = "UPDATE `post` SET `post_commentCount` = `post_commentCount` + 1 WHERE `post`.`post_id` = ".$post['post_id'];
        $statement3 = $db->prepare($setpostcommentquery);
        $statement3->execute();

        header("Location: entry.php?post_id=".$post_id);

    }

?>

<?php include('nav.php') ?>

    <div class="main-content">
    <h1><?= $post['post_title'] ?></h1>
        <h5><?= date('M d, Y h:i a', strtotime($post['post_date'])) ?>
        <?php if($_SESSION['user'] != null && $_SESSION['user']['user_id'] == $post['post_author']): ?>
            - <a href="edit.php?post_id=<?= $post['post_id']?>">edit</a></h6>
        <?php endif; ?>
        <p><?= $post['post_content'] ?></p>

        <?php
            $commentquery = "SELECT * FROM comment
                            LEFT JOIN post
                            ON comment.comment_postId = post.post_id
                            WHERE comment.comment_postId = ".$post['post_id'];
            $stmt = $db->prepare($commentquery); // Returns a PDOStatement object.
            $stmt->execute(); // The query is now executed.
            $comments = $stmt->fetchAll();
        ?>

        <h6>.................... Comments .................</h6>
        <?php foreach ($comments as $comment): ?>

        <?php
            $userquery = "SELECT user_displayName FROM useraccountinformation WHERE user_id = ".$comment['comment_author'];
            $stmt2 = $db->prepare($userquery); // Returns a PDOStatement object.
            $stmt2->execute(); // The query is now executed.
            $commentdisplayname = $stmt2->fetch();
        ?>

            <h6><?= $commentdisplayname['user_displayName'] ?></h6>
            <h6><?= $comment['comment_date'] ?></h6>
            <h6><?= $comment['comment_content'] ?></h6>
            <h6>..........................................................</h6>
        <?php endforeach; ?>

        <br>

        <?php if(isset($_SESSION['user']['user_id'])): ?>
        <form method="post" action="entry.php?post_id=<?=$post_id ?>">
            <div class="row">
                Commenting as:
            </div>
            <div class="row form-element">
                <?php
                    $userquery2 = "SELECT user_displayName FROM useraccountinformation WHERE user_id = ".$_SESSION['user']['user_id'];
                    $stmt3 = $db->prepare($userquery2); // Returns a PDOStatement object.
                    $stmt3->execute(); // The query is now executed.
                    $userdisplayname = $stmt3->fetch();
                ?>
                <h6><?= $userdisplayname['user_displayName'] ?></h6>
            </div>
            <div class="row">
                <label for="comment_content"><h3>Comment: </h3></label>
            </div>
            <div class="row form-element">
                <textarea class="form-element" id="comment_content" name="comment_content" row="100" cols="200"></textarea>
            </div>
            
            <input type="hidden" id="comment_author" name="comment_author" value="<?= $_SESSION['user']['user_id'] ?>">
            <input type="hidden" id="comment_postId" name="comment_postId">
            <input type="hidden" id="comment_date" name="comment_date">
            
            <div class="row form-element">
                <input type="submit" value="Comment">
            </div>
        </form>
        <?php else: ?>
        <h4><a href="register.php">Sign up</a> or <a href="signin.php">Log in</a> to leave a comment.</h4>
        <?php endif; ?>

        <br><br><br>

<?php include('footer.php'); ?>