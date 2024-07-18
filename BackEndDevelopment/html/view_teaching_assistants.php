<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "School_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = $search ? "WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%'" : '';
$limit = 6; 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$count_sql = "SELECT COUNT(*) FROM teaching_assistants $search_query";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_row()[0];
$total_pages = ceil($total_rows / $limit);
$sql = "SELECT t.assistant_id_pk, t.first_name, t.last_name, t.address, t.phone_number, t.annual_salary, t.qualification, t.date_of_birth, c.class_name 
        FROM teaching_assistants t 
        LEFT JOIN classes c ON t.class_id_fk = c.class_id_pk
        $search_query 
        LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Teaching Assistants</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmDelete(assistantId) {
            if (confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: 'delete_teaching_assistant.php',
                    type: 'POST',
                    data: { delete_id: assistantId },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Record deleted successfully.');
                            location.reload();
                        } else {
                            alert('Error deleting record.');
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
    <div class="display_teachers">
        <div class="container">
            <h1>All Teaching Assistant Records</h1>
            <div class="search-bar">
                <form method="get" action="view_teaching_assistants.php">
                    <input type="text" id="searchInput" name="search" placeholder="Search by name..." value="<?php echo $search; ?>">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="table-container">
                <?php
                if ($result->num_rows > 0) {
                    echo "<table class='teacher-table'>";
                    echo "<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Address</th><th>Phone Number</th><th>Annual Salary</th><th>Qualification</th><th>Date of Birth</th><th>Class</th><th>Edit</th><th>Delete</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["assistant_id_pk"] . "</td>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["last_name"] . "</td>";
                        echo "<td>" . $row["address"] . "</td>";
                        echo "<td>" . $row["phone_number"] . "</td>";
                        echo "<td>" . $row["annual_salary"] . "</td>";
                        echo "<td>" . $row["qualification"] . "</td>";
                        echo "<td>" . $row["date_of_birth"] . "</td>";
                        echo "<td>" . $row["class_name"] . "</td>";
                        echo "<td><a href='edit_teaching_assistant.php?id=" . $row["assistant_id_pk"] . "' class='edit-btn'>Edit</a></td>";
                        echo "<td><button type='button' class='delete-btn' onclick='confirmDelete(" . $row["assistant_id_pk"] . ")'>Delete</button></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No teaching assistants found.☹️</p>";
                }
                ?>
            </div>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='view_teaching_assistants.php?page=$i&search=$search'";
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
