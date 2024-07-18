<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "School_management";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $delete_id = $_POST['delete_id'];
    $conn->query("SET FOREIGN_KEY_CHECKS=0");
    $delete_book_sql = "DELETE FROM Library_books WHERE Book_id_pk=?";
    $stmt = $conn->prepare($delete_book_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $conn->query("SET FOREIGN_KEY_CHECKS=1");

    $conn->close();
}
?>
