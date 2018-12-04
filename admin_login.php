<?php
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
if (isset($_POST['email'])) {
    $email = $_POST["email"];
    $password = md5($_POST["password"]);

    $sql = "SELECT email, password FROM users WHERE users.email='$email' AND users.password ='$password' AND users.role_ID = 22";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        // echo "found something";
        // session_name("current session");
        session_start();
        $_SESSION["email"] = $email;
        // echo print_r($_SESSION);


        $time = (string)time();
        $query = "INSERT INTO sessions (start_date, end_date) VALUES ('$time', ' ')";
        $result = $conn->query($query);

        if($result === TRUE){
          $query = "SELECT session_id FROM sessions WHERE start_date = $time";
          $result = $conn->query($query);
          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['session_id'] = $row['session_id'];
            echo $_SESSION['session_id'];
          }

          $sql = "SELECT user_id FROM users WHERE email='$email' AND password ='$password' AND role_ID = 22";
          $result = $conn->query($sql);
          $row = $result->fetch_assoc();
          $user = (int)$row['user_id'];
          // echo "user is" .$user . "end user";

          $_SESSION['uid'] = $user;
          header('Location: admin.php');
        }

    } else {
        echo "The email and/or password you entered are invalid";
    }

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
					</nav>
				</header>

			<!-- CTA -->
				<section id="cta">

					<h2>Admin Log In</h2>

					<form method="post">
						<div class="align-center">
							<div class="align-center">
								<input class="align-center" type="email" name="email" id="email" placeholder="Email Address" />
							</div>
							<p> </p>
							<div class="align-center">
								<input class="align-center" type="password" name="password" id="password" placeholder="Password" />
							</div>
						</div>
						<p> </p>
						<div class="align-center">
							<div class="col-4 col-12-mobilep">
								<input class="align-center" type="submit" value="Sign In" class="fit" />
							</div>
						</div>
					</form>

				</section>

			<!-- Footer -->
				<!-- <footer id="footer">
					<ul class="copyright">
						<li>&copy; Library Management System. All rights reserved.</li><li>Design: HTML5 UP</li>
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
