<?php
// Start session and output buffering
session_start();
ob_start();

// Include database connection
include('db/connection.php');

// Redirect to index.php if not logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Retrieve admin data from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    echo "<script>Swal.fire({
            title: 'Error!',
            text: 'Admin not found.',
            icon: 'error',
            timer: 1000,
            showConfirmButton: false
        });
    </script>";
    exit();
}

// Flags to check if updates occurred
$image_updated = false;
$password_updated = false;
$alert_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $alert_message = "<script>Swal.fire({
            title: 'Error!',
            text: 'Passwords do not match!',
            icon: 'error',
            timer: 1000,
            showConfirmButton: false
        });
        </script>";
    } else {
        $hashed_password = $admin['password']; // Default to current password
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $password_updated = true;
        }

        if (isset($_FILES['profileimg']) && $_FILES['profileimg']['error'] == 0) {
            $image_tmp = $_FILES['profileimg']['tmp_name'];
            $image_name = $_FILES['profileimg']['name'];
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_new_name = uniqid() . '.' . $image_ext;
            $upload_dir = 'uploads/';
            $image_path = $upload_dir . $image_new_name;

            if (move_uploaded_file($image_tmp, $image_path)) {
                $update_img_sql = "UPDATE users SET profileimg = ? WHERE username = ?";
                $stmt_img = $conn->prepare($update_img_sql);
                $stmt_img->bind_param("ss", $image_new_name, $username);
                $stmt_img->execute();
                $image_updated = true;
            } else {
                $alert_message = "<script>Swal.fire({
                    title: 'Error!',
                    text: 'Error uploading image.',
                    icon: 'error',
                    timer: 1000,
                    showConfirmButton: false
                });
                </script>";
            }
        }

        $update_sql = "UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ?, password = ? WHERE username = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssss", $firstname, $lastname, $email, $phone, $hashed_password, $username);
        $stmt->execute();

        if ($stmt->affected_rows > 0 || $image_updated || $password_updated) {
            $alert_message = "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Details updated successfully!',
                    icon: 'success',
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'admin_dashboard.php';
                });
            </script>";
        } else {
            $alert_message = "<script>Swal.fire({
                title: 'Info',
                text: 'No changes made.',
                icon: 'info',
                timer: 1000,
                showConfirmButton: false
            });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Details</title>
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
            max-width: 600px;
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
        }
        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
        }
        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Admin Details</h2>

    <form action="edit_admin.php" method="POST" enctype="multipart/form-data">
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($admin['firstname']); ?>" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($admin['lastname']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required><br>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($admin['phone']); ?>" required><br>

        <!-- Profile Image Upload -->
        <label for="profileimg">Change Profile Image:</label>
        <input type="file" name="profileimg" accept="image/*"><br>

        <!-- Password Fields -->
        <label for="password">New Password:</label>
        <input type="password" name="password" placeholder="Leave blank to keep current password"><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password"><br>

        <input type="submit" value="Update Details">
    </form>

    <a href="admin_dashboard.php" class="back-btn">
        <button>Back to Dashboard</button>
    </a>
</div>

<?php
// Display the alert message if set
if (!empty($alert_message)) {
    echo $alert_message;
}
?>

</body>
</html>

<?php ob_end_flush(); ?>
