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

            function displaySearchBars() {
                global $userID;
                echo <<<HTML
                    <form method="GET" action="search-users.php">
                        <input type="hidden" id="userID" name="userID" value="$userID">
                        <br /><br />
                        <hr/>
                        <br/>
                        <b>Filter People by Name: </b>
                        <input type="text" name="name" placeholder="John Doe" /> 
                        <br /><br />
                        <b>Filter People by Company: </b>
                        <input type="text" name="companies" placeholder="Amazon, IBM" />
                        <br /><br />
                        <b>Recruiter </b>
                        <input type="checkbox" name="isRecruiter" />
                        <br /><br />
                        <div>
                            <input type="submit" value="Search" name="searchSubmit"></p>
                        </div>
                        <hr/>
                    </form>
                HTML;
            }

            function createQuery() {
                $name = trim($_GET["name"]);
                $companies = array_filter(
                    array_map('trim', explode(",", $_GET["companies"])),
                    function($c) { return !empty($c); }, 
                    ARRAY_FILTER_USE_BOTH);
                // Query: Projection
                $baseQuery = "SELECT u.userID, u.uName, u.age FROM UserTable u"
                . " WHERE UPPER(u.uName) LIKE UPPER('%$name%')";
                if (isset($_GET["isRecruiter"])) {
                    // Query: Join
                    $baseQuery = "SELECT u.userID, u.uName, u.age"
                        . " FROM UserTable u, Recruiter r"
                        . " WHERE u.userID = r.userID"
                        . " AND UPPER(u.uName) LIKE UPPER('%$name%')";
                }
                if (count($companies) === 0) {
                    return $baseQuery;
                } else {
                    $companyQuery = implode(" or ", array_map(function($c) { return "c.cName = '$c'"; }, $companies));
                    // Query: Division
                    return $baseQuery
                        . " AND NOT EXISTS ((SELECT c.cName FROM Company c"
                        . " WHERE ($companyQuery))"
                        . " MINUS (SELECT e.cName FROM WorkExperience e"
                        . " WHERE e.userID = u.userID))";
                }
            }

            function displayUsers() {
                global $userID;
                $query = createQuery();
                $result = executePlainSQL($query);
                echo "<table>";
                echo "<tr><th>User ID</th><th>Name</th><th>Age</th></tr>";
                while ($row = oci_fetch_array($result, OCI_BOTH)) {
                    $name = trim($row["UNAME"]);
                    $uID = $row["USERID"];
                    $age = $row["AGE"];
                    echo "<tr>";
                    echo "<td>$uID</td>";
                    echo "<td><a style='color:blue' href='profile.php?userID=$userID&profileID=$uID'>$name</a></td>";
                    echo "<td>$age</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

            if (connectToDB()) {
                displayMenu();
                displaySearchBars();
                displayUsers();
                disconnectFromDB();
            } else {
                echo "<br><br> Unable to retrieve your details. Please refresh your browser. <br>";
            }


        ?>
        
    </body> 

</html>
