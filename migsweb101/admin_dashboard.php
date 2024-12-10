<?php
// Start session
session_start();

// Include database connection
include('db/connection.php');

// redirect to index.php if not logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// retrieve admin data from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ? AND role = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// check if admin data exists
if (!$admin) {
    echo "Admin not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .admin-details {
            position: relative;
            padding-top: 40px;
            text-align: center;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 15px auto;
            display: block;
        }
        .details p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }
        .edit-icon {
            font-size: 20px;
            cursor: pointer;
            color: #007bff;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .edit-icon:hover {
            color: #0056b3;
        }
        .links {
            text-align: center;
            margin-top: 30px;
        }
        .links a {
            text-decoration: none;
            font-size: 16px;
            margin: 10px;
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: color 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
        }
        .links a.view-clients {
            color: white;
            border-color: #007bff;
            background-color: #007bff;
        }
        .links a.view-clients:hover {
            color: white;
            border-color: #0056b3;
            background-color: #0056b3;
        }
        .links a.logout {
            color: white;
            border-color: red;
            background-color: red;
        }
        .links a.logout:hover {
            color: white;
            border-color: darkred;
            background-color: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome Admin, <?php echo htmlspecialchars($admin['username']); ?></h2>
    
    <div class="admin-details">
        <!-- Display Admin Profile Image -->
        <?php if (!empty($admin['profileimg'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($admin['profileimg']); ?>" alt="Admin Profile Image" class="profile-image">
        <?php else: ?>
            <p>No profile image uploaded.</p>
        <?php endif; ?>

        <!-- Admin Details -->
        <div class="details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['firstname']) . " " . htmlspecialchars($admin['lastname']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($admin['phone']); ?></p>
        </div>
        
        <!-- Edit icon linked to edit_admin.php -->
        <a href="edit_admin.php" class="edit-icon" title="Edit Admin Details">
            <i class="fas fa-edit"></i>
        </a>
    </div>
    
    <hr>
    
    <!-- Links for navigating to client list and logout -->
    <div class="links">
        <a href="client_list.php" class="view-clients">View Clients</a>
        <a href="#" class="logout" onclick="confirmLogout()">Logout</a>
    </div>
</div>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of your account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, log me out!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}
</script>

</body>
</html>