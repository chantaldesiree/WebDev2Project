<?php
    $postcategoryquery = "SELECT DISTINCT post_category FROM post ORDER BY post_category";

    $postcategorystatement = $db->prepare($postcategoryquery); // Returns a PDOStatement object.
    $postcategorystatement->execute(); // The query is now executed.
    $postcategorylist = $postcategorystatement->fetchAll();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>boondoggle</title>
    <script src="https://kit.fontawesome.com/4263976262.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://www.strictly-software.com/scripts/downloads/encoder.js"></script>

</head>
<body>
    <div id="container-fluid" class="container-fluid">
        <div class="content">
            <div class="navigation">
                <div class="row navigation-container">
                    <div class="left-nav col-sm-4">
                        <?php if(isset($_SESSION['user']['user_email'])): ?>
                            <div class="new-post"><a href="index.php"><img id="logo" src="images/boondoggle.png" alt="boondoggle logo"></a> <a href="post.php">[New Post]</a></div>
                        <?php else: ?>
                        <a href="index.php"><img id="logo" src="images/boondoggle.png" alt="boondoggle logo"></a>
                        <?php endif; ?>
                    </div>
                    <div class="right-nav col-sm-8">
                        <ul>
                        <?php if(isset($_SESSION['user']['user_email'])): ?>
                            <li>
                                <form action="search.php" method="get">
                                    <input type="text" name="search">
                                    <select name="post_category">
                                            <option value="all">All Categories</option>
                                            <?php foreach($postcategorylist as $category): ?>
                                                <option><?= $category['post_category'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <input type="submit">
                                </form>
                            </li>   
                            <li><a href="welcome.php"><?= $_SESSION['user']['user_displayName'] ?></a></li>
                            <li><i class="fas fa-user-circle"></i><a href="account.php">Account</a></li>
                            <li><i class="fas fa-sign-out-alt"></i><a href="signout.php">Logout</a></li>
                        <?php else: ?>
                            <li>
                                <form action="search.php" method="get">
                                    <input type="text" name="search">
                                    <input type="submit">
                                </form>
                            </li> 
                            <li><i class="fas fa-user-plus"></i><a href="register.php">Sign up</a></li>
                            <li><i class="fas fa-user-circle"></i><a href="signin.php">Login</a></li>
                        <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>