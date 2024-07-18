<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "School_management";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $pupil_id_pk = $_POST['pupil_id_pk'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $medical_information = $_POST['medical_information'];
    $class_id_fk = $_POST['class_id_fk'];

    // Input validation
    if (!preg_match("/^[a-zA-Z-' ]*$/", $first_name) || 
        !preg_match("/^[a-zA-Z-' ]*$/", $last_name) || 
        !preg_match("/^[a-zA-Z0-9-' ]*$/", $medical_information) ||
        empty($address)) {
        $_SESSION['update_error'] = 'Invalid input. Please correct the highlighted fields.';
    } else {
        $stmt = $conn->prepare("UPDATE pupils SET first_name=?, last_name=?, date_of_birth=?, address=?, medical_information=?, class_id_fk=? WHERE pupil_id_pk=?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $date_of_birth, $address, $medical_information, $class_id_fk, $pupil_id_pk);

        if ($stmt->execute()) {
            $_SESSION['update_success'] = 'Pupil updated successfully';
            header('Location: view_pupils.php');
            exit;
        } else {
            $_SESSION['update_error'] = 'Error: ' . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
} elseif (isset($_GET['id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "School_management";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $pupil_id_pk = $_GET['id'];

    $sql = "SELECT * FROM pupils WHERE pupil_id_pk=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pupil_id_pk);
    $stmt->execute();
    $result = $stmt->get_result();
    $pupil = $result->fetch_assoc();

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pupil</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<!-- Header -->
<div class="header">
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <img src="logo3.png" width="55" height="60" alt="company's-logo">
            </div>
            <nav>
                <ul id="MenuItems">
                    <li><a href="index.php">Home</a></li>
                    <li class="dropdown">
                        <a href="">Pupils<i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="add_pupil.php">Add Pupil</a></li>
                            <li><a href="view_pupils.php">View All Pupils</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="">Teachers <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="add_teacher.php">Add Teacher</a></li>
                            <li><a href="view_teachers.php">View All Teachers</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="">Classes <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="reception_year.php">Reception Year</a></li>
                            <li><a href="year_one.php">Year One</a></li>
                            <li><a href="year_two.php">Year Two</a></li>
                            <li><a href="year_three.php">Year Three</a></li>
                            <li><a href="year_four.php">Year Four</a></li>
                            <li><a href="year_five.php">Year Five</a></li>
                            <li><a href="year_six.php">Year Six</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="">Parents/Guardians <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="add_parent.php">Add Parent/Guardian</a></li>
                            <li><a href="view_parents.php">View All Parents/Guardians</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="">Teaching Assistants <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="add_teaching_assistant.php">Add Teaching Assistant</a></li>
                            <li><a href="view_teaching_assistants.php">View All Teaching Assistants</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="">Library <i class="fas fa-caret-down"></i></a>
                        <ul class="dropdown-content">
                            <li><a href="add_book.php">Add Book</a></li>
                            <li><a href="view_books.php">View All Books</a></li>
                            <li><a href="issue_book.php">Issue Book To Student</a></li>
                            <li><a href="view_issued_books.php">View All Issued Books</a></li>
                        </ul>
                    </li>
                    <li class="account">
                        <a href="account.php"><i class="fas fa-user"></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- Main Content -->
    <div class="add_pupil">
        <div class="add_pupil_form" id="edit_pupil_form">
            <h1>Edit Pupil</h1>
            <div id="alert-message" style="display:none; color: red; margin-bottom: 20px;">
                <?php
                if (isset($_SESSION['update_error'])) {
                    echo $_SESSION['update_error'];
                    unset($_SESSION['update_error']);
                }
                if (isset($_SESSION['update_success'])) {
                    echo $_SESSION['update_success'];
                    unset($_SESSION['update_success']);
                }
                ?>
            </div>
            <form action="edit_pupil.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="pupil_id_pk" value="<?php echo $pupil['pupil_id_pk']; ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo $pupil['first_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo $pupil['last_name']; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $pupil['date_of_birth']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo $pupil['address']; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="medical_information">Medical Information:</label>
                        <input type="text" id="medical_information" name="medical_information" value="<?php echo $pupil['medical_information']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="class_id_fk">Class:</label>
                        <select id="class_id_fk" name="class_id_fk" required>
                            <option value="">Select Class</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            $result = $conn->query("SELECT class_id_pk, class_name FROM classes");
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['class_id_pk'] . '" ' . ($pupil['class_id_fk'] == $row['class_id_pk'] ? 'selected' : '') . '>' . $row['class_name'] . '</option>';
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group button-group">
                    <input type="submit" value="Update Pupil">
                </div>
            </form>
        </div>
    </div>
<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="footer-text">&copy; 2024 Rishton Academy Primary School. All Rights Reserved.</p>
    </div>
 </footer>
 <!-- JavaScript for Form Validation -->
    <script>
        function validateForm() {
            var first_name = document.getElementById('first_name').value;
            var last_name = document.getElementById('last_name').value;
            var date_of_birth = document.getElementById('date_of_birth').value;
            var address = document.getElementById('address').value;
            var medical_information = document.getElementById('medical_information').value;
            var class_id_fk = document.getElementById('class_id_fk').value;
            var namePattern = /^[a-zA-Z-' ]*$/;
            var medicalPattern = /^[a-zA-Z0-9-' ]*$/;
            if (!first_name || !last_name || !date_of_birth || !address || !medical_information || !class_id_fk) {
                displayAlert('Please fill in all fields.');
                return false;
            }
            if (!namePattern.test(first_name)) {
                displayAlert('First name can only contain letters, hyphens, apostrophes, and spaces.');
                return false;
            }
            if (!namePattern.test(last_name)) {
                displayAlert('Last name can only contain letters, hyphens, apostrophes, and spaces.');
                return false;
            }
            if (!medicalPattern.test(medical_information)) {
                displayAlert('Medical information can only contain letters, numbers, hyphens, apostrophes, and spaces.');
                return false;
            }

            return true;
        }

        function displayAlert(message) {
            var alertMessage = document.getElementById('alert-message');
            alertMessage.style.display = 'block';
            alertMessage.innerHTML = message;
        }

        <?php
        if (isset($_SESSION['update_success'])) {
            echo 'displayAlert("Pupil updated successfully.");';
            unset($_SESSION['update_success']);
        }
        ?>
</script>
</body>
</html>
