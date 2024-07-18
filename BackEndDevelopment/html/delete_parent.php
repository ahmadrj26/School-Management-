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
    $conn->begin_transaction();

    try {
        $delete_pupil_parent_sql = "DELETE FROM Pupils_Parents WHERE parent_id_fk=?";
        $stmt1 = $conn->prepare($delete_pupil_parent_sql);
        $stmt1->bind_param("i", $delete_id);
        $stmt1->execute();
        $stmt1->close();
        $delete_parent_sql = "DELETE FROM parents WHERE parent_id_pk=?";
        $stmt2 = $conn->prepare($delete_parent_sql);
        $stmt2->bind_param("i", $delete_id);
        $stmt2->execute();
        $stmt2->close();
        $conn->commit();

        echo 'success';
    } catch (Exception $e) {
        $conn->rollback();
        echo 'error';
    }

    $conn->close();
}
?>
