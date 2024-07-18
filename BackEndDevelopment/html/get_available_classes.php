<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "School_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT class_id_pk, class_name FROM classes WHERE class_id_pk NOT IN (SELECT class_id_fk FROM teachers)";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['class_id_pk'] . "'>" . $row['class_name'] . "</option>";
    }
} else {
    echo "<option value=''>No classes left to assign</option>";
}
$conn->close();
?>
