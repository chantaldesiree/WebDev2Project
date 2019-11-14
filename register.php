<?php
    require('db_connect.php');
    
    if ($_POST && !empty($_POST['user_email']) && !empty($_POST['user_password']) && ($_POST['user_password'] == $_POST['user_password_confirmation'])) {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $displayName = filter_input(INPUT_POST, 'user_displayName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $hashedpassword = password_hash( $password , PASSWORD_DEFAULT );
        
        //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "INSERT INTO useraccountinformation (user_email, user_password, user_displayName) VALUES (:user_email, :user_password, :user_displayName)";
        $statement = $db->prepare($query);
        //  Bind values to the parameters
        $statement->bindValue(':user_email', $email);
        $statement->bindValue(':user_password', $hashedpassword);
        $statement->bindValue(':user_displayName', $displayName);
        
        //  Execute the INSERT.
        //  execute() will check for possible SQL injection and remove if necessary
        if($statement->execute()){
            header("Location: signin.php");
        }

    }
?>

<?php include('nav.php'); ?>

    <div class="main-content">
    <form method="post" action="register.php">
            <label for="user_displayName">Username: </label>
            <input type="text" id="user_displayName" name="user_displayName">
            <label for="user_email">Email Address: </label>
            <input type="email" id="user_email" name="user_email">
            <label for="user_password">Password: </label>
            <input type="password" id="user_password" name="user_password">
            <label for="user_password">Confirm Password: </label>
            <input type="password" id="user_password" name="user_password_confirmation">
            <input type="submit">
        </form>
    </div>

<?php include('footer.php'); ?>