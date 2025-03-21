<?php
session_start(); 

$conn = new mysqli("localhost", "root", "", "contactpage");

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_message"])) {
    $contact_id = $_POST["contact_id"];

    $stmt = $conn->prepare("DELETE FROM contact_table WHERE Contact_Id = ?");
    $stmt->bind_param("i", $contact_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Message Deleted Successfully!";
    } else {
        $_SESSION['error_message'] = "Error! Please try again.";
    }

    $stmt->close();
    $conn->close();

    header("Location: admin_messages.php");
    exit();
}

$result = $conn->query("SELECT * FROM contact_table ORDER BY Contact_Id DESC");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Messages</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            color: #000;
            text-align: center;
            padding: 20px;
        }
        h2{
            font-size:3rem;
        }
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            background: #f9f9f9;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #000;
            color: #fff;
            text-align:center;
        }
        tr:hover {
            background: rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        button {
            background: #000;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            border-radius: 20px;
            font-size: 14px;
            width: 120px;
        }
        button:hover {
            background: #444;
            transform: scale(1.05);
        }
        .btn-danger {
            background: red;
        }
    </style>
</head>
<body>
    <h2>All Messages</h2>
    <table>
        <tr>
            <th>Contact ID</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row["Contact_Id"]; ?></td>
                <td><?php echo $row["E_Mail"]; ?></td>
                <td><?php echo $row["Subject"]; ?></td>
                <td><?php echo $row["Message"]; ?></td>
                <td>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="contact_id" value="<?php echo $row["Contact_Id"]; ?>">
                        <button type="submit" name="delete_message" class="btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
