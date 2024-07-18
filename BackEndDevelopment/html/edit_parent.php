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
    $parent_id_pk = $_POST['parent_id_pk'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $date_of_birth = $_POST['date_of_birth'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $relationship_to_pupil = $_POST['relationship_to_pupil'];
    $pupil_name = $_POST['pupil_name'];

    // Validate input fields
    if (!preg_match("/^[a-zA-Z-' ]*$/", $firstname) ||
        !preg_match("/^[a-zA-Z-' ]*$/", $lastname) ||
        !preg_match("/^[0-9]{10}$/", $phone_number) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['update_error'] = 'Invalid input';
    } else {
        $stmt = $conn->prepare("UPDATE parents SET first_name=?, last_name=?, date_of_birth=?, address=?, phone_number=?, email=?, relationship_to_pupil=?, pupil_name=? WHERE parent_id_pk=?");
        $stmt->bind_param("ssssssssi", $firstname, $lastname, $date_of_birth, $address, $phone_number, $email, $relationship_to_pupil, $pupil_name, $parent_id_pk);

        if ($stmt->execute()) {
            $_SESSION['update_success'] = 'Parent updated successfully';
            header('Location: view_parents.php');
            exit();
        } else {
            $_SESSION['update_error'] = 'Error updating parent: ' . $stmt->error;
        }
        $stmt->close();
    }
    $conn->close();
} elseif (isset($_GET['id'])) {
    $parent_id_pk = $_GET['id'];

    $sql = "SELECT * FROM parents WHERE parent_id_pk=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parent_id_pk);
    $stmt->execute();
    $result = $stmt->get_result();
    $parent = $result->fetch_assoc();
    $stmt->close();

    if (!$parent) {
        echo "Parent not found.";
        exit;
    }
} else {
    echo "No parent ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parent/Guardian</title>
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
<!-- Edit Parent Form -->
    <div class="add_parent">
        <div class="add_parent_form" id="edit_parent_form">
            <h1>Edit Parent/Guardian</h1>
            <div id="alert-message" style="display:none; color: red; margin-bottom: 20px;"></div>
            <form action="edit_parent.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="parent_id_pk" value="<?php echo $parent['parent_id_pk']; ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo $parent['first_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo $parent['last_name']; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $parent['date_of_birth']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" value="<?php echo $parent['phone_number']; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo $parent['address']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $parent['email']; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="relationship_to_pupil">Relationship to Pupil:</label>
                        <input type="text" id="relationship_to_pupil" name="relationship_to_pupil" value="<?php echo $parent['relationship_to_pupil']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="pupil_name">Pupil Name:</label>
                        <input type="text" id="pupil_name" name="pupil_name" value="<?php echo $parent['pupil_name']; ?>" required>
                    </div>
                </div>
                <div class="form-group button-group">
                    <input type="submit" value="Update Parent/Guardian">
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
            var firstname = document.getElementById('firstname').value;
            var lastname = document.getElementById('lastname').value;
            var date_of_birth = document.getElementById('date_of_birth').value;
            var address = document.getElementById('address').value;
            var phone_number = document.getElementById('phone_number').value;
            var email = document.getElementById('email').value;
            var relationship_to_pupil = document.getElementById('relationship_to_pupil').value;
            var pupil_name = document.getElementById('pupil_name').value;

            var namePattern = /^[a-zA-Z-' ]*$/;
            var phonePattern = /^[0-9]{10}$/;
            var emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
            if (!firstname || !lastname || !date_of_birth || !address || !phone_number || !email || !relationship_to_pupil || !pupil_name) {
                displayAlert('Please fill in all fields.');
                return false;
            }
            if (!namePattern.test(firstname)) {
                displayAlert('First name can only contain letters, hyphens, apostrophes, and spaces.');
                return false;
            }
            if (!namePattern.test(lastname)) {
                displayAlert('Last name can only contain letters, hyphens, apostrophes, and spaces.');
                return false;
            }
            if (!phonePattern.test(phone_number)) {
                displayAlert('Phone number must be a 10-digit number.');
                return false;
            }
            if (!emailPattern.test(email)) {
                displayAlert('Email must be a valid email address.');
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
        if (isset($_SESSION['update_error'])) {
            echo 'displayAlert("' . $_SESSION['update_error'] . '");';
            unset($_SESSION['update_error']);
        }
        ?>
    </script>
</body>
</html>
