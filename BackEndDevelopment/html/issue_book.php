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
    $book_id_fk = $_POST['book_id_fk'];
    $pupil_id_fk = $_POST['pupil_id_fk'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];
    if (empty($pupil_id_fk) || empty($book_id_fk) || empty($issue_date) || empty($return_date)) {
        $_SESSION['issue_error'] = 'Invalid input. Please correct the highlighted fields.';
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO Issued_books (Book_id_fk, pupil_id_fk, Issue_date, Return_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $book_id_fk, $pupil_id_fk, $issue_date, $return_date);

            if ($stmt->execute()) {
                $_SESSION['issue_success'] = 'Book issued successfully.';
            } else {
                $_SESSION['issue_error'] = 'Error issuing book: ' . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $_SESSION['issue_error'] = 'Exception: ' . $e->getMessage();
        }
    }

    header('Location: issue_book.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book</title>
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
<!-- Issue Book Form -->
    <div class="issue_book">
        <div class="issue_book_form" id="issue_book_form">
            <h1>Issue Book</h1>
            <div id="alert-message" style="display:none; color: red; margin-bottom: 20px;"></div>
            <form id="issueBookForm" action="issue_book.php" method="post" onsubmit="return validateForm()">
                <div class="form-row">
                    <div class="form-group">
                        <label for="pupil_id_fk">Pupil:</label>
                        <select id="pupil_id_fk" name="pupil_id_fk" required>
                            <option value="">Select Pupil</option>
                            <?php
                            try {
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                if ($conn->connect_error) {
                                    throw new Exception("Connection failed: " . $conn->connect_error);
                                }

                                $sql = "SELECT pupil_id_pk, first_name, last_name FROM pupils";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['pupil_id_pk'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No pupils available</option>";
                                }
                                $conn->close();
                            } catch (Exception $e) {
                                echo "<option value=''>" . $e->getMessage() . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="book_id_fk">Book:</label>
                        <select id="book_id_fk" name="book_id_fk" required>
                            <option value="">Select Book</option>
                            <?php
                            try {
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                if ($conn->connect_error) {
                                    throw new Exception("Connection failed: " . $conn->connect_error);
                                }

                                $sql = "SELECT Book_id_pk, Title FROM Library_books";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['Book_id_pk'] . "'>" . $row['Title'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No books available</option>";
                                }
                                $conn->close();
                            } catch (Exception $e) {
                                echo "<option value=''>" . $e->getMessage() . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="issue_date">Issue Date:</label>
                        <input type="date" id="issue_date" name="issue_date" required>
                    </div>
                    <div class="form-group">
                        <label for="return_date">Return Date:</label>
                        <input type="date" id="return_date" name="return_date" required>
                    </div>
                </div>
                <div class="form-group button-group">
                    <input type="submit" value="Issue Book">
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
            var pupil_id_fk = document.getElementById('pupil_id_fk').value;
            var book_id_fk = document.getElementById('book_id_fk').value;
            var issue_date = document.getElementById('issue_date').value;
            var return_date = document.getElementById('return_date').value;

            if (!pupil_id_fk) {
                displayAlert('Please select a valid pupil.');
                return false;
            }

            if (!book_id_fk) {
                displayAlert('Please select a valid book.');
                return false;
            }

            if (!issue_date) {
                displayAlert('Please select an issue date.');
                return false;
            }

            if (!return_date) {
                displayAlert('Please select a return date.');
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
        if (isset($_SESSION['issue_error'])) {
            echo 'displayAlert("' . $_SESSION['issue_error'] . '");';
            unset($_SESSION['issue_error']);
        }
        if (isset($_SESSION['issue_success'])) {
            echo 'displayAlert("' . $_SESSION['issue_success'] . '");';
            unset($_SESSION['issue_success']);
        }
        ?>
    </script>
</body>
</html>
