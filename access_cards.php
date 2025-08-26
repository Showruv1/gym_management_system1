<?php
include 'config.php';

// Initialize variables
$id = $member_id = $status = "";
$edit_mode = false;

// Add or Update Card
if (isset($_POST['save'])) {
    $member_id = $_POST['member_id'];
    $status    = $_POST['status'];

    if (!empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE access_cards SET member_id=?, status=? WHERE access_card_id=?");
        $stmt->bind_param("isi", $member_id, $status, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO access_cards (member_id, status) VALUES (?, ?)");
        $stmt->bind_param("is", $member_id, $status);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete Card
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM access_cards WHERE access_card_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Load Card data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM access_cards WHERE access_card_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $member_id = $row['member_id'];
        $status    = $row['status'];
    }
    $stmt->close();
}

// Fetch all access cards
$result = $conn->query("SELECT * FROM access_cards ORDER BY access_card_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Cards</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Access Cards</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="number" name="member_id" placeholder="Member ID" value="<?= $member_id ?>" required>
    <select name="status" required>
        <option value="">Select Status</option>
        <option value="Active"   <?= ($status=='Active')?'selected':'' ?>>Active</option>
        <option value="Inactive" <?= ($status=='Inactive')?'selected':'' ?>>Inactive</option>
    </select>
    <button type="submit" name="save"><?= $edit_mode ? "Update Card" : "Issue Card" ?></button>
</form>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Member ID</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?= $row['access_card_id'] ?></td>
    <td><?= $row['member_id'] ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <a href="?edit=<?= $row['access_card_id'] ?>">Edit</a> |
        <a href="?delete=<?= $row['access_card_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
