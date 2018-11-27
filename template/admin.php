<?php
$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
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

            <h2>Users</h2>

            <form>
                <div class="align-center">
                    <p> </p>
                    <div class="btn-group" style="list-style-type:square">
                        <ul> John Smith </ul>
                        Harry Potter and the Deathly Hallows (Movie) <p> </p>
                        <button> Check In </button>
                        <p> </p>
                        Catcher in the Rye (Book) <p> </p>
                        <button> Check In </button>
                    </div>
                    <p> </p>
                </div>
                <p> </p>
            </form>

        </section>

        <!-- Footer -->
        <footer id="footer">
            <ul class="copyright">
                <li>&copy; Library Management System. All rights reserved.</li>
                <li>Design: HTML5 UP</li>
            </ul>
        </footer>

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