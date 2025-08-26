<?php
include 'config.php';

// Add Membership
if (isset($_POST['add'])) {
    $name  = $_POST['name'];
    $dur   = $_POST['duration'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO memberships (name, duration, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $name, $dur, $price);
    $stmt->execute();
    $stmt->close();
}

// Update Membership
if (isset($_POST['update'])) {
    $id    = $_POST['membership_id'];
    $name  = $_POST['name'];
    $dur   = $_POST['duration'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE memberships SET name=?, duration=?, price=? WHERE membership_id=?");
    $stmt->bind_param("sidi", $name, $dur, $price, $id);
    $stmt->execute();
    $stmt->close();
}

// Delete Membership
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM memberships WHERE membership_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch Memberships
$result = $conn->query("SELECT * FROM memberships ORDER BY membership_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memberships</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Membership Plans</h2>

<!-- Add Membership -->
<form method="POST">
    <input type="text" name="name" placeholder="Plan Name" required>
    <input type="number" name="duration" placeholder="Duration (months)" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <button type="submit" name="add">Add Membership</button>
</form>

<!-- Update Membership -->
<h3>Update Membership</h3>
<form method="POST">
    <input type="number" name="membership_id" placeholder="Membership ID" required>
    <input type="text" name="name" placeholder="New Plan Name" required>
    <input type="number" name="duration" placeholder="New Duration (months)" required>
    <input type="number" step="0.01" name="price" placeholder="New Price" required>
    <button type="submit" name="update">Update Membership</button>
</form>

<!-- Display Table -->
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Duration (Months)</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['membership_id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['duration'] ?></td>
        <td><?= $row['price'] ?></td>
        <td>
            <a href="?delete=<?= $row['membership_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
