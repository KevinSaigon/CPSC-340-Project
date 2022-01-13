<?php 
    function formatDate($date) {
        $time = strtotime($date);
        return date('Y-m-d',$time);
    }
    
    function displayMenu() {
        global $userID;
        echo <<<HTML
            <div style="float:left">
                <form method = "GET" action = "profile.php">
                    <input type = "hidden" id = "userID" name = "userID" value = "$userID" />  
                    <input type = "submit" value = "Profile" name = "profileSubmit"/> &nbsp;&nbsp;
                </form>
            </div>
            <div style="float:left">
                <form method = "GET" action = "inbox.php">
                    <input type = "hidden" id = "userID" name = "userID" value = "$userID" />  
                    <input type = "submit" value = "Inbox" name = "inboxSubmit"/> &nbsp;&nbsp;
                </form>
            </div>
            <div style="float:left">
                <form id = "form2" method = "GET" action = "outbox.php">
                    <input type = "hidden" id = "userID" name = "userID" value = "$userID" />  
                    <input type = "submit" value = "Send messages" name = "outboxSubmit"/> &nbsp;&nbsp;
                </form> 
            </div>
            <div style="float:left">
                <form method = "GET" action = "search-users.php">
                        <input type = "hidden" id = "userID" name = "userID" value = "$userID" />  
                        <input type = "submit" value = "People" name = "searchUsersSubmit"/> &nbsp;&nbsp;
                </form> 
            </div>
            <div style="float:left">
                <form method = "GET" action = "job-postings.php">
                        <input type = "hidden" id = "userID" name = "userID" value = "$userID" />  
                        <input type = "submit" value = "Job Postings" name = "jobPostingsSubmit"/> &nbsp;&nbsp;
                </form> 
            </div>
        HTML;
    }
?>
