<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
            font-style: italic;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"], input[type="phone"], select {
            width: 100%;
            padding: 12px 20px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="phone"]:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        input[type="submit"]:active {
            transform: translateY(0);
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-size: 15px;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
        .footer a {
            color: #007bff;
        }
        /* Align "Have an Account?" and "Login" on the same line */
        .login-link {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 15px;
            margin-top: 20px;
        }
        .login-link a {
            margin-left: 5px;
            color: green;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Register</h2>
        <p class="message" style="text-align: center; color: #555; font-size: 18px;">Signup now</p>

        <?php
        if (isset($_GET['message'])) {
            echo "<p class='error-message'>" . htmlspecialchars($_GET['message']) . "</p>";
        }

        // Display SweetAlert if registration is successful
        if (isset($_GET['message_success'])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Registration Successful!',
                        text: 'You have successfully registered.',
                        icon: 'success',
                        timer: 1000,  // Auto-close after 3 seconds
                        showConfirmButton: false  // Hide the confirm button
                    });
                });
              </script>";
        }
        ?>

        <form action="register_account.php" method="post" enctype="multipart/form-data">
            <input type="text" name="firstname" placeholder="Enter Firstname" required>
            <input type="text" name="lastname" placeholder="Enter Lastname" required>
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="phone" name="phone" placeholder="Enter Phone Number" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="password" name="password_confirm" placeholder="Confirm Password" required>
            <input type="file" name="profile_image" accept="image/*">
            
            <!-- Hidden field to always assign the role as 'client' -->
            <input type="hidden" name="role" value="client">
            
            <select name="table" required>
                <?php
                // Fetch table names from the database
                include('db/connection.php');  // Include your DB connection file
                $result = $conn->query("SHOW TABLES");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_row()) {
                        echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
                    }
                } else {
                    echo "<option value=''>No tables found</option>";
                }
                ?>
            </select>

            <input type="submit" name="register" value="Register">
        </form>

        <div class="login-link">
            <p>Have an Account?</p>
            <a href="index.php">Login</a>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Your Company. All rights reserved.</p>
    </div>

</body>
</html>
