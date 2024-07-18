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
    if (isset($_POST['delete_parent'])) {
        $parent_id = $_POST['parent_id'];
        $delete_sql = "DELETE FROM parents WHERE parent_id_pk = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $parent_id);
        if ($delete_stmt->execute()) {
            $_SESSION['parent_deleted'] = 'success';
        } else {
            $_SESSION['parent_deleted'] = 'error';
        }
        $delete_stmt->close();
    } else {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $date_of_birth = $_POST['date_of_birth'];
        $address = $_POST['address'];
        $phonenumber = $_POST['phonenumber'];
        $email = $_POST['email'];
        $relationship_to_pupil = $_POST['relationship_to_pupil'];
        $pupilid_fk = $_POST['pupilid_fk'];
        // Input validation
        if (!preg_match("/^[a-zA-Z-' ]*$/", $firstname) ||
            !preg_match("/^[a-zA-Z-' ]*$/", $lastname) ||
            !preg_match("/^[0-9]{10}$/", $phonenumber) ||
            !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['parent_added'] = 'invalid_input';
        } else {
            $sql = "SELECT parent_id_fk FROM Pupils_Parents WHERE pupil_id_fk = $pupilid_fk";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if (!is_null($row['parent_id_fk'])) {
                $_SESSION['parent_added'] = 'pupil_has_parent';
            } else {
                $sql = "SELECT CONCAT(first_name, ' ', last_name) AS pupil_name FROM pupils WHERE pupil_id_pk = $pupilid_fk";
                $result = $conn->query($sql);
                $pupil = $result->fetch_assoc();
                $pupil_name = $pupil['pupil_name'];
                $stmt = $conn->prepare("INSERT INTO parents (first_name, last_name, date_of_birth, address, phone_number, email, relationship_to_pupil, pupil_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $firstname, $lastname, $date_of_birth, $address, $phonenumber, $email, $relationship_to_pupil, $pupil_name);

                if ($stmt->execute()) {
                    $parent_id_pk = $stmt->insert_id;
                    $update_pupil = $conn->prepare("INSERT INTO Pupils_Parents (pupil_id_fk, parent_id_fk) VALUES (?, ?)");
                    $update_pupil->bind_param("ii", $pupilid_fk, $parent_id_pk);
                    if ($update_pupil->execute()) {
                        $_SESSION['parent_added'] = 'success';
                    } else {
                        $_SESSION['parent_added'] = 'error_relationship';
                    }
                } else {
                    $_SESSION['parent_added'] = 'error';
                }

                $stmt->close();
            }
        }
    }

    $conn->close();
    header("Location: add_parent.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Parent/Guardian</title>
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
<!-- Add Parent Form -->
    <div class="add_parent">
        <div class="add_parent_form" id="add_parent_form">
            <h1>Add Parent/Guardian</h1>
            <div id="alert-message" style="display:none; color: red; margin-bottom: 20px;">
                <?php
                if (isset($_SESSION['parent_added'])) {
                    if ($_SESSION['parent_added'] == 'success') {
                        echo 'Parent/Guardian added successfully.';
                    } else if ($_SESSION['parent_added'] == 'invalid_input') {
                        echo 'Invalid input. Please correct the highlighted fields.';
                    } else if ($_SESSION['parent_added'] == 'pupil_has_parent') {
                        echo 'Selected pupil already has a parent/guardian.';
                    } else if ($_SESSION['parent_added'] == 'error_relationship') {
                        echo 'Error updating pupil-parent relationship.';
                    } else {
                        echo 'Error adding parent/guardian.';
                    }
                    unset($_SESSION['parent_added']);
                }
                ?>
            </div>
            <form id="parentForm" action="add_parent.php" method="post" onsubmit="return validateForm()">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="phonenumber">Phone Number:</label>
                        <input type="text" id="phonenumber" name="phonenumber" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="relationship_to_pupil">Relationship to Pupil:</label>
                        <input type="text" id="relationship_to_pupil" name="relationship_to_pupil" required>
                    </div>
                    <div class="form-group">
                        <label for="pupilid_fk">Pupil:</label>
                        <select id="pupilid_fk" name="pupilid_fk" required>
                            <option value="">Select Pupil</option>
                            <?php
                            $sql = "SELECT pupil_id_pk, first_name, last_name FROM pupils WHERE pupil_id_pk NOT IN (SELECT pupil_id_fk FROM Pupils_Parents)";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['pupil_id_pk'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No pupils available</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group button-group">
                    <input type="submit" value="Add Parent/Guardian">
                </div>
            </form>
        </div>
    </div>
<!-- JavaScript for Form Validation and Dynamic Dropdown Update -->
    <script>
        function validateForm() {
            var firstname = document.getElementById('firstname').value;
            var lastname = document.getElementById('lastname').value;
            var date_of_birth = document.getElementById('date_of_birth').value;
            var address = document.getElementById('address').value;
            var phonenumber = document.getElementById('phonenumber').value;
            var email = document.getElementById('email').value;
            var relationship_to_pupil = document.getElementById('relationship_to_pupil').value;
            var pupilid_fk = document.getElementById('pupilid_fk').value;
            var namePattern = /^[a-zA-Z-' ]*$/;
            var phonePattern = /^[0-9]{10}$/;
            var emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
            if (!firstname || !lastname || !date_of_birth || !address || !phonenumber || !email || !relationship_to_pupil || !pupilid_fk) {
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

        $(document).ready(function() {
            $('#parentForm').on('submit', function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'add_parent.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.includes('Parent/Guardian added successfully')) {
                            displayAlert('Parent/Guardian added successfully.');
                            updatePupilDropdown();
                            $('#parentForm')[0].reset(); 
                        } else {
                            displayAlert(response);
                        }
                    }
                });
            });

            $('#deleteParentForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: 'add_parent.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.includes('Parent/Guardian deleted successfully')) {
                            displayAlert('Parent/Guardian deleted successfully.');
                            updatePupilDropdown();
                            $('#deleteParentForm')[0].reset(); 
                        } else {
                            displayAlert(response);
                        }
                    }
                });
            });
        });

        function updatePupilDropdown() {
            $.ajax({
                type: 'GET',
                url: 'get_available_pupils.php',
                success: function(response) {
                    $('#pupilid_fk').html(response);
                }
            });
        }

        <?php
        if (isset($_SESSION['parent_deleted'])) {
            if ($_SESSION['parent_deleted'] == 'success') {
                echo 'displayAlert("Parent/Guardian deleted successfully.");';
            } else {
                echo 'displayAlert("Error deleting parent/guardian.");';
            }
            unset($_SESSION['parent_deleted']);
        }
        ?>
    </script>
<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">&copy; 2024 Rishton Academy Primary School. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
