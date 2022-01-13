<html>
    <head>
        <title>Home</title>
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
        <?php
            require __DIR__ . '/db-util.php';
            require __DIR__ . '/util.php';

            $success = True;
            $db_conn = NULL;
            $show_debug_alert_messages = False;
            $userID = $_GET['userID'];
            $profileID = $_GET["profileID"] ?? $userID;

            function displayUserAttributes() {
                global $userID, $profileID;
                $result = executePlainSQL("SELECT uName, age FROM UserTable WHERE userID = " . $profileID);
                if ($row = oci_fetch_array($result, OCI_BOTH)) {
                    $name = ($profileID === $userID) ? "Your" : trim($row["UNAME"]) . "'s";
                    $age = $row["AGE"];
                    echo " <br/><br/>"
                        . "<h3>{$name} Profile: </h3>"
                        . "<hr/>"
                        . "<b>Name: </b>"
                        . "&ensp;"
                        . "<p style='display:inline;'>" . $row["UNAME"] . "</p>"
                        . "<br /><br />"
                        . "<b>Age: </b>"
                        . "&ensp;"
                        . "<p style='display:inline;'>" . $age . "</p>"
                        . "<br /><br />";
                } else {
                    echo "<br><br> Unable to retrieve your details. Please refresh your browser. <br>";
                }
            }

            function displayWorkExperience() {
                global $userID, $profileID;
                $result = executePlainSQL("SELECT * "
                    . "FROM WorkExperience "
                    . "WHERE userID = " . $profileID . " "
                    . "ORDER BY startDate DESC");

                echo "<h3>Work Experience: </h3>";
                if ($userID === $profileID) {
                    echo <<<HTML
                        <form method="GET" action="profile.php">
                            <input type="hidden" id="userID" name="userID" value="$userID" />
                            <input type="submit" value="Add" name="add"></p>
                        </form>
                    HTML;
                }
                if (isset($_GET["add"])) {
                    echo <<<HTML
                        <form method="GET" action="profile.php">
                            <input type="hidden" id="userID" name="userID" value="$userID">
                            <input type="hidden" id="isAdd" name="isAdd" value="">
                            <b>Title: </b>
                            &ensp;
                            <input type="text" name="eTitle" />
                            <br /><br />
                            <b>Company: </b>
                            &ensp;
                            <input type="text" name="cName" />
                            <br /><br />
                            <b>Start Date: </b>
                            &ensp;
                            <input type="date" name="startDate">
                            <br /><br />
                            <b>End Date: </b>
                            &ensp;
                            <input type="date" name="endDate">
                            <br /><br />
                            <div style="float:left">
                                <input type="submit" value="Save" name="editSubmit"></p>
                            </div>
                            <div>
                                &ensp;
                                <input type="submit" value="Cancel" name="cancelEdit">
                            </div>
                        </form>
                    HTML;
                }
                echo "<hr />";
                while ($row = oci_fetch_array($result, OCI_BOTH)) {
                    $startDate = formatDate($row["STARTDATE"]);
                    $endDate = formatDate($row["ENDDATE"]);
                    $eTitle = trim($row['ETITLE']);
                    $cName = trim($row['CNAME']);
                    $eID = $row["EID"];
                    if (isset($_GET["edit"]) && $_GET["eID"] === $eID) {
                        echo <<<HTML
                            <form method="GET" action="profile.php">
                                <input type="hidden" id="userID" name="userID" value="$userID">
                                <input type="hidden" id="eID" name="eID" value="$eID">
                                <b>Title: </b>
                                &ensp;
                                <input type="text" name="eTitle" value="$eTitle" />
                                <br /><br />
                                <b>Company: </b>
                                &ensp;
                                <input type="text" name="cName" value="$cName" />
                                <br /><br />
                                <b>Start Date: </b>
                                &ensp;
                                <input type="date" name="startDate" value="$startDate">
                                <br /><br />
                                <b>End Date: </b>
                                &ensp;
                                <input type="date" name="endDate" value="$endDate">
                                <br /><br />
                                <div style="float:left">
                                    <input type="submit" value="Save" name="editSubmit"></p>
                                </div>
                                <div>
                                    &ensp;
                                    <input type="submit" value="Cancel" name="cancelEdit">
                                </div>
                            </form>
                        HTML;
                    } else {
                        echo <<<HTML
                            <b>Title: </b>
                            &ensp;
                            <p style="display:inline;">{$eTitle}</p>
                            <br /><br />
                            <b>Company: </b>
                            &ensp;
                            <p style="display:inline;">{$cName}</p>
                            <br /><br />
                            <b>Start Date: </b>
                            &ensp;
                            <p style="display:inline;">{$startDate}</p>
                            <br /><br />
                            <b>End Date: </b>
                            &ensp;
                            <p style="display:inline;">{$endDate}</p>
                            <br /><br />
                        HTML;
                        if ($userID === $profileID) {
                            echo <<<HTML
                                <form method="GET" action="profile.php">
                                    <input type="hidden" id="userID" name="userID" value="$userID">
                                    <input type="hidden" id="eID" name="eID" value="$eID">
                                    <div style="float:left">
                                        <input style="display:block" type="submit" value="Edit" name="edit">
                                    </div>
                                    <div>
                                        &ensp;
                                        <input type="submit" value="Delete" name="deleteSubmit">
                                    </div>
                                </form>
                            HTML;
                        }
                    }
                }
            }

            function displayDeleteAccountButton() {
                global $userID;
                echo <<<HTML
                    <br /><hr /><br />
                    <form method = "GET" action = "profile.php">
                        <input type = "hidden" id = "userID" name = "userID" value = "$userID" />  
                        <input type = "submit" value = "Delete My Account" name = "deleteAccountSubmit"/> &nbsp;&nbsp;
                    </form>
                HTML;
            }

            function handleAddRequest() {
                global $userID, $db_conn, $success;
                $eTitle = $_GET["eTitle"];
                $cName = $_GET["cName"];
                $startDate = formatDate($_GET["startDate"]);
                $endDate = formatDate($_GET["endDate"]);

                $result = executePlainSQL("SELECT MAX(eid) FROM WorkExperience");
                if (($row = oci_fetch_row($result)) != false) {
                    $eID = $row[0] + 1;
                } else {
                    $eID = 1;
                }
                
                // Query: Insert
                executePlainSQL("INSERT INTO WorkExperience "
                    . "(eTitle, eID, userID, startDate, endDate, cName) VALUES ( "
                    . "'" . $eTitle . "', "
                    . "'" . $eID . "', "
                    . "'" . $userID . "', "
                    . "DATE '" . $startDate . "', "
                    . "DATE '" . $endDate . "', "
                    . "'" . $cName . "')");

                oci_commit($db_conn);
                if ($success) {
                    header("Location: profile.php?userID=" . $userID);
                    exit;
                }
            }

            function handleEditRequest() {
                global $userID, $db_conn, $success;
                $eTitle = $_GET["eTitle"];
                $cName = $_GET["cName"];
                $startDate = formatDate($_GET["startDate"]);
                $endDate = formatDate($_GET["endDate"]);
                $eID = $_GET["eID"];

                // Query: Update
                executePlainSQL("UPDATE WorkExperience SET eTitle = '" . $eTitle . "', "
                    . "startDate = DATE '" . $startDate . "', "
                    . "endDate = DATE '" . $endDate . "', "
                    . "cName = '" . $cName . "' "
                    . "WHERE eID = '" . $eID . "' "
                    . "AND userID = " . $userID);
                oci_commit($db_conn);
                if ($success) {
                    header("Location: profile.php?userID=" . $userID);
                    exit;
                }
            }

            function handleDeleteRequest() {
                global $userID, $db_conn, $success;
                $eID = $_GET["eID"];

                executePlainSQL("DELETE FROM WorkExperience WHERE eID = '" . $eID . "'");
                oci_commit($db_conn);
                if ($success) {
                    header("Location: profile.php?userID=" . $userID);
                    exit;
                }
            }

            function handleDeleteAccountRequest() {
                global $userID, $db_conn, $success;

                // Query: Delete
                executePlainSQL("DELETE FROM UserTable WHERE userID = '" . $userID . "'");
                oci_commit($db_conn);
                if ($success) {
                    header("Location: ./project-log-in.php");
                    exit;
                }
            }

            if (connectToDB()) {
                displayMenu();
                displayUserAttributes();
                displayWorkExperience();
                displayDeleteAccountButton();
                if (isset($_GET["editSubmit"])) {
                    if (isset($_GET["isAdd"])) {
                        handleAddRequest();
                    } else {
                        handleEditRequest();
                    }
                } else if (isset($_GET["deleteSubmit"])) {
                    handleDeleteRequest();
                } else if (isset($_GET["deleteAccountSubmit"])) {
                    handleDeleteAccountRequest();
                }
                disconnectFromDB();
            } else {
                echo "<br><br> Unable to retrieve your details. Please refresh your browser. <br>";
            }

        ?>
        
    </body>

</html>
