<?php
// Start session
session_start();

// Include database connection
include('db/connection.php');

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$table_name = '';
$table_created = false;
$existing_tables = [];

// Fetch all table names from the database
$result = $conn->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $existing_tables[] = $row['Tables_in_' . $conn->query('SELECT DATABASE()')->fetch_row()[0]]; // Fetch table names
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table_name = trim($_POST['table_name']);
    $selected_table = $_POST['existing_table'] ?? '';
    
    if (!empty($table_name) && !empty($selected_table)) {
        // sanitize table name (basic sanitization to prevent SQL injection)
        $table_name = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);

        // check if the table already exists
        if (in_array($table_name, $existing_tables)) {
            // if the table already exists, set an error message
            $table_created = false;
            $error_message = "The table '$table_name' already exists.";
        } else {
            // get columns of the selected existing table
            $columns_result = $conn->query("DESCRIBE `$selected_table`");
            if ($columns_result) {
                $columns = [];
                while ($column = $columns_result->fetch_assoc()) {
                    if (strtolower($column['Field']) != 'id') {  // Skip 'id', as it will be auto-generated
                        $columns[] = "`" . $column['Field'] . "` " . $column['Type'];
                    }
                }
                
                // query to create a new table with the same columns as the selected table (without copying data)
                $create_query = "CREATE TABLE `$table_name` (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    " . implode(', ', $columns) . ",
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";

                if ($conn->query($create_query) === TRUE) {
                    // successfully created the table
                    $table_created = true;
                } else {
                    $table_created = false;
                    $error_message = "Error creating table: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Table</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
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
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input[type="text"], select {
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            width: 70%;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
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
    <h2>Add New Table with Same Structure</h2>

    <form action="add_new_table.php" method="POST">
        <!-- New Table Name -->
        <input type="text" name="table_name" placeholder="Enter New Table Name" value="<?php echo htmlspecialchars($table_name); ?>" required>
        
        <!-- Select Existing Table to Copy Structure From -->
        <select name="existing_table" required>
            <option value="" disabled selected>Select Existing Table</option>
            <?php foreach ($existing_tables as $existing_table): ?>
                <option value="<?php echo $existing_table; ?>"><?php echo $existing_table; ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Submit Button -->
        <input type="submit" value="Create Table">
    </form>

    <?php if ($table_created): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: "Table '<?php echo htmlspecialchars($table_name); ?>' created successfully with structure copied from '<?php echo htmlspecialchars($selected_table); ?>'.",
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
            }).then(function() {
                window.location.href = 'client_list.php'; 
            });
        </script>
    <?php elseif (isset($error_message)): ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: "<?php echo $error_message; ?>",
                icon: 'error',
                timer: 1000,
                showConfirmButton: false  
            }).then(function() {
                window.location.href = 'client_list.php'; 
            });
        </script>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !$table_created): ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: "There was an error creating the table. Please try again.",
                icon: 'error',
                timer: 1000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <a href="client_list.php" class="back-btn">Back to Client List</a>
</div>

</body>
</html>
