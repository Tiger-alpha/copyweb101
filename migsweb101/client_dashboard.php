<?php
// Start Session
session_start();

// Include database connection
include('db/connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'client') {
    header("Location: index.php");
    exit();
}

// retrieve client data from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

// check if client data exists
if (!$client) {
    echo "Client not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
          body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
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
        .profile-card {
            position: relative;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .edit-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #007bff;
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s, transform 0.3s; 
        }
        .edit-icon:hover {
            color: #0056b3; 
            transform: scale(1.1);
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 15px auto;
            display: block;
        }
        .details {
            margin-top: 15px;
            text-align: left;
        }
        .details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .logout-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s;
        }
        .logout-link:hover {
            background-color: red;
            transform: translateY(-2px);
        }
        .logout-link:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="profile-card">
        <!-- Edit Icon -->
        <a href="edit_profile.php" class="edit-icon" title="Edit Profile">
            <i class="fas fa-edit"></i>
        </a>

        <!-- Welcome Message -->
        <h2>Welcome Client, <?php echo htmlspecialchars($client['username']); ?></h2>

        <!-- Profile Image -->
        <?php if (!empty($client['profileimg'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($client['profileimg']); ?>" alt="Profile Image" class="profile-image">
        <?php else: ?>
            <p>No profile image uploaded.</p>
        <?php endif; ?>

        <!-- Client Details -->
        <div class="details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($client['firstname']) . " " . htmlspecialchars($client['lastname']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($client['phone']); ?></p>
        </div>
    </div>

    <!-- Logout Link with SweetAlert Confirmation -->
    <a href="#" class="logout-link" onclick="confirmLogout()">Logout</a>
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