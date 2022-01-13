<html>
    <head> 
        <title> Outbox </title>
    </head> 

    <h1> 
        <center>
            Outbox 
        </center>
    </h1>

    <?php
        if ($_GET['userID'] == NULL) {
            $userID = $_POST['userID'];
        }
        else {
            $userID = $_GET['userID'];
        }
        $db_conn = NULL;

        function connectToDB() {
            global $db_conn;

            $db_conn = OCILogon("ora_kn2001", "a97703045", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error();
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function executePlainSQL($cmdstr) {
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr); 

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); 
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        if (connectToDB()) {
            $queryResult = executePlainSQL("SELECT * FROM userTable WHERE userid =" . $userID );
            if (($row= oci_fetch_row($queryResult)) != false) {
                $currName = $row[0];
            }
            disconnectFromDB(); 
        }
    ?>

    <body>
        Welcome to the outbox! <br />
        Here, you'll be able to send messages to all available  users, click button below to see all available users
        
        <br />
        <br />
        
        <form method="GET" action="outbox.php"> 
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">            
            <input type="hidden" id="bleh" name="userID" value = <?php echo $userID;?>>
            <input type="submit" value = "View all" name="displayTuples"></p>
        </form>

        <br />
        <br />

        <form method="POST" action="outbox.php?">
            <input type="hidden" id="sendMessageRequest" name="sendMessageRequest">
            <input type="hidden" id="bleh" name="userID" value = <?php echo $userID;?>>
            From: <?php echo $currName ?> &nbsp;&nbsp;&nbsp;
            To (please enter their user ID): <input type="number" name="recipientID"> 
            <br />
            <br />

            <textarea id="messageBody" name="messageBody" rows="12" cols="50"> </textarea>
            <br />
            <br />

            <input type="submit" value="Send Message" name="sendMessageSubmit"></p>
        </form>

        <?php

        $success = True;
    
        $show_debug_alert_messages = False; 

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }


        function executeBoundSQL($cmdstr, $list) {

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    OCIBindByName($statement, $bind, $val);
                    unset ($val);
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement);
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) {
            echo "<br>Available Users :<br>";
            echo "<br>";
            
            echo "<table>";
            echo "<tr><th>User ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[2] . "</td><td>" . $row[0] . "</td></tr>";
            }

            echo "</table>";
        }


        function handleDisplayRequest() {
            global $db_conn;
            
            $result = executePlainSQL("SELECT * FROM userTable");
            printResult($result);
        }

        function handleSendMessageRequest() {
            global $db_conn;
            global $userID;


            $result = executePlainSQL("SELECT MAX(mid) FROM Messages");
            if (($row = oci_fetch_row($result)) != false) {
                $newMID = $row[0] + 1;
            }
            $messageBody = $_POST['messageBody'];
            $messageBody = str_replace("'", "''", $messageBody);

            date_default_timezone_set('America/Los_Angeles');
            $todayDate = date("F j, Y");

            $senderID = $userID;
            $receiverID = $_POST['recipientID'];

            executePlainSQL("insert into Messages values(". $newMID . ", '". $messageBody ."' , 
                                    to_date('". $todayDate ."', 'MONTH DD, YYYY'), ". $senderID." )"
            );

            executePlainSQL("insert into Contains values(". $receiverID .", ". $newMID .")");

            executePlainSQL("insert into ReceivedBy values(". $newMID .", ". $receiverID .")");
            
            echo "<br>";
            echo "<br>";
            echo 'All done sending emails? <a href="profile.php?userID=' . $userID . '">Go home</a>!';
            
            OCICommit($db_conn);
        }

        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('sendMessageRequest', $_POST)) {
                    handleSendMessageRequest();
                }
                disconnectFromDB();
            }
        }

        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('displayTuples', $_GET)) {
                    handleDisplayRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['sendMessageSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayTupleRequest'])) {
            handleGETRequest();
        }

        ?>
        
    </body> 

</html>