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

$sql = "SELECT * FROM books";
$result = $conn->query($sql);
$books =[];
$isbns = [];
$count = 0;
$search_results = NULL;
while ($row = $result->fetch_assoc()) {
    $isbns[] = $row['ISBN'];
    $books[] = $row['book_title'] . ", ". $row['book_author'] . ", " . $row['book_genre'];
    // echo $row['book_title'] . ", ". $row['book_author'] . ", " . $row['book_genre'];
}

//borrow form
if (isset($_POST['id'])) {
  // session_start();
  $id = (int)$_POST["id"];
  // echo $id;

  $sql = "SELECT * FROM bookcopies WHERE isbn = $id";
  $result = $conn->query($sql);
  // echo print_r($result);

  if ($result->num_rows > 0) {
    $borrowed = FALSE;
    $num_rows = $result->num_rows;
    $count = 0;

    while($count < $num_rows) {
      // echo $result->num_rows;
      $row = $result->fetch_assoc();
      // echo print_r($row);
      // echo "count" . $count;

      if ($row['book_status'] === "in"){
        $copy = (int)$row['copy_ID'];

        $uid = $_SESSION["uid"];
        // echo print_r($_SESSION);
        // echo $uid;
        $sql = "UPDATE bookcopies SET book_status='out', user_id='$uid' WHERE copy_ID = $copy";
        $result = $conn->query($sql);

        if($result === TRUE){
          $borrowed = TRUE;

          $session = $_SESSION['session_id'];
          // echo "session" . $session;
          $query = "INSERT INTO transactions (transaction_desc, session_ID) VALUES ('borrowing a book', $session)";
          $res = $conn->query($query);

          if($res === TRUE){
            echo "Book Borrowed Successfully!";
            break;
          }
        }
      }
      $count = $count +1;
    }
    if($borrowed == FALSE){
      echo "No Available Copies of this Book";
    }
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

//search form
if (isset($_POST['search'])) {
  $search = $_POST['search'];
  $search_str = "%" . $search . "%";

  // echo $search_str;
  $query = "SELECT * FROM books WHERE (isbn LIKE '$search_str') OR (book_title LIKE '$search_str') OR (book_author LIKE '$search_str')";
  // $query = "SELECT * FROM books WHERE isbn ='$search'";
  $result = $conn->query($query);
  // echo print_r($result);

  $search_results = [];

  $num_rows = $result->num_rows;
  $count = 0;

  while($count < $num_rows) {
    $row = $result->fetch_assoc();
    $string = "ISBN: " . $row['ISBN'] . " | " . $row['book_title'] . ", " . $row['book_author'];
    $search_results[] = $string;
    $count = $count +1;
    // echo print_r($result);
  }

  // while ($row = $result->fetch_assoc()) {
  //      $result = $row['isbn'] . " | " . $row['book_title'] . ", " . $row['book_author'];
  //      $search_results[] = $result;
  //      echo $result;
  // }

}

?>

<html>

<head>
    <title>Library Management System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body class="landing is-preload">
    <div id="page-wrapper">
        <!-- Header -->
        <header id="header" class="alt">
            <h1>Library Management System</h1>
            <nav id="nav">
                <!-- <ul>
							<li><a href="index.html">Home</a></li>
							<li><a href="#" class="button">Sign Up</a></li>
						</ul> -->
            <form method="post"> <input type="hidden" id="logout" name="logout" value="logout"> <button type="submit" class="align-right">Log Out</button></form>
            </nav>

        </header>
        <!-- CTA -->
        <section id="cta">

            <form method="post">
              <div class="align-center">
                  <div class="align-center">
                      <input class="align-center" type="text" id="search" name="search" placeholder="Search" />
                      <p> </p>
                      <input class="align-center" type="submit" id="search_button" value="Go" />
                  </div>
            </form>

            <?php if($search_results !== NULL) : ?>

              <h3>Search Results</h3>

              <?php foreach($search_results as $item): ?>
              <div class="btn-group">
                <?php echo $item;?></br>
              </div>
              <p> </p>
              <?php endforeach; ?>
            <?php endif; ?>

            <p> </p>

            <h3>Browse Items</h3>

            <?php foreach($books as $this_book): ?>
            <form method="post">              
              <div class="btn-group">
                <?php echo $this_book; $count = $count +1;?></br>
                <button type="submit"> Borrow </button>
                <input type="hidden" id="id" name="id" value="<?php echo $isbns[$count-1];?>">
              </div>
              <p> </p>
            </form>
            <?php endforeach; ?>

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
