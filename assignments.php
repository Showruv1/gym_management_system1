<?php
include 'config.php';

$id = $mid = $tid = "";
$edit_mode = false;

// Add or Update Assignment
if (isset($_POST['save'])) {
    $mid = $_POST['member_id'];
    $tid = $_POST['trainer_id'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE trainer_assignments SET member_id=?, trainer_id=? WHERE assignment_id=?");
        $stmt->bind_param("iii", $mid, $tid, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO trainer_assignments (member_id, trainer_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $mid, $tid);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete Assignment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM trainer_assignments WHERE assignment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Load for edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM trainer_assignments WHERE assignment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $mid = $row['member_id'];
        $tid = $row['trainer_id'];
    }
    $stmt->close();
}

// Fetch all assignments
$result = $conn->query("SELECT * FROM trainer_assignments ORDER BY assignment_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trainer Assignments</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Trainer Assignments</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="number" name="member_id" placeholder="Member ID" value="<?= $mid ?>" required>
    <input type="number" name="trainer_id" placeholder="Trainer ID" value="<?= $tid ?>" required>
    <button type="submit" name="save"><?= $edit_mode ? "Update Assignment" : "Assign Trainer" ?></button>
</form>

<table border="1" cellpadding="5">
<tr>
<th>ID</th>
<th>Member ID</th>
<th>Trainer ID</th>
<th>Action</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?= $row['assignment_id'] ?></td>
<td><?= $row['member_id'] ?></td>
<td><?= $row['trainer_id'] ?></td>
<td>
    <a href="?edit=<?= $row['assignment_id'] ?>">Edit</a> |
    <a href="?delete=<?= $row['assignment_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>
