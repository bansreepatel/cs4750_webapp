<?php
// session_name("current session");
session_start();
// echo print_r($_SESSION);

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "cs4750_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//add new book form
if (isset($_POST['isbn'])) {
  //creating the book
  $isbn = $_POST['isbn'];
  $title = $_POST['title'];
  $author = $_POST['author'];
  $genre = $_POST['genre'];

  $query = "INSERT INTO books (ISBN, book_title, book_author, book_genre) VALUES ('$isbn', '$title', '$author', '$genre')";
  $result = $conn->query($query);
  if($result){
    header('Location: admin.php');
  }else{
    echo "Error executing the action";
  }

}

//logout form
if (isset($_POST['logout'])) {
  $time = (string)time();
  // echo print_r($_SESSION);
  $session = $_SESSION['session_id'];
  // echo $session;
  $query = "UPDATE sessions SET end_date = '$time' WHERE session_id = '$session'";
  $result = $conn->query($query);
  session_destroy();
  header('Location: login.php');
  // echo "logging out";
}
?>

<html>

<head>
    <title>Library Management System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />

    <style>
      table, th, td {
        border: 1px solid white;
        color:white;
      }
      tr {
        color:white;
      }
    </style>

</head>

<body class="landing is-preload">
    <div id="page-wrapper">

        <!-- Header -->
        <header id="header" class="alt">
            <h1>Library Management System</h1>
            <nav id="nav">
            <form method="post"> <input type="hidden" id="logout" name="logout" value="logout"> <button type="submit" class="align-right">Log Out</button></form>
            </nav>
        </header>

        <!-- CTA -->
        <section id="cta">

            <h2>New Book</h2>

            <form method="post">
                  <div class="align-center">
                    <input class="align-center" type="text" id="isbn" name="isbn" placeholder="ISBN" required/>
                  </br>
                    <input class="align-center" type="text" id="title" name="title" placeholder="Book Title" required/>
                  </br>
                    <input class="align-center" type="text" id="author" name="author" placeholder="Book Author" required/>
                  </br>
                    <input class="align-center" type="text" id="genre" name="genre" placeholder="Genre" required/>
                  </br>
                    <input class="align-center" type="submit" id="create_button" value="Create" />
                  </div>
            </form>

        </section>

        <!-- Footer -->
        <!-- <footer id="footer">
            <ul class="copyright">
                <li>&copy; Library Management System. All rights reserved.</li>
                <li>Design: HTML5 UP</li>
            </ul>
        </footer> -->

    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.dropotron.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>
