<?php
include 'config.php';

$id = $mid = $amount = $date = "";
$edit_mode = false;

// Add or Update Payment
if (isset($_POST['save'])) {
    $mid    = $_POST['member_id'];
    $amount = $_POST['amount'];
    $date   = $_POST['date'] ?: date('Y-m-d');

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE payments SET member_id=?, amount=?, payment_date=? WHERE payment_id=?");
        $stmt->bind_param("idsi", $mid, $amount, $date, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO payments (member_id, amount, payment_date) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $mid, $amount, $date);
        $stmt->execute();
        $stmt->close();
    }
}

// Delete Payment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM payments WHERE payment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Load for edit
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM payments WHERE payment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $mid    = $row['member_id'];
        $amount = $row['amount'];
        $date   = $row['payment_date'];
    }
    $stmt->close();
}

// Fetch all payments
$result = $conn->query("SELECT * FROM payments ORDER BY payment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payments</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Payments</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="number" name="member_id" placeholder="Member ID" value="<?= $mid ?>" required>
    <input type="number" step="0.01" name="amount" placeholder="Amount" value="<?= $amount ?>" required>
    <input type="date" name="date" value="<?= $date ?>">
    <button type="submit" name="save"><?= $edit_mode ? "Update Payment" : "Add Payment" ?></button>
</form>

<table border="1" cellpadding="5">
<tr>
<th>ID</th>
<th>Member ID</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?= $row['payment_id'] ?></td>
<td><?= $row['member_id'] ?></td>
<td><?= $row['amount'] ?></td>
<td><?= $row['payment_date'] ?></td>
<td>
    <a href="?edit=<?= $row['payment_id'] ?>">Edit</a> |
    <a href="?delete=<?= $row['payment_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>
