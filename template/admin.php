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
// echo "Connected successfully";

// $sql = "SELECT * FROM bookcopies WHERE book_status = 'out'";
// $result = $conn->query($sql);
// $books =[];
// $copy_id = [];
// $users = [];
// $num_rows = $result->num_rows;
// $count = 0;
// while ($row = $result->fetch_assoc()) {
//     $books[] = $row['isbn'];
//     $copy_id[] = $row['copy_ID'];
//     $users[] = $row['user_id'];
// }
//
// $book_strs = [];
// foreach ($books as $book) {
//   $sql = "SELECT * FROM books WHERE isbn = $book";
//   $result = $conn->query($sql);
//   $row = $result->fetch_assoc();
//   $book_strs[] = $row['book_title'] . ", ". $row['book_author'] . " (" . $row['book_genre'] . ")";
// }
//
// $users_strs = [];
// foreach ($users as $user) {
//   $sql = "SELECT * FROM users WHERE user_id = $user";
//   $result = $conn->query($sql);
//   $row = $result->fetch_assoc();
//   $users_strs[] = $row['first_name'] . " ". $row['last_name'] . " (" . $row['email'] . ")";
// }
//
// $strings = [];
// while( $count < $num_rows){
//   $this_string = $book_strs[$count] . " | " . $users_strs[$count];
//   $strings[] = $this_string;
//   $count = $count + 1;
// }
//
// $count = 0;

//loading checked out Copies
$query =
"SELECT books.ISBN, books.book_title, books.book_author, books.book_genre, bookcopies.copy_ID, users.first_name, users.last_name, users.email
FROM users
INNER JOIN bookcopies
ON users.user_ID = bookcopies.user_id
INNER JOIN books
ON books.ISBN = bookcopies.isbn";

$result = $conn->query($query);
$num_rows = $result->num_rows;
$count = 0;
$books = [];
while($count < $num_rows) {
  $row = $result->fetch_assoc();
  $books[] = $row;
  $count = $count +1;
}

//loading all books
$query = "SELECT * FROM books";
$result = $conn->query($query);
$num_rows = $result->num_rows;
$count = 0;
$all_books = [];
$num_copies = [];
while($count < $num_rows) {
  $row = $result->fetch_assoc();
  $isbn = $row['ISBN'];
  $my_query = "SELECT * FROM bookcopies WHERE isbn = $isbn";
  $res = $conn->query($my_query);
  $num_copies[$isbn] = $res->num_rows;
  $all_books[] = $row;
  $count = $count +1;
}

//loading all users
$query = "SELECT * FROM users";
$result = $conn->query($query);
$num_rows = $result->num_rows;
$count = 0;
$all_users = [];
while($count < $num_rows) {
  $row = $result->fetch_assoc();
  $all_users[] = $row;
  $count = $count +1;
}

//check in form
if (isset($_POST['id'])) {
  // session_start();
  $copy_id = (int)$_POST["id"];

  $sql = "UPDATE bookcopies SET book_status='in', user_id=NULL WHERE copy_ID = $copy_id";
  $result = $conn->query($sql);

  if($result === TRUE){
    $session = $_SESSION['session_id'];
    // echo "session" . $session;
    $query = "INSERT INTO transactions (transaction_desc, session_ID) VALUES ('checking in a book', $session)";
    $res = $conn->query($query);

    if($res === TRUE){
      Header('Location: '.$_SERVER['PHP_SELF']);
      echo "Book Checked In Successfully!";
    }
  }else{
    echo "An error occurred when processing your request.";
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

            <h2>Checked Out Copies</h2>

            <table class="align-left" style="table-layout:fixed; width:80%; text-align:left; color:white; margin-left:10%; margin-right:500px; border:2px solid white; border-collapse:collapse; background-color:#008B8B;">
              <tr>
                <th style="color:white;">ISBN</th>
                <th style="color:white;">Title</th>
                <th style="color:white;">Author</th>
                <th style="color:white;">Genre</th>
                <th style="color:white;">Copy ID</th>
                <th style="color:white;">First Name</th>
                <th style="color:white;">Last Name</th>
                <th style="color:white;">Email</th>
                <th style="color:white;">Ckeck In</th>
              </tr>
              <?php foreach($books as $this_book): ?>
                <tr style="background-color:#008B8B;">
                  <td><?php echo $this_book['ISBN'];?></td>
                  <td><?php echo $this_book['book_title'];?></td>
                  <td><?php echo $this_book['book_author'];?></td>
                  <td><?php echo $this_book['book_genre'];?></td>
                  <td><?php echo $this_book['copy_ID'];?></td>
                  <td><?php echo $this_book['first_name'];?></td>
                  <td><?php echo $this_book['last_name'];?></td>
                  <td><?php echo $this_book['email'];?></td>
                  <td>
                    <form method="post">
                        <button type="submit">Check In</button>
                        <input type="hidden" id="id" name="id" value="<?php echo $this_book['copy_ID'];?>">
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>

          </br>

            <a href="new_book.php"><input class="align-center" type="submit" id="new_book" value="Create Book" /></a>
            <a href="update_book.php"><input class="align-center" type="submit" id="update_book" value="Update Book" /></a>
            <a href="delete_book.php"><input class="align-center" type="submit" id="delete_book" value="Delete Book" /></a>
            <a href="new_copy.php"><input class="align-center" type="submit" id="new_copy" value="Add Book Copy" /></a>
            <a href="remove_copy.php"><input class="align-center" type="submit" id="remove_copy" value="Delete Book Copy" /></a>

          </br>
          </br>

          <h3>All Books</h3>

          <table class="align-left" style="table-layout:fixed; width:80%; text-align:left; color:white; margin-left:10%; margin-right:500px; border:2px solid white; border-collapse:collapse; background-color:#008B8B;">
            <tr>
              <th style="color:white;">ISBN</th>
              <th style="color:white;">Title</th>
              <th style="color:white;">Author</th>
              <th style="color:white;">Genre</th>
              <th style="color:white;"># of Copies</th>

            </tr>
            <?php foreach($all_books as $this_book): ?>
              <tr style="background-color:#008B8B;">
                <td><?php echo $this_book['ISBN'];?></td>
                <td><?php echo $this_book['book_title'];?></td>
                <td><?php echo $this_book['book_author'];?></td>
                <td><?php echo $this_book['book_genre'];?></td>
                <td><?php echo $num_copies[$this_book['ISBN']];?></td>

              </tr>
            <?php endforeach; ?>
          </table>

        </br>
        </br>

          <h3>All Users</h3>

          <table class="align-left" style="table-layout:fixed; width:80%; text-align:left; color:white; margin-left:10%; margin-right:500px; border:2px solid white; border-collapse:collapse; background-color:#008B8B;">
            <tr>
              <th style="color:white;">First Name</th>
              <th style="color:white;">Last Name</th>
              <th style="color:white;">Email</th>
              <th style="color:white;">Address</th>

            </tr>
            <?php foreach($all_users as $this_user): ?>
              <tr style="background-color:#008B8B;">
                <td><?php echo $this_user['first_name'];?></td>
                <td><?php echo $this_user['last_name'];?></td>
                <td><?php echo $this_user['email'];?></td>
                <td><?php echo $this_user['address'];?></td>

              </tr>
            <?php endforeach; ?>
          </table>

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
