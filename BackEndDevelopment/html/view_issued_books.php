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

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = $search ? "WHERE b.Title LIKE '%$search%' OR p.first_name LIKE '%$search%' OR p.last_name LIKE '%$search%'" : '';
$limit = 6; 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$count_sql = "SELECT COUNT(*) FROM Issued_books ib
JOIN Library_books b ON ib.Book_id_fk = b.Book_id_pk
JOIN pupils p ON ib.pupil_id_fk = p.pupil_id_pk $search_query";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);
$sql = "SELECT ib.Issue_id_pk, b.Title, p.first_name, p.last_name, ib.Issue_date, ib.Return_date FROM Issued_books ib
JOIN Library_books b ON ib.Book_id_fk = b.Book_id_pk
JOIN pupils p ON ib.pupil_id_fk = p.pupil_id_pk $search_query LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Issued Books</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmDelete(issueId) {
            if (confirm("Are you sure you want to delete this issued book record?")) {
                $.ajax({
                    url: 'delete_issued_book.php',
                    type: 'POST',
                    data: { delete_id: issueId },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Issued book record deleted successfully.');
                            location.reload();
                        } else {
                            alert('Error deleting issued book record.');
                        }
                    }
                });
            }
        }
    </script>
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
    <div class="display_books">
        <div class="container">
            <h1>All Issued Book Records</h1>
            <div class="search-bar">
                <form method="get" action="view_issued_books.php">
                    <input type="text" id="searchInput" name="search" placeholder="Search by book title or pupil name..." value="<?php echo $search; ?>">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="table-container">
                <?php
                if ($result->num_rows > 0) {
                    echo "<table class='book-table'>";
                    echo "<thead><tr><th>Issue ID</th><th>Book Title</th><th>Pupil Name</th><th>Issue Date</th><th>Return Date</th><th>Edit</th><th>Delete</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Issue_id_pk"] . "</td>";
                        echo "<td>" . $row["Title"] . "</td>";
                        echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
                        echo "<td>" . $row["Issue_date"] . "</td>";
                        echo "<td>" . $row["Return_date"] . "</td>";
                        echo "<td><a href='edit_issued_book.php?id=" . $row["Issue_id_pk"] . "' class='edit-btn'>Edit</a></td>";
                        echo "<td><button type='button' class='delete-btn' onclick='confirmDelete(" . $row["Issue_id_pk"] . ")'>Delete</button></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No issued books found.☹️</p>";
                }
                ?>
            </div>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='view_issued_books.php?page=$i&search=$search'";
                    if ($i == $page) echo " class='active'";
                    echo ">$i</a>";
                }
                ?>
            </div>
        </div>
    </div>
<!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="footer-text">&copy; 2024 Rishton Academy Primary School. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
