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
        <link rel="stylesheet" type="text/css" href="css/activity.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Users' Activity Log</title>

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

        $sql = "SELECT firstName, lastName, accountType FROM users WHERE userName='$usre' AND Password='$pasd'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row 
            $storevalue = $result->fetch_assoc();
            $fstN = $storevalue["firstName"];
            $lasN = $storevalue["lastName"];
            $accT = $storevalue["accountType"];
        } else {
            echo '0 results';
        }

        $updAct = "";
        $actDel = "";
        if (isset($_POST['dateClearBtn'])) {
            $hid1 = $_POST["firsthidden"];
            $hid2 = $_POST["lasthidden"];
            $begind = $_POST['beginhidden'];
            $endd = $_POST['endhidden'];
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM usersactivity WHERE firstname='$hid1' AND (dates='$begind' OR (dates<'$endd' AND dates>'$begind') OR dates='$endd') AND lastname='$hid2'";

            if ($conn->query($sql) === TRUE) {
                $actDel = ' User Activity between ' . $begind . ' and ' . $endd . ' Cleared. ';
            } else {
                $actDel = ' Error Clearing User Activity between ' . $begind . ' and ' . $endd . ':' . $conn->error;
            }

            $actv = ' Cleared User Activity between ' . $begind . ' and ' . $endd . ' from this list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct = ' User activity updated.';
            } else {
                $updAct = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:5; URL=manageusers.php');
            echo $next;
        }

        if (isset($_POST['backBtn'])) {
            header('Refresh:0; URL= manageusers.php');
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
                <div  class="col-md-12 col-sm-12 text-center">
                    <h4 class="">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="btn-primary text-center"> 
                <?php
                    if (isset($_POST['activityBtn'])) {
                        $hidden1 = $_POST["hidbox1"];
                        $hidden2 = $_POST["hidbox2"];
                        $begindate = $_POST['begindate'];
                        $enddate = $_POST['enddate'];

                        $sql = "SELECT firstName, lastName, accountType FROM users WHERE firstName= '$hidden1' AND lastName= '$hidden2'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($storevalue = $result->fetch_assoc()) {
                                $firstn = $storevalue["firstName"];
                                $lastn = $storevalue["lastName"];
                                $accTp = $storevalue["accountType"];
                                echo 'User Activity Log For ' . $firstn . '_' . $lastn . ' <i>(' . $accTp . ')</i> between ' . $begindate . ' and ' . $enddate . '.';
                            }
                        }
                    }
                ?> 
                </div>
            </div>
        </div>
        <div class="text-center my-black" style="padding:0.5%">
        </div><br/>
<!--<i class="alertbar"><?php echo $createConnection . $actDel . $updAct; ?></i>--> 

        <div class="container-fluid">
            <form action='<?php echo $_SERVER["PHP_SELF"]; ?>' method='POST'> 
                <div class="row"> 
                    <div  class="col-md-12 col-sm-12 text-center"> 
                    <?php
                    if (isset($_POST['activityBtn'])) {
                        $hidden1 = $_POST["hidbox1"];
                        $hidden2 = $_POST["hidbox2"];
                        $begindate = $_POST['begindate'];
                        $enddate = $_POST['enddate'];

                        $sql = "SELECT activity FROM usersactivity WHERE firstname= '$hidden1' AND lastname= '$hidden2' AND (dates='$begindate' OR (dates<'$enddate' AND dates>'$begindate') OR dates='$enddate')";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // output data of each row
                            while ($storevalue = $result->fetch_assoc()) {
                                $useract = $storevalue["activity"];
                                echo $useract . '.<br/>';
                            }
                        } else {
                            echo '0 results for User. ';
                        }
                    }
                    ?>
                    </div> 
                </div> 
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center"> 
                        <input type="hidden" name="firsthidden" value="<?php echo $hidden1; ?>" > 
                        <input type="hidden" name="lasthidden" value="<?php echo $hidden2; ?>" > 
                        <input type="hidden" name="beginhidden" value="<?php echo $begindate; ?>" > 
                        <input type="hidden" name="endhidden" value="<?php echo $enddate; ?>" ><br/> 
                        <button class="btn btn-danger" name="dateClearBtn" style="vertical-align:middle">Clear Activity Record For User Within This Duration&nbsp;&nbsp;<span class="glyphicon glyphicon-floppy-remove pull-right"></span></button><br/><br/> 
                        <button name="backBtn" class="btn btn-success">Back to Users' Account <span class="glyphicon glyphicon-share-alt" style="font-size:14px;"></span></button>
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

        <script src="myscript.js"></script> 
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

    </body>
</html>