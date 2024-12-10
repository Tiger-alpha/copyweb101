<?php
// Start session and include database connection
session_start();
include('db/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $profile_image = $_FILES['profile_image']['name'];

    // Always assign 'client' role
    $role = 'client'; // Assigning the default role as 'client'

    // Validate that the passwords match
    if ($password !== $password_confirm) {
        header("Location: register.php?message=" . urlencode("Passwords do not match."));
        exit();
    }

    // Optional: Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile image upload
    if ($profile_image) {
        // Set the upload directory and ensure the profile image is saved correctly
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($profile_image);
        
        // Move the uploaded image to the directory
        if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_file)) {
            header("Location: register.php?message=" . urlencode("Failed to upload profile image."));
            exit();
        }
    }

    // Prepare the SQL query to insert the user data with the role 'client'
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, email, phone, password, profileimg, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $firstname, $lastname, $username, $email, $phone, $hashed_password, $profile_image, $role);

    // Execute the query and redirect accordingly
    if ($stmt->execute()) {
        header("Location: register.php?message_success=1");
    } else {
        header("Location: register.php?message=" . urlencode("Registration failed."));
    }
}
?>
