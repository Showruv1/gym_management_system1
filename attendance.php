<?php
include 'config.php';

// Initialize variables
$id = $member_id = $card_id = $date = $check_in = $check_out = "";
$edit_mode = false;

// Insert / Update Attendance
if (isset($_POST['save'])) {
    $member_id = $_POST['member_id'];
    $card_id   = $_POST['card_id'];
    $date      = $_POST['date'] ?: date('Y-m-d');
    $check_in  = $_POST['check_in'] ?: date('H:i:s');
    $check_out = $_POST['check_out'] ?: null;

    if (!empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE attendance 
                                SET member_id=?, access_card_id=?, date=?, check_in_time=?, check_out_time=? 
                                WHERE attendance_id=?");
        $stmt->bind_param("iisssi", $member_id, $card_id, $date, $check_in, $check_out, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO attendance (member_id, access_card_id, date, check_in_time, check_out_time)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $member_id, $card_id, $date, $check_in, $check_out);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete Attendance
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM attendance WHERE attendance_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Load data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM attendance WHERE attendance_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($row = $result_edit->fetch_assoc()) {
        $member_id = $row['member_id'];
        $card_id   = $row['access_card_id'];
        $date      = $row['date'];
        $check_in  = $row['check_in_time'];
        $check_out = $row['check_out_time'];
    }
    $stmt->close();
}

// Fetch all attendance records
$result = $conn->query("SELECT * FROM attendance ORDER BY date DESC, check_in_time DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Attendance</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="number" name="member_id" placeholder="Member ID" value="<?= $member_id ?>" required>
    <input type="number" name="card_id" placeholder="Card ID" value="<?= $card_id ?>" required>
    <input type="date" name="date" value="<?= $date ?>">
    <input type="time" name="check_in" value="<?= $check_in ?>">
    <input type="time" name="check_out" value="<?= $check_out ?>">
    <button type="submit" name="save"><?= $edit_mode ? "Update Attendance" : "Mark Attendance" ?></button>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Member ID</th>
        <th>Card ID</th>
        <th>Date</th>
        <th>Check In</th>
        <th>Check Out</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['attendance_id'] ?></td>
        <td><?= $row['member_id'] ?></td>
        <td><?= $row['access_card_id'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['check_in_time'] ?></td>
        <td><?= $row['check_out_time'] ?></td>
        <td>
            <a href="?edit=<?= $row['attendance_id'] ?>">Edit</a> |
            <a href="?delete=<?= $row['attendance_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
