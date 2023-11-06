<?php
// Include the configuration file to establish a database connection
include 'config.php';

// Function to fetch all records from the database
function fetchAllRecords() {
    global $conn;
    $query = "SELECT * FROM nfc_info";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Function to add a new record to the database
function addRecord($tag_number, $user_id, $validity, $till) {
    global $conn;
    $query = "INSERT INTO nfc_info (tag_number, user_id, validity, till) VALUES ('$tag_number', '$user_id', '$validity', '$till')";
    return mysqli_query($conn, $query);
}

// Function to delete a record from the database
function deleteRecord($id) {
    global $conn;
    $query = "DELETE FROM nfc_info WHERE id = $id";
    return mysqli_query($conn, $query);
}

// Function to update a record in the database
function updateRecord($id, $tag_number, $user_id, $validity, $till) {
    global $conn;
    $query = "UPDATE nfc_info SET tag_number = '$tag_number', user_id = '$user_id', validity = '$validity', till = '$till' WHERE id = $id";
    return mysqli_query($conn, $query);
}

if (isset($_POST['add'])) {
    $tag_number = mysqli_real_escape_string($conn, $_POST['tag_number']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $validity = $_POST['validity'] == '1' ? 1 : 0;
    $till = mysqli_real_escape_string($conn, $_POST['till']);

    addRecord($tag_number, $user_id, $validity, $till);
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    deleteRecord($id);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tag_number = mysqli_real_escape_string($conn, $_POST['tag_number']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $validity = $_POST['validity'] == '1' ? 1 : 0;
    $till = mysqli_real_escape_string($conn, $_POST['till']);

    updateRecord($id, $tag_number, $user_id, $validity, $till);
}

$records = fetchAllRecords();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Database Viewer</title>
    <!-- custom css file link -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>MySQL Database Viewer</h1>

    <!-- Add a new record form -->
    <h2>Add New Record</h2>
    <form method="POST">
        <input type="text" name="tag_number" placeholder="Tag Number" required>
        <input type="text" name="user_id" placeholder="User ID" required>
        <label for="validity">Valid:</label>
        <input type="checkbox" name="validity" value="1" id="validity">
        <input type="date" name="till" required>
        <button type="submit" name="add">Add Record</button>
    </form>

    <!-- Display records from the database -->
    <h2>Records</h2>
    <table>
        <tr>
            <th>Tag Number</th>
            <th>User ID</th>
            <th>Valid</th>
            <th>Till</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($records as $record) : ?>
            <tr>
                <td><?= $record['tag_number'] ?></td>
                <td><?= $record['user_id'] ?></td>
                <td><?= $record['validity'] == 1 ? 'Yes' : 'No' ?></td>
                <td><?= $record['till'] ?></td>
                <td>
                    <form method="POST">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                    <form method="POST">
                        <input type="text" name="tag_number" placeholder="Tag Number" required>
                        <input type="text" name="user_id" placeholder="User ID" required>
                        <label for="validity">Valid:</label>
                        <input type="checkbox" name="validity" value="1" id="validity">
                        <input type="date" name="till" required>
                        <button type="submit" name="update">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
