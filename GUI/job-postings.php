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


            function displayFilters() {
                global $userID;
                echo <<<HTML
                    <form method="GET" action="job-postings.php">
                        <input type="hidden" id="userID" name="userID" value="$userID">
                        <br /><br />
                        <h4>Filters</h4>
                        <hr/>
                <select name="tables">
                    <option selected="selected">Choose an option to search from</option>
                    <option value="Company">Company</option>
                    <option value="Salary">Salary</option>
                    <option value="Job Title">Job Title</option>
                    <option value="Job Description">Job Description</option>
                </select>
                <input type="text" name="keywordSearch" placeholder="" />
                <hr/>
                        <input type="hidden" id="showPopular" name="displayPopularRequest">
                        <input type="submit" value="Sort by Most Popular Jobs" name="displayPopularPositions"></p>
                        <br />
                        <input type="hidden" id="showSalary" name="displaySalaryRequest">
                        <input type="submit" value="Show Jobs With Base Salary More Than $50,000" name="displaySalary"></p>
                        <br />
                        <input type="hidden" id="showMost" name="displayMostJobsRequest">
                        <input type="submit" value="Show Companies With Most Job Postings" name="displayMostJobs"></p>
                        <br /><br />
                <div>
                    <input type="submit" value="Search" name="searchSubmit"></p>
                </div>
                <hr/>
                    </form>
                HTML;
            }
            
            function handleRequests() {
                $baseQuery = "SELECT j.pid, hc.cname AS Company, j.jobTitle AS Position, d.baseSalary AS Salary, j.pbody AS Description FROM JobPost j, JobDesc d, HiringCompany hc WHERE hc.pid = j.pid AND d.jobTitle = j.jobTitle";
                     if (isset($_GET["displayPopularPositions"])) {
                         // Query: Aggregation with Group By
                         $baseQuery = "SELECT COUNT(jobTitle) as Job_Count, jobTitle
                         FROM JobPost
                         GROUP BY jobTitle
                         ORDER BY Count(jobTitle) DESC";
                    } else if (isset($_GET["displaySalary"])) {
                        // Query: Aggregation with Having
                        $baseQuery = "SELECT jobTitle, MIN(baseSalary) AS Base_Salary
                        FROM JobDesc
                        GROUP BY jobTitle
                        HAVING MIN(baseSalary) > 50000";
                    } else if (isset($_GET["displayMostJobs"])) {
                        // Query: Nested Aggregation with Group By
                        $baseQuery = "WITH tmp AS (SELECT h.cname AS hname, COUNT(j.pid) as jcount
                                     FROM HiringCompany h, JobPost j
                                     WHERE h.pid = j.pid
                                     GROUP BY h.cname)
                                     SELECT tmp.hname AS Company, tmp.jcount AS No_of_Postings
                                     FROM tmp
                                     WHERE tmp.jcount > (SELECT MIN(tmp.jcount) FROM tmp)";
                    } else if (isset($_GET['tables'])) {
                        $table = $_GET["tables"];
                        $keyword = $_GET["keywordSearch"];
                        
                        // Query: Selection
                        switch ($table) {
                                case "Company":
                                $baseQuery = "SELECT j.pid, hc.cname AS Company, j.jobTitle AS Position, d.baseSalary AS Salary, j.pbody AS Description FROM JobPost j, JobDesc d, HiringCompany hc WHERE hc.pid = j.pid AND d.jobTitle = j.jobTitle AND UPPER(hc.cname) = UPPER('$keyword')";
                                break;
                            case "Salary":
                                $baseQuery = "SELECT j.pid, hc.cname AS Company, j.jobTitle AS Position, d.baseSalary AS Salary, j.pbody AS Description FROM JobPost j, JobDesc d, HiringCompany hc WHERE hc.pid = j.pid AND d.jobTitle = j.jobTitle AND d.baseSalary >= $keyword";
                                break;
                            case "Job Title":
                                $baseQuery = "SELECT j.pid, hc.cname AS Company, j.jobTitle AS Position, d.baseSalary AS Salary, j.pbody AS Description FROM JobPost j, JobDesc d, HiringCompany hc WHERE hc.pid = j.pid AND d.jobTitle = j.jobTitle AND UPPER(j.jobTitle) = UPPER('$keyword')";
                                break;
                            case "Job Description":
                                $baseQuery = "SELECT j.pid, hc.cname AS Company, j.jobTitle AS Position, d.baseSalary AS Salary, j.pbody AS Description FROM JobPost j, JobDesc d, HiringCompany hc WHERE hc.pid = j.pid AND d.jobTitle = j.jobTitle AND UPPER(j.pbody) LIKE UPPER('%$keyword%')";
                                break;
                                default:
                                break;
                        }
                    }

                    return $baseQuery;
                }
            
                function displayJobPostings() {
                    global $userID;
                    
                    $query = handleRequests();
                    $result = executePlainSQL($query);
                    
                    if (isset($_GET["displayPopularPositions"])) {
                        echo "<br>Most Popular Jobs:<br>";
                        echo "<table>";
                        echo "<tr><th>Job Count</th><th>Position</th></tr>";
    
                   } else if (isset($_GET["displaySalary"])) {
                       echo "<br>Job Postings With Base Salary More Than $50,000:<br>";
                       echo "<table>";
                       echo "<tr><th>Position</th><th>Base Salary</th></tr>";
    
                   } else  if (isset($_GET["displayMostJobs"])) {
                       echo "<br>Comapnies With the Most Job Postings: <br>";
                       echo "<table>";
                       echo "<tr><th>Company</th><th># of Postings</th></tr>";
    
                   } else {
                       echo "<br>Job Postings:<br>";
                       echo "<table>";
                       echo "<tr><th>PID</th><th>Company</th><th>Position</th><th>Salary</th><th>Description</th></tr>";
                    }
    
                       while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                            if (isset($_GET["displayPopularPositions"])) {
                                 echo "<tr><td>" . $row["JOB_COUNT"] . "</td><td>" . $row["JOBTITLE"] . "</td></tr>";
                            } else if (isset($_GET["displaySalary"])) {
                                echo "<tr><td>" . $row["JOBTITLE"] . "</td><td>" . $row["BASE_SALARY"] . "</td></tr>";
                            } else if (isset($_GET["displayMostJobs"])) {
                               echo "<tr><td>" . $row["COMPANY"] . "</td><td>" . $row["NO_OF_POSTINGS"] . "</td></tr>";
                           } else {
                               echo "<tr><td>" . $row["PID"] . "</td><td>" . $row["COMPANY"] . "</td><td>" . $row["POSITION"] . "</td><td>" . $row["SALARY"] . "</td><td>" . $row["DESCRIPTION"] ."</td></tr>";
                           }
                       }
    
                    echo "</table>";
                }

            if (connectToDB()) {
                displayMenu();
                displayFilters();
                displayJobPostings();
                disconnectFromDB();
            } else {
                echo "<br><br> Unable to retrieve your details. Please refresh your browser. <br>";
            }
        ?>
        
    </body>

</html>
