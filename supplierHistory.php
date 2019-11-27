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
        <link rel="stylesheet" type="text/css" href="css/supply.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Suppliers' History</title>

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

        $updAct = "";
        $enterSupplier = "";
        if (isset($_POST['addsupplierbtn'])) {
            $dtsna = $_POST['suppliername'];
            $dtsnu = $_POST['suppliernumber'];
            $dtsad = $_POST['supplieraddress'];
            $dtdat = date("Y-m-d");

            $sql = "INSERT INTO suppliers(Name, contactAddress, phoneNumber, additionDate)VALUES ('$dtsna', '$dtsad', '$dtsnu', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $enterSupplier = 'New Supplier added successfully. ';
            } else {
                $enterSupplier = 'Error in Supplier entry. ' . $conn->error;
            }

            $actv = ' Added New Supplier ' . $dtsna . ' to list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $updAct = 'User activity updated.';
            } else {
                $updAct = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=supplierHistory.php');
            echo $next;
        }

        $Name = "";
        $createResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];

            $sql = "SELECT Name FROM suppliers WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $Name = $storevalue["Name"];
                $checkbox = 'Option Selected';
            } else {
                $createResults = '0 results. ';
            }
        }

        $updAct1 = "";
        $removeSupplier = "";
        if (isset($_POST['removesupplierbtn'])) {
            $supNames = $_POST["supNames"];
            $dtdat = date("Y-m-d");
            // sql to delete a record
            $sql = "DELETE FROM suppliers WHERE Name='$supNames'";

            if ($conn->query($sql) === TRUE) {
                $removeSupplier = 'Supplier Account removed . ';
            } else {
                $removeSupplier = 'Error removing Supplier Account: ' . $conn->error;
            }

            $actv = ' Removed Supplier ' . $supNames . ' from list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = 'User activity updated.';
            } else {
                $updAct1 = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=supplierHistory.php');
            echo $next;
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= supplierHistory.php');
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
            <div id="mnguser"> <label style="border-top:2px solid black;"><a href="manageusers.php">Manage Users' Accounts</a></label> </div>
        </nav>

        <i class="alertbar"><?php echo $checkbox . $enterSupplier . $updAct1 . $updAct . $createResults . $removeSupplier; ?></i>

         <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">SUPPLIERS' SALES &AMP; RETURN HISTORY <span class="glyphicon glyphicon-time"></span></span></h1></header>
        </div><hr class="line-blue">
                        <h4 class="my-center">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>

        <div class="container-fluid">
                <div class="row">
                    <form role="form" action="<?php $_PHP_SELF ?>" method="POST">
                        <div class="col-md-12 col-sm-12">
                        <div class="col-sm-3 col col-md-3">
                        <small>Supply Date: From <span class="glyphicon glyphicon-calendar"></span></small>
                        <input type="date" class="form-control" name="startdate"></span> 
                        </div>
                        <div class="col-sm-3 col-md-3">
                        <small>To <span class="glyphicon glyphicon-calendar"></span></small>
                        <input type="date" class="form-control" name="enddate"></span>
                        </div>
                        <div class="col-sm-4 col-md-4">
                        <small>Dealer's Name: <span class="glyphicon glyphicon-user"></span></small>
<?php
$supoptionstart = '<option value="';
$supoptioncont = '">';
$supoptionend = 'supplier </option>';

$sql = "SELECT DISTINCT Name FROM suppliers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<input list="supplier" class="form-control" name="suppliers">
                  <datalist id="supplier">';
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo $supoptionstart . $row["Name"] . $supoptioncont . $supoptionend;
    }
    echo '</datalist>';
} else {
    echo '0 results';
}
?>
</div>
<div class="col-sm-2 col-md-2">
    <span onclick="document.getElementById('addSup').style.display='block';" style="cursor: pointer;">Add New Supplier <span class="glyphicon glyphicon-send"></span></span>
</div>
</div><br/><br/><br/><br/>
<div class="text-center">
    <div class="text-center btn-group">
        <button class="btn btn-success" name="searchitembtn"><span class="glyphicon glyphicon-search"></span>&nbsp;SEARCH</button>
        <button class="btn btn-danger" name="removesupplierbtn"><span class="glyphicon glyphicon-remove"></span>&nbsp;REMOVE SUPPLIER
        <input class="pull-left" type='hidden' name='supNames' value= '<?php echo $Name; ?>' ></button>
        <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
    </div>
</div>
                        
                            
</form>
</div>
</div><hr class="line-blue">
    <div class="container-fluid">
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        <div class="tablecontainer" data-toggle="tooltip" title="Click Checkbox To Select">
<?php
$checkboxform = "<form action='supplierHistory.php' method='POST' name='form7'>";
$checkbox = "<input type='submit' value='' name='box'> <br/>";
$hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
$hiddenboxend = "'>";

