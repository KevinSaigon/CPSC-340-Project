<html>
    <head>
        <title>Sign Up</title>
    </head>

    <h1>
        <center>
        Welcome to ... LinkedOut
        </center>
    </h1>

    <h3 align = "right">
        A CPSC 304 Project By Kevin Nguyen, Amy Yung, Colton Quan
    </h3>

    <body>
        Welcome to the sign up page

        <h2>Insert Values into DemoTable</h2>
        <form method="POST" action="project-sign-up.php">
            <input type="hidden" id="createProfileRequest" name="createProfileRequest">
            Name: <input type="text" name="insUsername"> <br /><br />
            Login ID: <input type="number" name="insUserID">
            <p>This number must not overlap with any other user's number, if you get an error, please choose a different number </p>
            Age: <input type="number" name="insAge"> <br /><br />
            <br />

            <input type="submit" value="Create Profile" name="createProfileSubmit"></p>
            <br/>
            Change your mind? <a href="project-log-in.php">Go back</a>!
        </form>

        <?php
        require __DIR__ . '/db-util.php';

        $success = True;
        $db_conn = NULL;
        $show_debug_alert_messages = False;
        
        function handleCreateProfileRequest() {
            global $db_conn;

            $tuple = array (
                ":bind1" => $_POST['insUsername'],
                ":bind2" => $_POST['insAge'],
                ":bind3" => $_POST['insUserID'],
                ":bind4" => $_POST['insUserID']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into userTable values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
            OCICommit($db_conn);
        }

        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('createProfileRequest', $_POST)) {
                    handleCreateProfileRequest();
                }

                disconnectFromDB();
            }
        }

        if (isset($_POST['createProfileSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        } else if (isset($_GET['displayTupleRequest'])) {
            handleGETRequest();
        }

        ?>
    </body>

</html>
