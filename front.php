<?php

@include 'config.php';

$message = ""; // Initialize the message
$access = 0; // Default access is not granted

if (isset($_POST['scan_card'])) {
    $tag_number = mysqli_real_escape_string($conn, $_POST['tag_number']);

    // Check if the scanned card ID exists in the "nfc_info" table and has validity = 1
    $select_card = "SELECT tag_number FROM nfc_info WHERE tag_number = '$tag_number' AND validity = 1";
    $result_card = mysqli_query($conn, $select_card);

    if (mysqli_num_rows($result_card) > 0) {
        // Card found in nfc_info and validity is 1, set access to granted
        $access = 1;
    }

    $current_time = date("H:i:s");
    $current_date = date("Y-m-d");

    // Record card access data
    $insert_query = "INSERT INTO nfc_scan (tag_number, time_entry, date_entry, access) VALUES ('$tag_number', '$current_time', '$current_date', $access)";
    mysqli_query($conn, $insert_query);

    if ($access) {
        $message = "ACCESS IS GRANTED";
    } else {
        $message = "ACCESS NOT GRANTED";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Scanner</title>

    <!-- custom css file link -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Swipe Your Card</h1>
    <form action="" method="post">
        <input type="text" name="tag_number" required placeholder="Enter card ID">
        <input type="submit" name="scan_card" value="Scan Card">
    </form>
    <p class="<?php echo ($access ? '' : 'denied'); ?>"><?php if (!empty($message)) echo $message; ?></p>
</body>
</html>

