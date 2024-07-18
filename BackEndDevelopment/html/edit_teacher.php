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

$teacher = null;

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $teacher_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE teacher_id_pk = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phonenumber = $_POST['phonenumber'];
    $annualsalary = $_POST['annualsalary'];
    $qualification = $_POST['qualification'];
    $dateofbirth = $_POST['dateofbirth'];
    $classid_fk = $_POST['classid_fk'];
    if (!preg_match("/^[a-zA-Z-' ]*$/", $firstname) || 
        !preg_match("/^[a-zA-Z-' ]*$/", $lastname) || 
        !preg_match("/^[0-9]{10}$/", $phonenumber) || 
        !is_numeric($annualsalary) || 
        !preg_match("/^[a-zA-Z-' ]*$/", $qualification)) {
        $_SESSION['teacher_edited'] = 'invalid_input';
    } else {
        $stmt = $conn->prepare("UPDATE teachers SET first_name = ?, last_name = ?, address = ?, phone_number = ?, annual_salary = ?, qualification = ?, date_of_birth = ?, class_id_fk = ? WHERE teacher_id_pk = ?");
        $stmt->bind_param("ssssdssii", $firstname, $lastname, $address, $phonenumber, $annualsalary, $qualification, $dateofbirth, $classid_fk, $teacher_id);

        if ($stmt->execute()) {
            header("Location: view_teachers.php");
            exit();
        } else {
            $_SESSION['teacher_edited'] = 'error';
        }

        $stmt->close();
    }

    $conn->close();
    header("Location: edit_teacher.php?id=" . $teacher_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher - Rishton Academy</title>
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
<!-- Edit Teacher Form -->
    <div class="edit_teacher">
        <div class="edit_teacher_form" id="edit_teacher_form">
            <h1>Edit Teacher</h1>
            <div id="alert-message" style="display:none; color: red; margin-bottom: 20px;"></div>
            <form id="teacherForm" action="edit_teacher.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($teacher['teacher_id_pk']); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($teacher['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($teacher['last_name']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phonenumber">Phone Number:</label>
                        <input type="text" id="phonenumber" name="phonenumber" value="<?php echo htmlspecialchars($teacher['phone_number']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($teacher['address']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="annualsalary">Annual Salary:</label>
                        <input type="text" id="annualsalary" name="annualsalary" value="<?php echo htmlspecialchars($teacher['annual_salary']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="qualification">Qualification:</label>
                        <input type="text" id="qualification" name="qualification" value="<?php echo htmlspecialchars($teacher['qualification']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="dateofbirth">Date of Birth:</label>
                        <input type="date" id="dateofbirth" name="dateofbirth" value="<?php echo htmlspecialchars($teacher['date_of_birth']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="classid_fk">Class:</label>
                        <select id="classid_fk" name="classid_fk" required>
                            <option value="">Select Class</option>
                            <?php
                            $classQuery = "SELECT class_id_pk, class_name FROM classes WHERE class_id_pk = ? OR class_id_pk NOT IN (SELECT class_id_fk FROM teachers WHERE class_id_fk IS NOT NULL)";
                            $stmt = $conn->prepare($classQuery);
                            $stmt->bind_param("i", $teacher['class_id_fk']);
                            $stmt->execute();
                            $classResult = $stmt->get_result();

                            while ($class = $classResult->fetch_assoc()) {
                                $selected = $class['class_id_pk'] == $teacher['class_id_fk'] ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($class['class_id_pk']) . "' $selected>" . htmlspecialchars($class['class_name']) . "</option>";
                            }
                            $stmt->close();
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group button-group">
                    <input type="submit" value="Update Teacher">
                </div>
            </form>
        </div>
    </div>
<!-- JavaScript for Form Validation -->
    <script>
        function validateForm() {
            var firstname = document.getElementById('firstname').value;
            var lastname = document.getElementById('lastname').value;
            var address = document.getElementById('address').value;
            var phonenumber = document.getElementById('phonenumber').value;
            var annualsalary = document.getElementById('annualsalary').value;
            var qualification = document.getElementById('qualification').value;
            var dateofbirth = document.getElementById('dateofbirth').value;
            var classid_fk = document.getElementById('classid_fk').value;

            var namePattern = /^[a-zA-Z-' ]*$/;
            var phonePattern = /^[0-9]{10}$/;
            var salaryPattern = /^[0-9]*\.?[0-9]+$/;
            var qualificationPattern = /^[a-zA-Z-' ]*$/;
            if (!firstname || !lastname || !address || !phonenumber || !annualsalary || !qualification || !dateofbirth || !classid_fk) {
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
            if (!phonePattern.test(phonenumber)) {
                displayAlert('Phone number must be a 10-digit number.');
                return false;
            }
            if (!salaryPattern.test(annualsalary)) {
                displayAlert('Annual salary must be a valid number.');
                return false;
            }
            if (!qualificationPattern.test(qualification)) {
                displayAlert('Qualification can only contain letters, hyphens, apostrophes, and spaces.');
                return false;
            }

            return true;
        }

        function displayAlert(message) {
            var alertMessage = document.getElementById('alert-message');
            alertMessage.style.display = 'block';
            alertMessage.innerHTML = message;
        }

        $(document).ready(function() {
            $('#teacherForm').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">&copy; 2024 Rishton Academy Primary School. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>