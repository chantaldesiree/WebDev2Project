<?php

    session_start();

    require('db_connect.php');

    if (isset($_SESSION['user']))
    {
        $user_id = $_SESSION['user']['user_id'];
    }

    if(isset($_GET['search']) && isset($user_id))
        {
            $search = $_GET["search"];

            if($_GET['post_category'] == "all")
            {
                $query = "SELECT * FROM post WHERE (`post_title` LIKE '%".$search."%') OR (`post_content` LIKE '%".$search."%')";
            }
            else 
            {
                $query = "SELECT * FROM post WHERE ((`post_title` LIKE '%".$search."%') OR (`post_content` LIKE '%".$search."%')) AND post_category = '".$_GET['post_category']."'";
            }

        }

    $statement = $db->prepare($query); // Returns a PDOStatement object.
    $statement->execute(); // The query is now executed.
    $fullblog = $statement->fetchAll();
    $blog = array_reverse($fullblog);

    $x = 0;

    if ($fullblog != null)
    {
        $post_content = $blog[$x]['post_content'];
        $content = strip_tags($post_content, "<p>");
    }

?>

<?php include('nav.php') ?>

    <div class="main-content">
        <h1>Welcome to boondoggle!</h1>
        <h2>This site is currently a work in progress. Come back soon to see what new features we have added next!</h2>
        <h1></h1>

        <?php while($x < count($blog)): ?>   
            <div class="post">
            <?php if ($x <= 20): ?>
                <h2><a href="entry.php?post_id=<?= $blog[$x]['post_id'] ?>"><?= $blog[$x]['post_title'] ?></a> posted by 
                
                <?php
                $userquery = "SELECT * FROM useraccountinformation WHERE user_id = ".$blog[$x]['post_author'];
                $stmt = $db->prepare($userquery); // Returns a PDOStatement object.
                $stmt->execute(); // The query is now executed.
                $userdisplayName = $stmt->fetch();
                ?>
                
                <?= $userdisplayName['user_displayName'] ?></h2>
                <div class="form-element">
                    <h6>Posted on <?= date('M d Y, h:ia', strtotime($blog[$x]['post_date'])) ?>


                    <?php if(isset($_SESSION['user']) && ($_SESSION['user']['user_id'] == $blog[$x]['post_author']) || (isset($_SESSION['user']) && $_SESSION['user']['user_admin'] == 1)): ?>
                        - <a href="edit.php?post_id=<?= $blog[$x]['post_id']?>">edit</a> | <a href="delete.php?post_id=<?= $blog[$x]['post_id']?>">delete</a></h6>

                    <?php endif; ?>
                        </h6><p><?= strip_tags(html_entity_decode($blog[$x]['post_content'])); ?></p>

                        <?php
                            $commentquery = "SELECT * FROM comment WHERE comment_postId = ".$blog[$x]['post_id'];
                            $stmt2 = $db->prepare($commentquery); // Returns a PDOStatement object.
                            $stmt2->execute(); // The query is now executed.
                            $commentCount = $stmt2->fetchAll();
                        ?>

                        <p><a href="entry.php?post_id=<?= $blog[$x]['post_id']?>">Read Full Post...</a> | <?= count($commentCount) ?> Comments</p>
                    <?php $x++ ?>
            <?php else: ?>
                <?php $x = count($blog) ?>
            <?php endif ?>
                </div>
            </div>
        <?php endwhile; ?>   
    </div>

<?php include('footer.php'); ?>