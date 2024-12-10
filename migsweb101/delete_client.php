<?php
session_start();
include('db/connection.php');

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit();
}

// Check if the client ID is set in the URL and if the 'deleted' flag is present
if (isset($_GET['ID']) && isset($_GET['deleted']) && $_GET['deleted'] === 'true') {
    $client_id = $conn->real_escape_string($_GET['ID']);

    // Perform the deletion query
    $sql = "DELETE FROM users WHERE ID = '$client_id' AND role = 'client'";
    
    if ($conn->query($sql) === TRUE) {
        // Successfully deleted, set a session variable to show the success message
        $_SESSION['delete_success'] = true;
    } else {
        // If deletion fails, handle the error (optional)
        $_SESSION['delete_success'] = false;
    }

    // Redirect to the admin dashboard with a query parameter indicating success
    header("Location: client_list.php?deleted=true");
    exit();
} else {
    // If client ID is not set, redirect to dashboard
    header("Location: client_list.php");
    exit();
}
?>
