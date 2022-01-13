<html>
    <head>
        <title>Company Sign Up</title>
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
        Welcome to the company sign up page

        <h2>Insert Values into DemoTable</h2>
        <form method="POST" action="company-sign-up.php">
            <input type="hidden" id="createCompanyProfileRequest" name="createCompanyProfileRequest">
            Name: <input type="text" name="insCompanyName"> <br /><br />
            Postal Code: <input type="text" name="insPostalCode">
            <br />
            <br/>
            <input type="submit" value="Create Company Profile" name="createCompanyProfileSubmit"></p>
        </form>


        <h2>Display the Tuples in Company Table</h2>
        <form method="GET" action="company-sign-up.php">
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display Tuples" name="displayTuples"></p>
        </form>
        <br/>
        Change your mind? <a href="project-log-in.php">Go back</a>!
        <?php
        require __DIR__ . '/db-util.php';

        $success = True;
        $db_conn = NULL;
        $show_debug_alert_messages = False;
        
        function printResult() {
            global $db_conn;
            
            // Query: Selection
            $result = executePlainSQL("SELECT * FROM Company");
            
            echo "<br>Retrieved data from table Company:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["CNAME"] . "</td><td>" . $row["POSTALCODE"] . "</td></tr>";
            }

            echo "</table>";
        }

        function handleCreateCompanyProfileRequest() {
            global $db_conn;

            $tuple = array (
                ":bind1" => $_POST['insCompanyName'],
                ":bind2" => $_POST['insPostalCode']
            );

            $alltuples = array (
                $tuple
            );

            // Query: Insert
            executeBoundSQL("INSERT INTO Company values (:bind1, :bind2)", $alltuples);
            OCICommit($db_conn);
        }

        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('createCompanyProfileRequest', $_POST)) {
                    handleCreateCompanyProfileRequest();
                }

                disconnectFromDB();
            }
        }

        function handleGETRequest() {
            if (connectToDB()) {
                 if (array_key_exists('displayTuples', $_GET)) {
                    printResult();
                }

                disconnectFromDB();
            }
        }

        if (isset($_POST['createCompanyProfileSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayTupleRequest'])) {
            handleGETRequest();
        }

        ?>
    </body>

</html>
