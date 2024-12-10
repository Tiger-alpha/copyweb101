<?php
    //include database connection file
    include('db/connection.php');
    //start a session variable to manage user data
    session_start();

    if(isset($_POST['login']))
    {
        //sanitize the username to prevent sql injection
        $username = $conn->real_escape_string($_POST['username']);
        //get password from the form (note not yet encrypted)
        $password = $_POST['password'];

        //SQL query to select user data from database based on the username
        $sql_username = "SELECT * FROM users WHERE username='$username'";
        //Execute query
        $result = $conn->query($sql_username);
        //check if the query returned any result
        if($result->num_rows > 0)
        {
            //fetch the associated user data
            $row = $result->fetch_assoc();
            //verify password against stored hash password
            if(password_verify($password, $row['password']))
            {
                //set session variable for username and role
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];
                 //redirect the user to the appropriate dashboard
                 if($row['role'] == 'admin')
                 {
                    //redirect to admin dashboard
                    header("location: admin_dashboard.php");
                 }
                 else if ($row['role'] == 'client')
                 {
                    //redirect to admin dashboard
                    header("location:client_dashboard.php");
                 }
            }
            else
            {
                //if the password is incorrect show an error message and redirect to login page
                header("location: index.php?Incorrect");
            }
        }
        else
        {
            //No username found
            header("location: index.php?Incorrect_Username");
        }
    }
?>