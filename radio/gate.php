<?php
// is there a session?
session_start();
// if not, redirect to login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Gate</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>The Gate</h1>
                <p>You have found yourself </p>
                <form action="login.php" method="post">
                    <input type="text" name="username" placeholder="Username">
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>

</body>
</html>