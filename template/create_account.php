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
if (isset($_POST['fname'])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $password = md5($_POST["password"]);

    $sql = "INSERT INTO users (role_ID,first_name,last_name,email,address,password) VALUES (11, '$fname', '$lname', '$email', '$address', '$password');";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        session_start();
        $_SESSION["email"] = $email;
        header('Location: login.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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

            <h2>Create Account</h2>

            <form method="post">
                <div class="align-center">
                    <div class="align-center">
                        <input class="align-center" type="text" name="fname" id="firstname" placeholder="First Name" />
                    </div>
                    <p> </p>
                    <div class="align-center">
                        <input class="align-center" type="text" name="lname" id="lastname" placeholder="Last Name" />
                    </div>
                    <p> </p>
                    <div class="align-center">
                        <input class="align-center" type="text" name="address" id="address" placeholder="Home Address" />
                    </div>
                    <p> </p>
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
                        <input class="align-center" id="submit_button" type="submit" value="Sign In" class="fit" />
                    </div>
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
