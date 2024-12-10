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

$search_query = '';
$per_page = 5; // number of records per page

// get the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;
$search_field = isset($_GET['search_field']) ? $_GET['search_field'] : 'username'; // Default search field

// fetch all tables in the database
$table_result = $conn->query("SHOW TABLES");
$tables = [];
while ($row = $table_result->fetch_row()) {
    $tables[] = $row[0]; // get each table name
}

// default selected table
$selected_table = isset($_GET['table_name']) ? $_GET['table_name'] : $tables[0];

// fetch columns for the selected table
$columns_result = $conn->query("DESCRIBE `$selected_table`");
$columns = [];
while ($col = $columns_result->fetch_assoc()) {
    $columns[] = $col['Field']; // store column names as they are
}

// check if a search query is submitted
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM `$selected_table` WHERE $search_field LIKE '%$search_query%' AND role != 'admin' LIMIT $offset, $per_page";
} else {
    $sql = "SELECT * FROM `$selected_table` WHERE role != 'admin' LIMIT $offset, $per_page";
}

$result = $conn->query($sql);

// get total records for pagination
$total_sql = "SELECT COUNT(*) AS total FROM `$selected_table` WHERE role != 'admin'";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tables List</title>
    <link rel="stylesheet" href="client_list.css">
</head>
<body>

<div class="container">
    <h2>Tables List</h2>

    <!-- Dropdown for selecting a table -->
    <form action="" method="get">
        <select name="table_name" onchange="this.form.submit()">
            <option value="" disabled selected>Select Table</option>
            <?php foreach ($tables as $table): ?>
                <option value="<?php echo $table; ?>" <?php echo (isset($_GET['table_name']) && $_GET['table_name'] == $table) ? 'selected' : ''; ?>><?php echo $table; ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="search" placeholder="Search" value="<?php echo $search_query; ?>">
        <select name="search_field">
            <option value="username" <?php echo ($search_field == 'username') ? 'selected' : ''; ?>>Username</option>
            <option value="firstname" <?php echo ($search_field == 'firstname') ? 'selected' : ''; ?>>Firstname</option>
            <option value="lastname" <?php echo ($search_field == 'lastname') ? 'selected' : ''; ?>>Lastname</option>
            <option value="email" <?php echo ($search_field == 'email') ? 'selected' : ''; ?>>Email</option>
            <option value="phone" <?php echo ($search_field == 'phone') ? 'selected' : ''; ?>>Phone</option>
        </select>
        <input type="submit" value="Search">
    </form>

    <table>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            $count = $offset + 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>$count</td>";
                echo "<td>" . (isset($row['username']) ? $row['username'] : '') . "</td>";
                echo "<td>" . (isset($row['firstname']) ? $row['firstname'] : '') . "</td>";
                echo "<td>" . (isset($row['lastname']) ? $row['lastname'] : '') . "</td>";
                echo "<td>" . (isset($row['email']) ? $row['email'] : '') . "</td>";
                echo "<td>" . (isset($row['phone']) ? $row['phone'] : '') . "</td>";
                echo "<td>" . (isset($row['role']) ? $row['role'] : '') . "</td>";

                // Dynamically check if 'ID' exists in any case
                $id_column = null;
                foreach ($columns as $column) {
                    if (strtolower($column) == 'id') {
                        $id_column = $column;
                        break;
                    }
                }

                // If ID column is found, create the link
                if ($id_column && isset($row[$id_column])) {
                    echo "<td><a href='view_client.php?ID=" . $row[$id_column] . "'>View</a></td>";
                } else {
                    echo "<td>No ID found</td>";
                }
                
                echo "</tr>";
                $count++;
            }
        } else {
            echo "<tr><td colspan='8'>No clients found.</td></tr>";
        }
        ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1&search=<?php echo $search_query; ?>&search_field=<?php echo $search_field; ?>&table_name=<?php echo $selected_table; ?>">First</a>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search_query; ?>&search_field=<?php echo $search_field; ?>&table_name=<?php echo $selected_table; ?>">Prev</a>
        <?php endif; ?>

        <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search_query; ?>&search_field=<?php echo $search_field; ?>&table_name=<?php echo $selected_table; ?>">Next</a>
            <a href="?page=<?php echo $total_pages; ?>&search=<?php echo $search_query; ?>&search_field=<?php echo $search_field; ?>&table_name=<?php echo $selected_table; ?>">Last</a>
        <?php endif; ?>
    </div>

    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    <a href="add_new_table.php" class="add-table-btn">Add New Table</a>
</div>

</body>
</html>
