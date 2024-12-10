<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inde.css">
    <title>Login Page</title>
</head>
<body>

    <div class="container">
        <h2>Login</h2>
        <div class="error-message">
            <?php
            if (isset($_GET['Incorrect'])) {
                echo "Incorrect Username or Password. Please try again.";
            }
            ?>
        </div>
        <form action="authenticate.php" method="post">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" value="Login" name="login">
        </form>
        <a href="register.php">Create An Account</a>
    </div>

    <div class="footer">
        <p>&copy; 2024 Your Company. All rights reserved.</p>
    </div>

</body>
</html>
