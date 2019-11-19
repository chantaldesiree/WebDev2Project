<?php
    session_start();

    require('db_connect.php');

    $user_id = $_SESSION['user']['user_id'];

    $query = "SELECT * FROM post ORDER BY post_date";

    if(isset($_GET['sortby']) && isset($user_id))
    {
        $sort = $_GET["sortby"];
        
        if($sort == "title")
        {
            $query = "SELECT * FROM post ORDER BY post_title DESC";
        }
        if($sort == "author")
        {
            $query = "SELECT * FROM post INNER JOIN useraccountinformation ON post.post_author = useraccountinformation.user_id ORDER BY useraccountinformation.user_displayName DESC";
        }
        if($sort == "comments")
        {
            $query = "SELECT * FROM post ORDER BY post_commentCount";
        }

        if($sort == "newest to oldest")
        {
            $query = "SELECT * FROM post ORDER BY post_date ASC";
        }

        if($sort == "oldest to newest")
        {
            $query = "SELECT * FROM post ORDER BY post_date DESC";
        }

    }

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

<?php include('nav.php') ?>

    <div class="main-content">
        <h1>Welcome to boondoggle!</h1>
        <h2>This site is currently a work in progress. Come back soon to see what new features we have added next!</h2>
        <h1></h1>

        <?php if(isset($user_id)): ?>
            <form action="index.php" method="get">
            <input type="submit" name="sortby" value="title" />
            <input type="submit" name="sortby" value="newest to oldest" />
            <input type="submit" name="sortby" value="oldest to newest" />
            <input type="submit" name="sortby" value="author" />
            <input type="submit" name="sortby" value="comments" />
            </form>
        <?php endif; ?>

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


                    <?php if(isset($_SESSION['user']) && ($_SESSION['user']['user_id'] == $blog[$x]['post_author']) || $_SESSION['user']['user_admin'] == 1): ?>
                        - <a href="edit.php?post_id=<?= $blog[$x]['post_id']?>">edit</a> | <a href="delete.php?post_id=<?= $blog[$x]['post_id']?>">delete</a></h6>

                    <?php endif; ?>
                        </h6><p><?= $blog[$x]['post_content'] ?></p>

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