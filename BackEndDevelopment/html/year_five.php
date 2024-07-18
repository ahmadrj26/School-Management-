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
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;
$classid_fk = 6; 
$sql_class = "SELECT c.class_id_pk, c.class_name, c.class_capacity, COUNT(p.class_id_fk) AS occupied, c.class_capacity - COUNT(p.class_id_fk) AS available
              FROM classes c
              LEFT JOIN pupils p ON c.class_id_pk = p.class_id_fk
              WHERE c.class_id_pk = ?
              GROUP BY c.class_id_pk";
$stmt_class = $conn->prepare($sql_class);
$stmt_class->bind_param("i", $classid_fk);
$stmt_class->execute();
$class_result = $stmt_class->get_result();
$class = $class_result->fetch_assoc();
$sql_pupils = "SELECT pupil_id_pk, first_name, last_name, date_of_birth, address, medical_information 
               FROM pupils 
               WHERE class_id_fk = ? AND (first_name LIKE ? OR last_name LIKE ?)
               LIMIT ? OFFSET ?";
$search_param = "%{$search}%";
$stmt_pupils = $conn->prepare($sql_pupils);
$stmt_pupils->bind_param("isssi", $classid_fk, $search_param, $search_param, $limit, $offset);
$stmt_pupils->execute();
$pupils_result = $stmt_pupils->get_result();

$sql_total = "SELECT COUNT(*) AS total FROM pupils WHERE class_id_fk = ? AND (first_name LIKE ? OR last_name LIKE ?)";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("iss", $classid_fk, $search_param, $search_param);
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_pupils = $total_result->fetch_assoc()['total'];

$total_pages = ceil($total_pupils / $limit);

$stmt_class->close();
$stmt_pupils->close();
$stmt_total->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Year Five</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    padding: 15px 10px; 
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
    <div class="display_class">
        <div class="container">
            <h1>Year Five</h1>
            <div class="table-container">
                <table class="class-table">
                    <thead>
                        <tr>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th>Class Capacity</th>
                            <th>Occupied</th>
                            <th>Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $class['class_id_pk']; ?></td>
                            <td><?php echo $class['class_name']; ?></td>
                            <td><?php echo $class['class_capacity']; ?></td>
                            <td><?php echo $class['occupied']; ?></td>
                            <td><?php echo $class['available']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h2 style="text-align: center; font-weight: 1000; color: #333;">Pupils in Year Five</h2>
            <div class="search-bar">
                <form method="get" action="year_five.php">
                    <input type="text" id="searchInput" name="search" placeholder="Search by name..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="table-container">
                <?php if ($total_pupils == 0): ?>
                    <p>No pupils found.☹️</p>
                <?php else: ?>
                    <table class="pupil-table">
                        <thead>
                            <tr>
                                <th>Pupil ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Medical Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($pupil = $pupils_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $pupil['pupil_id_pk']; ?></td>
                                    <td><?php echo $pupil['first_name']; ?></td>
                                    <td><?php echo $pupil['last_name']; ?></td>
                                    <td><?php echo $pupil['date_of_birth']; ?></td>
                                    <td><?php echo $pupil['address']; ?></td>
                                    <td><?php echo $pupil['medical_information']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
<!-- Pagination -->
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="year_five.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>
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
