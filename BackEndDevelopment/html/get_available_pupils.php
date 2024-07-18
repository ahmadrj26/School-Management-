<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "School_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT pupilid_pk, firstname, lastname FROM Pupils WHERE pupilid_pk NOT IN (SELECT pupilid_fk FROM PupilsParents)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['pupilid_pk'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
    }
} else {
    echo "<option value=''>No pupils available</option>";
}

$conn->close();
?>
