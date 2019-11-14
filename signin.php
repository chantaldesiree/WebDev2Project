<?php
    require('db_connect.php');
    session_start();
    
    if ($_POST && !empty($_POST['user_email']) && !empty($_POST['user_password'])) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "SELECT * FROM useraccountinformation WHERE user_email = '$email'";
        $statement = $db->prepare($query); // Returns a PDOStatement object.
        $statement->execute(); // The query is now executed.
        $user = $statement->fetch();
        $hashedpassword = $user['user_password'];
        

        if($statement->execute()){

            $valid = password_verify($password, $hashedpassword);
            if ($valid) {
                header("Location: welcome.php");
                $_SESSION['user'] = $user;
                
            }
            else {
            }

        }

    }
?>

<?php include('nav.php'); ?>

    <div class="main-content">
        <form method="post" action="signin.php">
            <label for="user_email">Email Address: </label>
            <input type="email" id="user_email" name="user_email">
            <label for="user_password">Password: </label>
            <input type="password" id="user_password" name="user_password">
            <input type="submit">
        </form>
    </div>

<?php include('footer.php'); ?>