<html>
	<head>
        <title>Inbox</title>
    </head>

    <h1> 
        <center>
            Inbox 
        </center>
    </h1>



	<?php
        require __DIR__ . '/db-util.php';
        $success = True;
        $db_conn = NULL;
        $show_debug_alert_messages = False;
        $userID = $_GET['userID'];

        function printUserResult($result, $currID) { 
            global $userID;
            $query = executePlainSQL("SELECT uname FROM userTable WHERE userId = ". $currID." ");
            if ($name = oci_fetch_row($query)) {
                echo "You've reached the inbox of " . $name[0] . "<br><br>";
                echo "<br>";
                
                echo "<table>";
                echo "<tr><th>Sent by</th><th>Date</th><th>Message Body</th></tr>";

                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    if ($row[1] == $currID) {
                        echo "<tr><td>" . $row[5] . " (ID: " . $row[3] . ")" 
                        . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                        . "</td><td>" . $row[2]
                        . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" 
                        . "</td><td>" . $row[0] . "</td></tr>";
                    }
                }

                echo "</table>";
                echo "<br>";
                echo "<br>";
                echo 'All done? <a href="profile.php?userID=' . $userID . '">Go home</a>!';
            }
            else {
                echo 'The user ID you just entered does not exist, please try again';
            }
        }


        if (connectToDB()) {
            $result = executePlainSQL("SELECT mbody, rb.userID as receiverID, messageDate, m.userID as senderID, m.mid, ut.uname as senderName
                                        FROM Messages m, ReceivedBy rb, userTable ut 
                                        WHERE m.mid = rb.mid and m.userId = ut.userId
                                        ORDER BY messageDate desc");
            printUserResult($result, $userID);
        }
	?>
</html>
