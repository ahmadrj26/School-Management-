<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "School_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input fields
    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'All fields are required.';
        header('Location: account.php');
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id_pk'];
            $_SESSION['username'] = $user['username'];
            header('Location: account_logged_in.php');
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid username or password.';
            header('Location: account.php');
            exit();
        }
    }
}
?>
