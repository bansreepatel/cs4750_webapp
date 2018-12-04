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

//borrow form
if (isset($_POST['id'])) {
  $id = (int)$_POST["id"];
  $sql = "SELECT * FROM bookcopies WHERE isbn = $id AND book_status = 'in'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $num_rows = $result->num_rows;
    $count = 0;
    $row = $result->fetch_assoc();
    $copy = (int)$row['copy_ID'];
    $uid = $_SESSION["uid"];
    $sql = "UPDATE bookcopies SET book_status='out', user_id='$uid' WHERE copy_ID = $copy";
    $result = $conn->query($sql);
    if($result === TRUE){
      $session = $_SESSION['session_id'];
      $query = "INSERT INTO transactions (transaction_desc, session_ID) VALUES ('borrowing a book', $session)";
      $res = $conn->query($query);
      if($res === TRUE){
        echo "Book Borrowed Successfully!";
      }
    }
  }else{
    echo "No Available Copies of this Book";
  }
}

//logout form
if (isset($_POST['logout'])) {
  $time = (string)time();
  $session = $_SESSION['session_id'];
  $query = "UPDATE sessions SET end_date = '$time' WHERE session_id = '$session'";
  $result = $conn->query($query);
  session_destroy();
  header('Location: login.php');
}

$books = [];
$num_copies = [];

//search form
if (isset($_POST['search'])) {
  $search = $_POST['search'];
  $search_str = "%" . $search . "%";
  $query = "SELECT * FROM books WHERE (isbn LIKE '$search_str') OR (book_title LIKE '$search_str') OR (book_author LIKE '$search_str')";
  $result = $conn->query($query);
  $num_rows = $result->num_rows;
  $count = 0;
  while($count < $num_rows) {
    $row = $result->fetch_assoc();
    $isbn = $row['ISBN'];
    $my_query = "SELECT * FROM bookcopies WHERE isbn = $isbn AND book_status = 'in'";
    $res = $conn->query($my_query);
    $num_copies[$isbn] = $res->num_rows;
    $books[] = $row;
    $count = $count +1;
  }
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
    <!-- <div id="page-wrapper"> -->
        <!-- Header -->
        <header id="header" class="alt">
            <h1>Library Management System</h1>
            <nav id="nav">
            <form method="post"> <input type="hidden" id="logout" name="logout" value="logout"> <button type="submit" class="align-right">Log Out</button></form>
            </nav>

        </header>
        <!-- CTA -->
        <section id="cta" class="align-left" style="color:white; ">

            <form method="post">
              <!-- <div class="align-center"> -->
                  <div class="align-center">
                    <p>Enter a search term to begin</p>
                    <input class="align-center" type="text" id="search" name="search" placeholder="Search" />
                    <p> </p>
                    <input class="align-center" type="submit" id="search_button" value="Go" />
                  </div>
            </form>


            <h3>Books</h3>

            <table class="align-left" style="table-layout:fixed; width:80%; text-align:left; color:white; margin-left:10%; margin-right:500px; border:2px solid white; border-collapse:collapse; background-color:#008B8B;">
              <tr>
                <th style="color:white;">ISBN</th>
                <th style="color:white;">Title</th>
                <th style="color:white;">Author</th>
                <th style="color:white;">Genre</th>
                <th style="color:white;">Available Copies</th>
                <th style="color:white;">Borrow</th>
              </tr>
              <?php foreach($books as $this_book): ?>
                <tr style="background-color:#008B8B;">
                  <td><?php echo $this_book['ISBN'];?></td>
                  <td><?php echo $this_book['book_title'];?></td>
                  <td><?php echo $this_book['book_author'];?></td>
                  <td><?php echo $this_book['book_genre'];?></td>
                  <td><?php echo $num_copies[$this_book['ISBN']];?></td>
                  <td>
                    <?php if($num_copies[$this_book['ISBN']] > 0) : ?>
                    <form method="post">
                        <button type="submit">Borrow</button>
                        <input type="hidden" id="id" name="id" value="<?php echo $this_book['ISBN'];?>">
                    </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>


        </section>

    <!-- </div> -->

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
