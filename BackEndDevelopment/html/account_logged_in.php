<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: account.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Rishton Academy</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
.account_page {
    display: flex;
    justify-content: center;
    align-items: flex-start; 
    width: 100%;
    height: 100vh;
}

.account_form, .account_logged_in_form {
    background-color: white;
    padding: 20px 40px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    width: 100%;
    max-width: 500px;
    margin-top: 160px; 
}

.account_form h1, .account_logged_in_form h1 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #303030;
    text-align: center;
    font-weight: 1000;
}

.form-group {
    margin-bottom: 20px; 
}

.message-center {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100px; 
}

.account_form label {
    display: block;
    font-size: 14px;
    color: #303030;
    margin-bottom: 5px;
}

.account_form input[type="text"],
.account_form input[type="password"],
.account_form select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
    margin-bottom: 20px; 
}

.account_logged_in_form p {
    font-size: 18px; 
    color: #303030;
    margin-bottom: 20px;
    text-align: center; /
}

.account_form .button-group,
.account_logged_in_form .button-group {
    display: flex;
    justify-content: center;
}

.account_form input[type="submit"],
.account_logged_in_form input[type="submit"] {
    background-color: #303030;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-align: center;
    text-decoration: none;
}

.account_form input[type="submit"]:hover,
.account_logged_in_form input[type="submit"]:hover {
    background-color: #dd9e3a;
}

.account_form a {
    color: #303030;
    text-decoration: none;
}

.account_form a:hover {
    text-decoration: underline;
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
    <div class="account_page">
        <div class="account_logged_in_form">
            <h1>You are logged in as <?php echo $_SESSION['username']; ?></h1>
            <div class="form-group">
                <p>Welcome to Rishtom Acedemy School Managemnet Ssytem <?php echo $_SESSION['username']; ?>.</p>
            </div>
            <div class="button-group">
                <form action="logout.php" method="post">
                    <input type="submit" value="Logout">
                </form>
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
