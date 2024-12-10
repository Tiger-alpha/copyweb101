<?php
// Start the session
session_start();

// Check if an update was successful
if (isset($_SESSION['update_success']) && $_SESSION['update_success'] === true) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Updated!',
                text: 'Client details have been updated successfully.',
                icon: 'success',
                timer: 1000,
                showConfirmButton: false  // Hide the confirm button
            });
        });
    </script>";
    // Unset the session variable after displaying the message
    unset($_SESSION['update_success']);
}

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("location:index.php");
    exit();
}

// Include the database connection
include('db/connection.php');

// Check if the client ID is set in the URL
if (isset($_GET['ID'])) {
    $client_id = $conn->real_escape_string($_GET['ID']);

    // Fetch client data from the database
    $sql = "SELECT * FROM users WHERE ID = '$client_id' AND role = 'client'";
    $result = $conn->query($sql);

    // Check if the client exists
    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        echo "<div class='error-message'>Client not found!</div>";
        exit();
    }
} else {
    echo "<div class='error-message'>No client ID provided!</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Details</title>
    <link rel="stylesheet" href="view.css">  
</head>
<body>

<div class="container">
    <header>
        <h1>Client Details</h1>
        <a href="client_list.php" class="back-button">Back To The Client List</a>
    </header>

    <div class="client-card">
        <!-- Action Icons for Edit and Delete -->
        <div class="actions">
            <!-- <a href="edit_client.php?ID=<?php echo $client_id; ?>" class="edit-icon" title="Edit Client">
                <i class="fas fa-edit"></i> -->
            </a>
            <a href="#" class="delete-icon" onclick="confirmDelete(<?php echo $client_id; ?>)" title="Delete Client">
                <i class="fas fa-trash-alt"></i>
            </a>
        </div>

        <!-- Profile Image -->
        <?php if (!empty($client['profileimg'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($client['profileimg']); ?>" alt="Profile Image" class="profile-image">
        <?php else: ?>
            <img src="default-profile.jpg" alt="Default Profile Image" class="profile-image">
        <?php endif; ?>

        <!-- Client Details -->
        <div class="client-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($client['firstname']) . " " . htmlspecialchars($client['lastname']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($client['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($client['phone']); ?></p>
        </div>
    </div>
</div>

<script>
    function confirmDelete(clientId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Delete confirmed, redirect to delete page
                window.location.href = `delete_client.php?ID=${clientId}&deleted=true`;
            }
        });
    }
</script>

</body>
</html>