if (isset($_POST['searchitembtn'])) {
    $creds = $_POST['suppliers'];
    $sdate = $_POST['startdate'];
    $edate = $_POST['enddate'];

    $sql = "SELECT id, Name, contactAddress, phoneNumber, additionDate, allSupplies, allReturns, sucSupplies, cashSupplies, tranSupplies, creditRem, amountReturned, lastDate, lastReturnD FROM suppliers WHERE Name='$creds' OR (lastDate='$sdate' OR lastDate='$edate' OR (lastDate<'$edate' AND lastDate>'$sdate'))";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row 
        echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Name</th><th>Contact Address</th><th>Phone Number</th><th>Date Of Addition</th><th>Stock Supplied</th><th>Stock Returned</th><th>Successful Supplies</th><th>All Cash Supplies</th><th>All Transfers</th><th>All Credit Remaining</th><th>Total Amount Returned</th><th>Last Supply Date</th><th>Last Return Date</th></tr>";
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Name"] . "</td><td>" . $row["contactAddress"] . "</td><td>" . $row["phoneNumber"] . "</td><td>" . $row["additionDate"] . "</td><td>" . $row["allSupplies"] . "</td><td>" . $row["allReturns"] . "</td><td>" . $row["sucSupplies"] . "</td><td>" . $row["cashSupplies"] . "</td><td>" . $row["tranSupplies"] . "</td><td>" . $row["creditRem"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["lastDate"] . "</td><td>" . $row["lastReturnD"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
} else {

$sql = "SELECT id, Name, contactAddress, phoneNumber, additionDate, allSupplies, allReturns, sucSupplies, cashSupplies, tranSupplies, creditRem, amountReturned, lastDate, lastReturnD FROM suppliers";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Name</th><th>Contact Address</th><th>Phone Number</th><th>Date Of Addition</th><th>Stock Supplied</th><th>Stock Returned</th><th>Successful Supplies</th><th>All Cash Supplies</th><th>All Transfers</th><th>All Credit Remaining</th><th>Total Amount Returned</th><th>Last Supply Date</th><th>Last Return Date</th></tr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Name"] . "</td><td>" . $row["contactAddress"] . "</td><td>" . $row["phoneNumber"] . "</td><td>" . $row["additionDate"] . "</td><td>" . $row["allSupplies"] . "</td><td>" . $row["allReturns"] . "</td><td>" . $row["sucSupplies"] . "</td><td>" . $row["cashSupplies"] . "</td><td>" . $row["tranSupplies"] . "</td><td>" . $row["creditRem"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["lastDate"] . "</td><td>" . $row["lastReturnD"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
}
?>
</div><br>
                        <div class="col-sm-4 col-md-4">
                            <a href="orderList.php">Order <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <a href="payout.php">See Expenses <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <a href="receivedHistory.php">See Received Item History <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a>
                        </div>
                    </div>
                </div>
        </div><br>

        <div class="clearfix"></div>
        <div class="container-fluid copy">
                <footer class="text-center">
                    <small>&copy; 2018 All rights Reserved, Intelogiic Global Resources</small>
                </footer>
        </div>

        <!-- The New Supplier Modal -->
        <div id="addSup" class="my-modal">
            <div class="my-modal-content my-card-4 my-animate-top">
            <div class="my-container">
                <span class="my-closebtn" onclick="document.getElementById('addSup').style.display='none';">&times;</span>
                <h2 class="my-center"><span class="label label-danger">New Supplier<span class="glyphicon-user glyphicon"></span></span></h2><hr class="line-red">
                <form action="supplierHistory.php" method="POST">
                    <div class="col-sm-6 col-md-6">
                    <small>Supplier's Name:</small>
                        <input type="text" class="form-control" name="suppliername" required>
                    </div>
                    <div class="col-sm-6 col-md-6">
                    <small>Phone Number:</small>
                        <input type="text" class="form-control" name="suppliernumber" required>
                    </div>
                    <div class="form-group col-sm-12 col-md-12">
                    <small>Supplier's Address:</small>
                    <textarea name="supplieraddress" class="form-control" id="saddr" placeholder="Contact Address" required></textarea>
                    </div><div class="clearfix"></div>
                    <br/><hr class="line-black"/>
                    <div class="my-center">
                        <button class="btn btn-success" name="addsupplierbtn">REGISTER&nbsp;<span class="glyphicon glyphicon-check"></span></button>
                    </div><br>
                </form>
            </div>
        </div>
        </div>

        <script src="mymodalscript.js"></script>
        <script src="js/myscript.js"></script>           
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <script>
                  function addr() {
                      document.getElementById("saddr").innerHTML = "";
                  }

        </script>

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