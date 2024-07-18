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
    $book_id_pk = $_POST['Book_id_pk'];
    $title = $_POST['Title'];
    $author = $_POST['Author'];
    $publication_year = $_POST['Publication_year'];
    $page_count = $_POST['Page_count'];

    // Validate input fields
    if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $title) ||
        !preg_match("/^[a-zA-Z-' ]*$/", $author) ||
        !preg_match("/^[0-9]{1,4}$/", $publication_year) ||
        ($page_count && !preg_match("/^[0-9]*$/", $page_count))) {
        $_SESSION['book_updated'] = 'invalid_input';
        header('Location: edit_book.php?id=' . $book_id_pk);
        exit();
    } else {
        // Update book in the database
        $stmt = $conn->prepare("UPDATE Library_books SET Title=?, Author=?, Publication_year=?, Page_count=? WHERE Book_id_pk=?");
        $stmt->bind_param("sssii", $title, $author, $publication_year, $page_count, $book_id_pk);

        if ($stmt->execute()) {
            $_SESSION['book_updated'] = 'success';
        } else {
            $_SESSION['book_updated'] = 'error';
        }

        $stmt->close();
        $conn->close();
        header('Location: view_books.php');
        exit();
    }
} elseif (isset($_GET['id'])) {
    $book_id_pk = $_GET['id'];

    $sql = "SELECT * FROM Library_books WHERE Book_id_pk=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id_pk);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        echo "Book not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "No book ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
.edit_book {
    display: flex;
    justify-content: center;
    align-items: flex-start; 
    width: 100%;
    height: 100vh;
}

.edit_book_form {
    background-color: white;
    padding: 20px 40px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    width: 100%;
    max-width: 500px;
    margin-top: 160px; 
}

.edit_book_form h1 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #303030;
    text-align: center;
    font-weight: 1000;
}

.form-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px; 
}

.form-group {
    flex: 1;
    margin-right: 10px;
}

.form-group:last-child {
    margin-right: 0;
}

.edit_book_form label {
    display: block;
    font-size: 14px;
    color: #303030;
    margin-bottom: 5px;
}

.edit_book_form input[type="text"],
.edit_book_form select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
}

.edit_book_form .button-group {
    display: flex;
    justify-content: center;
}

.edit_book_form input[type="submit"] {
    background-color: #303030;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.edit_book_form input[type="submit"]:hover {
    background-color: #dd9e3a;
}

    </style>
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
<!-- Edit Book Form -->
    <div class="edit_book">
        <div class="edit_book_form" id="edit_book_form">
            <h1>Edit Book</h1>
            <div id="alert-message" style="display:none; color: red; margin-bottom: 20px;"></div>
            <form id="bookForm" action="edit_book.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="Book_id_pk" value="<?php echo $book['Book_id_pk']; ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="Title" value="<?php echo $book['Title']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Author:</label>
                        <input type="text" id="author" name="Author" value="<?php echo $book['Author']; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="publishedyear">Published Year:</label>
                        <input type="text" id="publishedyear" name="Publication_year" value="<?php echo $book['Publication_year']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="page_count">Page Count:</label>
                        <input type="text" id="page_count" name="Page_count" value="<?php echo $book['Page_count']; ?>">
                    </div>
                </div>
                <div class="form-group button-group">
                    <input type="submit" value="Update Book">
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
            var title = document.getElementById('title').value;
            var author = document.getElementById('author').value;
            var publishedyear = document.getElementById('publishedyear').value;
            var page_count = document.getElementById('page_count').value;

            var titlePattern = /^[a-zA-Z0-9-' ]*$/;
            var authorPattern = /^[a-zA-Z-' ]*$/;
            var yearPattern = /^[0-9]{1,4}$/;
            var pageCountPattern = /^[0-9]*$/;

            if (!titlePattern.test(title)) {
                displayAlert('Title can only contain letters, numbers, hyphens, apostrophes, and spaces.');
                return false;
            }

            if (!authorPattern.test(author)) {
                displayAlert('Author can only contain letters, hyphens, apostrophes, and spaces.');
                return false;
            }

            if (!yearPattern.test(publishedyear)) {
                displayAlert('Published year must be a 1-4 digit number.');
                return false;
            }

            if (page_count && !pageCountPattern.test(page_count)) {
                displayAlert('Page count must be a number.');
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
        if (isset($_SESSION['book_updated'])) {
            if ($_SESSION['book_updated'] == 'invalid_input') {
                echo 'displayAlert("Invalid input. Please correct the highlighted fields.");';
            } else if ($_SESSION['book_updated'] == 'error') {
                echo 'displayAlert("Error updating book.");';
            }
            unset($_SESSION['book_updated']);
        }
        ?>
    </script>
</body>
</html>
