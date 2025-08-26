<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        header { background: #333; color: #fff; padding: 20px; text-align: center; }
        ul { list-style: none; padding: 0; margin: 20px auto; max-width: 400px; }
        li { margin: 10px 0; }
        a { text-decoration: none; color: #333; background: #fff; padding: 10px 15px; display: block; border-radius: 5px; border: 1px solid #ccc; text-align: center; }
        a:hover { background: #ddd; }
    </style>
</head>
<body>

<header>
    <h1>Welcome to Gym Management System</h1>
</header>

<ul>
    <li><a href="members.php">Manage Members</a></li>
    <li><a href="trainers.php">Manage Trainers</a></li>
    <li><a href="memberships.php">Manage Memberships</a></li>
    <li><a href="payments.php">Manage Payments</a></li>
    <li><a href="assignments.php">Trainer Assignments</a></li>
    <li><a href="access_cards.php">Access Cards</a></li>
    <li><a href="attendance.php">Attendance</a></li>
</ul>

</body>
</html>
