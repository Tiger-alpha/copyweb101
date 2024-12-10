<?php
// Start Session
session_start();

// Include database connection
include('db/connection.php'); // Ensure this file contains the code you shared

// Redirect to index.php if not logged in as client
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'client') {
    header("Location: index.php");
    exit();
}

// Retrieve client data from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// Check if client data exists
if (!$client) {
    echo "Client not found.";
    exit();
}

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);
    $profile_image = $_FILES['profile_image'];

    // Password validation (if new password is provided)
    if (!empty($password) && $password !== $password_confirm) {
        $error = "Passwords do not match.";
    } elseif (!empty($password)) {
        // Hash the password before updating
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Handle file upload for profile image
    if ($profile_image['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($profile_image["tmp_name"]);
        if ($check !== false) {
            // Check file size (e.g., 5MB max)
            if ($profile_image["size"] > 5000000) {
                $error = "File is too large.";
            } else {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($profile_image["tmp_name"], $target_file)) {
                    $image_path = basename($profile_image["name"]);
                } else {
                    $error = "Error uploading the image.";
                }
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        // If no image was uploaded, use the current profile image
        $image_path = $client['profileimg'];
    }

    // Update client details (excluding password if not provided)
    $sql = "UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, profileimg = ?";

    if (!empty($password)) {
        $sql .= ", password = ?";
    }
    $sql .= " WHERE username = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($password)) {
        $stmt->bind_param("ssssssss", $firstname, $lastname, $email, $phone, $image_path, $hashed_password, $username);
    } else {
        $stmt->bind_param("ssssss", $firstname, $lastname, $email, $phone, $image_path, $username);
    }
    
    if ($stmt->execute()) {
        // Successful update
        $success_message = "Profile updated successfully!";
    } else {
        $error = "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 500px; margin: auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .error { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($client['firstname']); ?>" required>
        </div>
        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($client['lastname']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($client['phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">New Password (leave blank to keep current password)</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="password_confirm">Confirm New Password</label>
            <input type="password" id="password_confirm" name="password_confirm">
        </div>
        <div class="form-group">
            <label for="profile_image">Profile Image (optional)</label>
            <input type="file" id="profile_image" name="profile_image">
        </div>
        <button type="submit" class="submit-btn">Save Changes</button>
    </form>
</div>

<?php if (isset($success_message)): ?>
    <script>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo $success_message; ?>',
            icon: 'success',
            timer: 1000,  // Set the time for the SweetAlert to disappear (in ms)
            showConfirmButton: false
        }).then(function() {
            window.location.href = 'client_dashboard.php'; // Redirect after clicking 'Okay'
        });
    </script>
<?php endif; ?>

</body>
</html>
