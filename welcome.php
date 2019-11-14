<?php
    session_start();

    require('db_connect.php');

    $user_id = $_SESSION['user']['user_id'];

    $query = "SELECT * FROM post WHERE post_author = $user_id";
    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $fullblog = $statement->fetchAll();
    $blog = array_reverse($fullblog);

    $x = 0;
    $post_content = $blog[$x]['post_content'];
    $content = strip_tags($post_content);

    /*
    * Purpose: Trims the blog posts to less than 200 characters.
    
    function cutPost($blog, $x)
    {
        $cutDesc = substr($blog[$x]['post_content'], 0, 200);
        $endOfContent = strrpos($cutDesc, ' ');
        $content = $endOfContent? substr($cutDesc, 0, $endOfContent) : substr($cutDesc, 0);
        return $content;
    }
    */
?>


<?php include('nav.php'); ?>

    <div class="main-content">
        <h1>Welcome, <?= $_SESSION['user']['user_displayName'] ?></h1>
        <?php while($x < count($blog)): ?>   
            <div class="post">
            <?php if ($x <= 10): ?>
                <h2><a href="post.php?post_id=<?= $blog[$x]['post_id'] ?>"><?= $blog[$x]['post_title'] ?></a></h2>
                <div class="form-element">
                    <h5>Posted on <?= date('M d Y, h:ia', strtotime($blog[$x]['post_date'])) ?> 
                    <?php if($_SESSION['user'] != null): ?>
                        - <a href="edit.php?post_id=<?= $blog[$x]['post_id']?>">edit</a></h5>
                    <?php endif; ?>
                        <p><?= $blog[$x]['post_content'] ?></p>   
                    <?php $x++ ?>
            <?php else: ?>
                <?php $x = count($blog) ?>
            <?php endif ?>
                </div>
            </div>
        <?php endwhile; ?>   
    </div>

<?php include('footer.php'); ?>