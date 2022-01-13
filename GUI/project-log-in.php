<html>
    <head>
        <title>LinkedOut</title>
    </head>

    <h1>
        <center>
        Welcome to ... LinkedOut
        </center>
    </h1>

    <h3 align = "right">
        A CPSC 304 Project By Kevin Nguyen, Amy Yung, Colton Quan
    </h3>

    <h3>
        <u>Already have an account? Log in!</u>
    </h3>
    <form method="POST" action="project-log-in.php">
        Login ID: <input type="number" name="userID">  <br /><br />

        <input type="submit" value="LogIn!" name="logInSubmit"></p>
    </form>

    <body>
        New here? Sign up as a <a href="project-sign-up.php">User</a> or <a href="company-sign-up.php">Company</a>!
    </body>

    <?php
        require __DIR__ . '/db-util.php';

        $success = True;
        $db_conn = NULL;
        $show_debug_alert_messages = False;
        
        function handleLoginRequest() {
            global $db_conn;
            $userID = $_POST['userID'];
            $result = executePlainSQL("SELECT * FROM UserTable WHERE userID = " . $userID);
            if ($row = oci_fetch_array($result, OCI_BOTH)) {
                header("Location: ./profile.php?userID=" . $userID);
                exit;
            } else {
                echo "<br><br> User ID not found. Please try a different ID, or create an account. <br>";
            }
        }

        if (connectToDB()) {
            if (isset($_POST["logInSubmit"])) {
                handleLoginRequest();
            }
            disconnectFromDB();
        }
    
        ?>
</html>
