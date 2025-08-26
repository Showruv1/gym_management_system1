<?php
include 'config.php';

// Initialize variables
$id = $name = $age = $gender = $email = $phone = "";
$edit_mode = false;

// Add or Update Member
if (isset($_POST['save'])) {
    $name   = $_POST['name'];
    $age    = $_POST['age'];
    $gender = $_POST['gender'];
    $email  = $_POST['email'];
    $phone  = $_POST['phone'];

    // Update
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE members SET name=?, age=?, gender=?, email=?, phone=? WHERE member_id=?");
        $stmt->bind_param("sisssi", $name, $age, $gender, $email, $phone, $id);
        $stmt->execute();

    // নতুন member এর ID বের করা
    $new_member_id = $conn->insert_id;

    // Access card function কল করা
    $sql = "SELECT generate_access_card($new_member_id) AS card_no";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $card_no = $row['card_no'];
        echo "<p style='color:green;'>Access card generated: $card_no</p>";
    }
        $stmt->close();
    } else { // Add
        $stmt = $conn->prepare("INSERT INTO members (name, age, gender, email, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $name, $age, $gender, $email, $phone);
        $stmt->execute();

    // নতুন member এর ID বের করা
    $new_member_id = $conn->insert_id;

    // Access card function কল করা
    $sql = "SELECT generate_access_card($new_member_id) AS card_no";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $card_no = $row['card_no'];
        echo "<p style='color:green;'>Access card generated: $card_no</p>";
    }
        $stmt->close();
    }
}

// Delete Member
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM members WHERE member_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // নতুন member এর ID বের করা
    $new_member_id = $conn->insert_id;

    // Access card function কল করা
    $sql = "SELECT generate_access_card($new_member_id) AS card_no";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $card_no = $row['card_no'];
        echo "<p style='color:green;'>Access card generated: $card_no</p>";
    }
    $stmt->close();
}

// Load member data for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_mode = true;
    $stmt = $conn->prepare("SELECT * FROM members WHERE member_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // নতুন member এর ID বের করা
    $new_member_id = $conn->insert_id;

    // Access card function কল করা
    $sql = "SELECT generate_access_card($new_member_id) AS card_no";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $card_no = $row['card_no'];
        echo "<p style='color:green;'>Access card generated: $card_no</p>";
    }
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $name   = $row['name'];
        $age    = $row['age'];
        $gender = $row['gender'];
        $email  = $row['email'];
        $phone  = $row['phone'];
    }
    $stmt->close();
}

// Fetch all members
$result = $conn->query("SELECT * FROM members ORDER BY member_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Members</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Members</h2>

<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="text" name="name" placeholder="Name" value="<?= $name ?>" required>
    <input type="number" name="age" placeholder="Age" value="<?= $age ?>">
    <select name="gender">
        <option value="">Select Gender</option>
        <option value="Male"   <?= ($gender=='Male')?'selected':'' ?>>Male</option>
        <option value="Female" <?= ($gender=='Female')?'selected':'' ?>>Female</option>
        <option value="Other"  <?= ($gender=='Other')?'selected':'' ?>>Other</option>
    </select>
    <input type="email" name="email" placeholder="Email" value="<?= $email ?>" required>
    <input type="text" name="phone" placeholder="Phone" value="<?= $phone ?>" required>
    <button type="submit" name="save"><?= $edit_mode ? "Update Member" : "Add Member" ?></button>
</form>

<table border="1" cellpadding="5">
<tr>
<th>ID</th>
<th>Name</th>
<th>Age</th>
<th>Gender</th>
<th>Email</th>
<th>Phone</th>
<th>Action</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?= $row['member_id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['age'] ?></td>
<td><?= $row['gender'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['phone'] ?></td>
<td>
    <a href="?edit=<?= $row['member_id'] ?>">Edit</a> |
    <a href="?delete=<?= $row['member_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>