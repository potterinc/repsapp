<?php
// Start the session 
session_start();
if ($_SESSION["username"] == '' AND $_SESSION["password"] == '') {
    header('Location: index.php');
}
?>

<?php include('qIconnection.php'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="set.css"> 
        <link rel="stylesheet" href="mybar.css">
        <link rel="stylesheet" type="text/css" href="css/manage.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Manage Users' Accounts</title>

        <?php
        $compNam = "";
        $sql = "SELECT companyName FROM companydetails";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $storevalue = $result->fetch_assoc();
            $compNam = $storevalue["companyName"];
        } else {
            $compNam = 'COMPANY NAME';
        }

        if (isset($_POST['dashboardbtn'])) {
            header('Refresh:0; URL= dashboard.php');
        }

        $luser = "";
        $lpass = "";
        if (isset($_POST['logoutbtn'])) {
            $lstdt = date("Y-m-d");

            $sql = "SELECT username, password FROM userscurrent";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $storevalue = $result->fetch_assoc();
                $luser = $storevalue["username"];
                $lpass = $storevalue["password"];

                $sql = "UPDATE users SET lastLogout='$lstdt' WHERE userName = '$luser' AND Password='$lpass'";

                if ($conn->query($sql) === TRUE) {
                    $logtime = '';
                } else {
                    $logtime = $conn->error;
                }
            }
            header('Refresh:0; URL= logout.php');
        }

        $usre = "";
        $pasd = "";
        $fstN = "";
        $lasN = "";
        $accT = "";
        $acsR = "";
        $sql = "SELECT username, password FROM userscurrent";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row 
            $storevalue = $result->fetch_assoc();
            $usre = $storevalue["username"];
            $pasd = $storevalue["password"];
        } else {
            echo '0 results';
        }

        $sql = "SELECT firstName, lastName, accountType, accessRight FROM users WHERE userName='$usre' AND Password='$pasd'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row 
            $storevalue = $result->fetch_assoc();
            $fstN = $storevalue["firstName"];
            $lasN = $storevalue["lastName"];
            $accT = $storevalue["accountType"];
            $acsR = $storevalue["accessRight"];
            echo '<p hidden id="accessR">' . $acsR . '</p>';
        } else {
            echo '0 results';
        }

        $compNme = "";
        $compLoc = "";
        $compNum = "";
        $noResults = "";
        $compBrd = "";
        $compAdd = "";
        $compPhn = "";

        $sql = "SELECT companyName, companyLocation, companyNumbers FROM companydetails";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $storevalue = $result->fetch_assoc();
            $compNme = $storevalue["companyName"];
            $compLoc = $storevalue["companyLocation"];
            $compNum = $storevalue["companyNumbers"];
        } else {
            $noResults = 'No Company Detail. ';
            $compBrd = '';
            $compAdd = 'Location: ..';
            $compPhn = 'Name: ... Phone Number: ...';
        }

        $updAct = "";
        $createRecord = "";
        $wrongPass = "";
        if (isset($_POST['adduserbtn'])) {
            $dtacc = $_POST['accounttype'];
            $dtaccess = $_POST['accessrights'];
            $dtuser = $_POST['username'];
            $dtfirst = $_POST['firstname'];
            $dtlast = $_POST['lastname'];
            $dtpass = $_POST['password'];
            $dtnewpass = $_POST['newpassword'];
            $lastD = date("Y-m-d");

            if ($dtpass == $dtnewpass) {

                $sql = "INSERT INTO users(accountType, accessRight, userName, firstName, lastName, Password)VALUES ('$dtacc', '$dtaccess', '$dtuser', '$dtfirst', '$dtlast', '$dtnewpass')";

                if ($conn->query($sql) === TRUE) {
                    $createRecord = 'New record created successfully. ';
                } else {
                    $createRecord = 'Error in password entry. ' . $conn->error;
                }

                $actv = ' Added New User Account- ' . $dtfirst . ' ' . $dtlast . '.';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

                if ($conn->query($sql) === TRUE) {
                    $updAct = ' User activity updated.';
                } else {
                    $updAct = ' Error updating user activity. ' . $conn->error;
                }
            } else {
                $wrongPass = ' Ensure Password typed in both fields are the same. ';
            }
            $next = header('Refresh:1; URL=manageusers.php');
            echo $next;
        }

        $hiddenid = "";
        $oldaccountType = "";
        $oldaccessRight = "";
        $oldfirstName = "";
        $oldlastName = "";
        $olduserName = "";
        $oldPassword = "";
        $hiddentext = "";
        $createResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];

            $sql = "SELECT id, accountType, accessRight, firstName, lastName, userName, Password, lastLogin, lastLogout FROM users WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldaccountType = $storevalue["accountType"];
                $oldaccessRight = $storevalue["accessRight"];
                $oldfirstName = $storevalue["firstName"];
                $oldlastName = $storevalue["lastName"];
                $olduserName = $storevalue["userName"];
                $oldPassword = $storevalue["Password"];
                $hiddentext = $storevalue["id"];
                $checkbox = 'Option Selected';
            } else {
                $createResults = '0 results for User. ';
            }
        }

        $updAct1 = "";
        $updateRecord = "";
        $wrongPass1 = "";
        if (isset($_POST['edituserbtn'])) {
            $dtacc = $_POST['accounttype'];
            $dtaccess = $_POST['accessrights'];
            $dtuser = $_POST['username'];
            $dtfirst = $_POST['firstname'];
            $dtlast = $_POST['lastname'];
            $dtpass = $_POST['password'];
            $dtnewpass = $_POST['newpassword'];
            $hiddenbox = $_POST["hiddenbox"];
            $lastD = date("Y-m-d");

            if ($dtpass == $dtnewpass) {

                $sql = "UPDATE users SET accountType='$dtacc', accessRight='$dtaccess', userName='$dtuser', firstName='$dtfirst', lastName='$dtlast', Password='$dtnewpass' WHERE id=$hiddenbox";
                // sql to update a record
                if ($conn->query($sql) === TRUE) {
                    $updateRecord = 'Record updated successfully. ';
                } else {
                    $updateRecord = 'Error updating record: ' . $conn->error;
                }

                $actv = ' Updated Existing User Account- ' . $dtfirst . ' ' . $dtlast . '.';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

                if ($conn->query($sql) === TRUE) {
                    $updAct1 = ' User activity updated.';
                } else {
                    $updAct1 = ' Error updating user activity. ' . $conn->error;
                }
            } else {
                $wrongPass1 = ' Ensure Password typed in both fields are the same. ';
            }
            $next = header('Refresh:1; URL=manageusers.php');
            echo $next;
        }

        $updAct2 = "";
        $removeRecord = "";
        if (isset($_POST['removeuserbtn'])) {
            $hiddenbox = $_POST["hiddenbox"];
            $lastD = date("Y-m-d");
            // sql to delete a record 

            $sql = "DELETE FROM users WHERE id=$hiddenbox";

            if ($conn->query($sql) === TRUE) {
                $removeRecord = 'Record deleted successfully. ';
            } else {
                $removeRecord = 'Error deleting record: ' . $conn->error;
            }

            $actv = ' Removed User Account with Id ' . $hiddenbox . '.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct2 = ' User activity updated.';
            } else {
                $updAct2 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=manageusers.php');
            echo $next;
        }

        $updAct3 = "";
        $createComp = "";
        $updateComp = "";
        $compName = "";
        if (isset($_POST['companyEditBtn'])) {
            $compName = $_POST["companyname"];
            $compLoct = $_POST["companylocation"];
            $compNumb = $_POST["companynumbers"];
            $lastD = date("Y-m-d");

            $sql = "SELECT companyName, companyLocation, companyNumbers FROM companydetails";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {

                $sql = "UPDATE companydetails SET companyName='$compName', companyLocation='$compLoct', companyNumbers='$compNumb'";
                // sql to update a record
                if ($conn->query($sql) === TRUE) {
                    $updateComp = 'Company detail Updated successfully. ';
                } else {
                    $updateComp = 'Error Updating Company detail: ' . $conn->error;
                }
            } else {

                $sql = "INSERT INTO companydetails(companyName, companyLocation, companyNumbers)VALUES ('$compName', '$compLoct', '$compNumb')";

                if ($conn->query($sql) === TRUE) {
                    $createComp = 'Company detail Entered successfully. ';
                } else {
                    $createComp = 'Error Entering Company detail. ' . $conn->error;
                }
            }

            $actv = ' Entered Company: ' . $compName . ' detail.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct3 = ' User activity updated.';
            } else {
                $updAct3 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=manageusers.php');
            echo $next;
        }

        $updAct4 = "";
        $resetComp = "";
        if (isset($_POST['resetBtn'])) {
            $lastD = date("Y-m-d");
            // sql to delete a record 

            $sql = "DELETE FROM companydetails";

            if ($conn->query($sql) === TRUE) {
                $resetComp = 'Company detail Reset successfully. ';
            } else {
                $resetComp = 'Error Reseting Company detail: ' . $conn->error;
            }

            $actv = ' Reset Company detail ';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct4 = ' User activity updated.';
            } else {
                $updAct4 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=manageusers.php');
            echo $next;
        }
        ?>
    
    </head>
    <body>
        <div class="container"><br>
        <div class="col-sm-12 col-md-12">
            <form class="mg-top-5" method="POST" action="<?php $_PHP_SELF ?>">
                <button class="pull-left btn btn-danger" data-toggle="tooltip" title="Logout" name="logoutbtn">LOGOUT&nbsp;<span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;<?php echo $fstN . " " . $lasN; ?></button>
                <span class="label label-info text-capitalize pull-right"><h5><?php echo $compNam; ?></h5></span>
                <div class="pull-right btn-group">
                    <button class="btn btn-primary" name="dashboardbtn" data-toggle="tooltip" title="Goto Dashboard">PROCEED TO DASHBOARD&nbsp;<span class="glyphicon glyphicon-dashboard"></span></button>
                </div>
            </form>
            </div>
        </div><hr class="line-black" />

        <div class="container-fluid">
            <div class="row">
                <div id="main" class="menubar btn-primary"> 
                    <i class="my-opennav my-xlarge" onclick="my_open()">&#9776; MENU</i>
                    <span class="conamebar right">
                        
                </div>
            </div>
        </div>

        <nav class="my-sidenav my-white my-card-2 sidebar" style="display:none">
            <a href="javascript:void(0)" 
               onclick="my_close()"
               class="my-closenav my-large">&times;</a>
            <div> <label>Stock</label> <ul><li><a href="viewItems.php">View Items</a></li><li><a href="newItem.php">Add New Item</a></li></ul> </div>
            <div id="purchase"> <label>Purchase</label> <ul><li><a href="orderList.php">Order List</a></li><li><a href="receivedHistory.php">View Received Item History</a></li></ul> </div>
            <div> <label>Accounts</label> <ul><li><a href="payin.php">Income</a></li><li id="expense"><a href="payout.php">Expenses</a></li><li id="balance"><a href="payBalance.php">Book Balance</a></li></ul> </div>
            <div id="credits"> <label><a href="credits.php">Credits</a></label> </div>
            <div id="returns"> <label><a href="returnedItems.php">All Returned Items</a></label> </div>
            <div id="customers"> <label><a href="customerHistory.php">Customers' History</a></label> </div>
            <div id="suppliers"> <label><a href="supplierHistory.php">Suppliers' History</a></label> </div>
            <div id="report"> <label><a href="report.php">Report</a></label> </div>
            <div> <label><a href="about.php">About Company</a></label> </div><br/>
            <div id="mnguser"> <label style="border-top:2px solid black;"><a href="manageusers.php">Manage Users' Accounts</a></label></div>
        </nav>
        <i class="alertbar"><?php echo $checkbox . $noResults . $createRecord . $wrongPass . $wrongPass1 . $createResults . $updateRecord . $removeRecord . $updateComp . $createComp . $resetComp . $updAct . $updAct1 . $updAct2 . $updAct3 . $updAct4; ?></i> 
        <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">Manage Users' Account&nbsp;&nbsp;<span class="glyphicon glyphicon-cog"></span></span></h1></header>
        </div><hr class="line-blue">

        <div class="container-fluid">
            <form action='<?php echo $_SERVER["PHP_SELF"]; ?>' method='POST'>
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">  
                        <h4 class="">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br/>
                    </div>
                </div>
                <div class="row">
                    <div  class="col-md-1 col-sm-1">
                        Account Type: <br/><br/>
                        Username:
                    </div>
                    <div  class="col-md-3 col-sm-3">
                        <input type="text" name="accounttype" value= '<?php echo $oldaccountType; ?>' ><br/><br/>
                         <input type="text" name="username" value= '<?php echo $olduserName; ?>' ><br/><br/>
                    </div>
                    <div  class="col-md-1 col-sm-1">
                        Firstname: <br/><br/>
                        Password:
                    </div>
                    <div  class="col-md-3 col-sm-3">
                        <input type="text" name="firstname" value= '<?php echo $oldfirstName; ?>' ><br/><br/>
                         <input type="password" name="password" value= '<?php echo $oldPassword; ?>' ><br/><br/>
                    </div>
                    <div  class="col-md-1 col-sm-1">
                        Lastname: <br/><br/>
                        Confirm Password:
                    </div>
                    <div  class="col-md-3 col-sm-3">
                        <input type="text" name="lastname" value= '<?php echo $oldlastName; ?>' ><br/><br/>
                         <input type="password" name="newpassword" value= '<?php echo $oldPassword; ?>' ><br/><br/>
                    </div>
                </div>
                <div class="row"> 
                        <input class="pull-right" type='hidden' name='hiddenbox' value= '<?php echo $hiddentext; ?>' >
                    <div  class="col-md-1 col-sm-1">
                        Access Right:
                    </div>
                    <div  class="col-md-2 col-sm-2">
                        <input list="accessright" name="accessrights" value= '<?php echo $oldaccessRight; ?>' >
                            <datalist id="accessright">
                                <option value="Admin"></option>
                                <option value="Supervisor"></option>
                                <option value="Sales Person"></option>
                            </datalist>
                    </div>
                    <div  class="col-md-3 col-sm-3">
                        <button class="btn btn-danger" name="removeuserbtn" style="vertical-align:middle">REMOVE USER ACCOUNT&nbsp;&nbsp;<span class="glyphicon glyphicon-remove"></span></button>
                    </div>

                    <div  class="col-md-3 col-sm-3">
                        <button class="btn btn-primary" name="edituserbtn" style="vertical-align:middle">UPDATE USER ACCOUNT&nbsp;&nbsp;<span class="glyphicon glyphicon-ok-circle"></span></button>
                    </div>
                    <div  class="col-md-3 col-sm-3">
                        <button class="btn btn-success" name="adduserbtn" style="vertical-align:middle">ADD NEW USER ACCOUNT&nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></button>
                    </div>
                </div><br/>
                <div class="row">
                    <div  class="col-md-12 col-sm-12">
                        <div class="tablecontainer" title="Click Checkbox To Select">
                            <?php
                            $checkboxform = "<form action='manageusers.php' method='POST' name='form1'>";
                            $checkbox = "<input type='submit' value='' name='box'> <br/>";
                            $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                            $hiddenboxend = "'>";

                            $sql = "SELECT id, accountType, accessRight, firstName, lastName, userName, Password, lastLogin, lastLogout FROM users";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table id='itemlist'>
                                            <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Account Type</th><th>Access Right</th><th>Firstname</th><th>Lastname</th><th>Username</th><th>Password</th><th>Last Login Date</th><th>Last Logout Date</th></tr>";
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["accountType"] . "</td><td>" . $row["accessRight"] . "</td><td>" . $row["firstName"] . "</td><td>" . $row["lastName"] . "</td><td>" . $row["userName"] . "</td><td>" . $row["Password"] . "</td><td>" . $row["lastLogin"] . "</td><td>" . $row["lastLogout"] . "</td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "0 results";
                            }
                            ?> 
                        </div> 
                    </div> 
                </div><br/>
                <div class="row"> 
                    <div  class="col-md-9 col-sm-9"> 
                        <form action='activitylog.php' method='POST'>
                            <input type='hidden' name='hidbox1' value= '<?php echo $oldfirstName; ?>' >
                            <input type='hidden' name='hidbox2' value= '<?php echo $oldlastName; ?>' > 
                            <button class="btn btn-success" name="activityBtn" style="vertical-align:middle"  title="First select user and enter dates for search"><span class="btntext">Click To View User's Activity Log</span> <span class="glyphicon glyphicon-send pull-right"></span></button>
                            <span class="dates">By Date: From <span class="glyphicon glyphicon-calendar space"></span> <input type="date" name="begindate"></span> 
                            <span class="space dates"> To <span class="glyphicon glyphicon-calendar space"></span> <input type="date" name="enddate"></span>
                        </form>
                    </div>
                    <div  class="col-md-3 col-sm-3"> 
                        <span class="cancelsales editcompany pull-right" title="Enter new company addresses and locations or edit existing">Click To Enter  Company Details <span class="glyphicon glyphicon-send"></span></span><br/><br/> 
                    </div> 
                </div><br/>
                <div class="row"> 
                    <div  class="col-md-12 col-sm-12"> 
                        <span id="marqueeHead">Today's Activity</span> 
                        <span id="marqueeBody"><marquee> 
                        <?php
                        $today = date("Y-m-d");
                        $sql = "SELECT firstname, lastname, activity FROM usersactivity WHERE dates='$today'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($storevalue = $result->fetch_assoc()) {
                                $alfirst = $storevalue["firstname"];
                                $allast = $storevalue["lastname"];
                                $allact = $storevalue["activity"];
                                echo $alfirst . ' ' . $allast . ': ' . $allact . ' || ';
                            } echo '<i> any more user activity will be updated by the system !!! </i>';
                        } else {
                            echo 'No Relevant Activity Today.';
                        }
                        ?> 
                            </marquee></span> 
                    </div>
                </div>
            </form> 
        </div>
        <div class="clearfix"></div><br/>
        <div class="container-fluid copy">
                <footer class="text-center">
                    <small>&copy; 2018 All rights Reserved, Intelogiic Global Resources</small>
                </footer>
        </div>

        <div class="clearfix"></div>
        <!-- The Company Edit Modal -->
        <div id="myModal" class="my-modal">
            <div class="my-modal-content my-animate-top my-card-4">
            <div class="my-container text-center">
                <span class="my-closebtn" onclick="document.getElementById('myModal').style.display='none';">&times;</span>
                <form action="manageusers.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate>
                    <h1 class="my-center"><span class="label label-primary">Enter Company Details&nbsp;<span class="glyphicon glyphicon-home"></span></span></h1><hr class="line-black">
                    <input type="text" name="companyname" placeholder="Company Name" value="<?php echo $compNme . $compBrd; ?>" required><br/><br/> 
                    <textarea name="companylocation" placeholder="<?php echo $compAdd; ?>" required><?php echo $compLoc; ?></textarea><br/>
                    <textarea name="companynumbers" placeholder="<?php echo $compPhn; ?>" required><?php echo $compNum; ?></textarea><br/><br/>
                    <div class="col-md-6 col-sm-6">
                    <button class="btn btn-primary pull-right" name="companyEditBtn" style="vertical-align:middle">CONFIRM UPDATE&nbsp;<span class="glyphicon glyphicon-floppy-save"></span></button>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <button class="btn btn-success pull-left" name="resetBtn">OTHERWISE RESET <span class="glyphicon glyphicon-repeat"></span></button>
                    </div><br/><br/><br/>
                </form> 
            </div> 
        </div>

        <script src="mymodalscript.js"></script>
        <script src="js/myscript.js"></script> 
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <script>
              var accR = document.getElementById("accessR").innerHTML;
              if (accR == "Supervisor" || accR == "Sales Person") {
                  document.getElementById("mnguser").style.display = "none";
                  document.getElementById("report").style.display = "none";
              }

              if (accR == "Sales Person") {
                  document.getElementById("purchase").style.display = "none";
                  document.getElementById("expense").style.display = "none";
                  document.getElementById("balance").style.display = "none";
                  document.getElementById("credits").style.display = "none";
                  document.getElementById("returns").style.display = "none";
                  document.getElementById("customers").style.display = "none";
                  document.getElementById("suppliers").style.display = "none";
              }

        </script> 

    </body>
</html>