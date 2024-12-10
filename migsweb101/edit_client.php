<?php
// Start session and check if the user is an admin
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit();
}

// Include the database connection
include('db/connection.php');

// Initialize variables for the form values
$username = $firstname = $lastname = $email = "";

// Check if the client ID is set in the URL
if (isset($_GET['ID'])) {
    $client_id = $conn->real_escape_string($_GET['ID']);

    // Fetch client data from the database
    $sql = "SELECT * FROM users WHERE ID = '$client_id' AND role = 'client'";
    $result = $conn->query($sql);

    // Check if the client exists
    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        // Assign current values to variables for display in the form
        $username = $client['username'];
        $firstname = $client['firstname'];
        $lastname = $client['lastname'];
        $email = $client['email']; // Ensure email is displayed
    } else {
        echo "<div class='error-message'>Client not found!</div>";
        exit();
    }
} else {
    echo "<div class='error-message'>No client ID provided!</div>";
    exit();
}

// Handle form submission for updating client details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);

    // Update client data in the database
    $update_sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email' WHERE ID='$client_id' AND role='client'";
    if ($conn->query($update_sql) === TRUE) {
        // Set a session variable to indicate a successful update
        $_SESSION['update_success'] = true;

        // Redirect to view_client.php with the client ID
        header("Location: view_client.php?ID=$client_id");
        exit();
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update client details.',
                icon: 'error'
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client Details</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit Client Details</h1>
            <a href="view_client.php?ID=<?php echo $client_id; ?>" class="back-button">Back to Client Details</a>
        </header>

        <form action="" method="post" class="edit-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <button type="submit" onclick="confirmUpdate(event)">Update Client</button>
        </form>
    </div>

    <script>
        function confirmUpdate(event) {
            event.preventDefault(); // Prevent form submission
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to update the client's details.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('.edit-form').submit(); // Submit form after confirmation
                }
            });
        }
    </script>
</body>
</html>
