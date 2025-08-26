<?php
include 'config.php';

$id = $name = $phone = $spec = $email = "";
$edit_mode = false;

// Add or Update Trainer
if (isset($_POST['save'])) {
    $name  = $_POST['name'];
    $phone = $_POST['phone'];
    $spec  = $_POST['specialization'];
    $email = $_POST['email'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE trainers SET name=?, phone=?, specialization=?, email=? WHERE trainer_id=?");
        $stmt->bind_param("ssssi", $name, $phone, $spec, $email, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO trainers (name, phone, specialization, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $spec, $email);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete Trainer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM trainers WHERE trainer_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Load data for edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM trainers WHERE trainer_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $name  = $row['name'];
        $phone = $row['phone'];
        $spec  = $row['specialization'];
        $email = $row['email'];
    }
    $stmt->close();
}

// Fetch all trainers
$result = $conn->query("SELECT * FROM trainers ORDER BY trainer_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trainers</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Trainers</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="text" name="name" placeholder="Name" value="<?= $name ?>" required>
    <input type="text" name="phone" placeholder="Phone" value="<?= $phone ?>">
    <input type="text" name="specialization" placeholder="Specialization" value="<?= $spec ?>">
    <input type="email" name="email" placeholder="Email" value="<?= $email ?>" required>
    <button type="submit" name="save"><?= $edit_mode ? "Update Trainer" : "Add Trainer" ?></button>
</form>

<table border="1" cellpadding="5">
<tr>
<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Specialization</th>
<th>Email</th>
<th>Action</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?= $row['trainer_id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['phone'] ?></td>
<td><?= $row['specialization'] ?></td>
<td><?= $row['email'] ?></td>
<td>
    <a href="?edit=<?= $row['trainer_id'] ?>">Edit</a> |
    <a href="?delete=<?= $row['trainer_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>
