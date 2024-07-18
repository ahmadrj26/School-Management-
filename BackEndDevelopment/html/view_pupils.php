<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pipils</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function confirmDelete(pupilId) {
            if (confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: 'delete_pupil.php',
                    type: 'POST',
                    data: { delete_id: pupilId },
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
    <style>
.display_pupils {
    padding: 50px 0;
    padding-bottom: 100px;
    min-height: calc(100vh - 60px); 
}

.display_pupils h1 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 1000;
    font-size: 30px;
    color: #333;
}

.search-bar {
    text-align: center;
    margin-bottom: 20px;
}

.search-bar input {
    padding: 10px;
    width: 80%;
    max-width: 400px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.search-bar button, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border: none;
    background-color: #333;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    margin-left: 10px;
    transition: background-color 0.3s;
    text-decoration: none;
    text-align: center;
}

.search-bar button:hover, .edit-btn:hover, .delete-btn:hover {
    background-color: #dd9e3a;
}

.search-bar button i {
    font-size: 16px; 
}

.table-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    max-height: 70vh; 
}

.pupil-table {
    width: 90%;
    border-collapse: separate; 
    border-spacing: 0; 
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 5px; 
    overflow: hidden; 
}

.pupil-table th, .pupil-table td {
    padding: 6px 10px; 
    text-align: center;
    border: 1px solid #ddd;
}

.pupil-table th {
    background-color: #333;
    color: #fff;
    letter-spacing: 0.1em;
    padding: 15px 6px;
    text-align: center;
}

.pupil-table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

.pupil-table tbody tr:hover {
    background-color: #e9e9e9;
    cursor: pointer;
}

.pagination {
    margin-top: 20px;
}

.pagination a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    border: 1px solid #ddd;
    margin: 0 5px;
    transition: background-color 0.3s;
    border-radius: 5px;
}

.pagination a.active {
    background-color: #333;
    color: #fff;
}

.pagination a:hover {
    background-color: #dd9e3a;
    color: #fff;
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
<!-- Main Content -->
    <div class="display_pupils">
        <div class="container">
            <h1>All Pupil Records</h1>
            <div class="search-bar">
                <form method="get" action="view_pupils.php">
                    <input type="text" id="searchInput" name="search" placeholder="Search by name..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="table-container">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "password";
                $dbname = "School_management";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $limit = 6; // Max number of rows in one table before go to the next page
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $start = ($page - 1) * $limit;
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                $sqlTotal = "SELECT COUNT(*) FROM pupils WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
                $resultTotal = $conn->query($sqlTotal);
                $total = $resultTotal->fetch_row()[0];
                $pages = ceil($total / $limit);

                $sql = "SELECT pupils.pupil_id_pk, pupils.first_name, pupils.last_name, pupils.date_of_birth, pupils.address, pupils.medical_information, classes.class_name 
                        FROM pupils 
                        JOIN classes ON pupils.class_id_fk = classes.class_id_pk 
                        WHERE pupils.first_name LIKE '%$search%' OR pupils.last_name LIKE '%$search%' 
                        LIMIT $start, $limit";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table class='pupil-table'>";
                    echo "<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Date of Birth</th><th>Address</th><th>Medical Information</th><th>Class</th><th>Edit</th><th>Delete</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["pupil_id_pk"] . "</td>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["last_name"] . "</td>";
                        echo "<td>" . $row["date_of_birth"] . "</td>";
                        echo "<td>" . $row["address"] . "</td>";
                        echo "<td>" . $row["medical_information"] . "</td>";
                        echo "<td>" . $row["class_name"] . "</td>";
                        echo "<td><a href='edit_pupil.php?id=" . $row["pupil_id_pk"] . "' class='edit-btn'>Edit</a></td>";
                        echo "<td>
                                <button type='button' class='delete-btn' onclick='confirmDelete(" . $row["pupil_id_pk"] . ")'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No pupils found.☹️</p>";
                }
                echo '<div class="pagination" >';
                for ($i = 1; $i <= $pages; $i++) {
                    echo "<a href='view_pupils.php?page=$i&search=$search'" . ($i == $page ? " class='active'" : "") . ">" . $i . "</a>";
                }
                echo '</div>';

                $conn->close();
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
