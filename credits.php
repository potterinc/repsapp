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
        <link rel="stylesheet" type="text/css" href="css/credits.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Credits</title> 

        <?php
        $Note = "Transaction note: ...";

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

        $hiddentext = "";
        $serialCodes = "";
        $staffNote = "";
        $carryResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";

            $sql = "SELECT id, serialCode, staffNote FROM expenses WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $storevalue = $result->fetch_assoc();
                $hiddentext = $storevalue["id"];
                $serialCodes = $storevalue["serialCode"];
                $staffNote = $storevalue["staffNote"];
                $checkbox = 'Option Selected';
            } else {
                $carryResults = '0 results. ';
            }
        }

        $hiddentexts = "";
        $serialCode = "";
        $staffNotes = "";
        $carryResult = "";
        $checkbox1 = "";
        if (isset($_POST['boxes'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";

            $sql = "SELECT id, serialCode, staffNote FROM income WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $storevalue = $result->fetch_assoc();
                $hiddentexts = $storevalue["id"];
                $serialCode = $storevalue["serialCode"];
                $staffNotes = $storevalue["staffNote"];
                $checkbox1 = 'Option Selected';
            } else {
                $carryResult = '0 results. ';
            }
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= credits.php');
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

        <i class="alertbar"><?php echo $checkbox . $checkbox1 . $carryResult . $carryResults; ?></i>
        <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">CREDITS <span class="glyphicon glyphicon-credit-card"></span></span></h1></header>
        </div><hr class="line-blue">

        <div class="container-fluid">
            <form action="credits.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate>
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        
                        <h4 class="date my-center">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>
                    </div>
                </div><br/>
                <div class="row">
                    <div  class="col-md-9 col-sm-9 text-center"> 
                        <span class="space">Search By Item Sold: <span class="glyphicon glyphicon-gift"></span> 
                            <?php
                            $itemoptionstart = '<option value="';
                            $itemoptioncont = '">';
                            $itemoptionend = '</option>';

                            $sql = "SELECT DISTINCT salesDesc FROM income WHERE creditRemaining!=0";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="credititems" name="credititemc" placeholder="Search For Item:">
                  <datalist id="credititems">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $itemoptionstart . $row["salesDesc"] . $itemoptioncont . $itemoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?>
                        </span>
                        <span class="space">Search By Item Purchased: <span class="glyphicon glyphicon-gift"></span> 
                            <?php
                            $itemoptionstart = '<option value="';
                            $itemoptioncont = '">';
                            $itemoptionend = '</option>';

                            $sql = "SELECT DISTINCT purchaseDesc FROM expenses WHERE creditRemaining!=0";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="credititem" name="credititems" placeholder="Search For Item:">
                  <datalist id="credititem">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $itemoptionstart . $row["purchaseDesc"] . $itemoptioncont . $itemoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?>
                        </span><br/><br/>
                        <span class="space">Search By Debtor: <span class="glyphicon glyphicon-user"></span> 
                            <?php
                            $cusoptionstart = '<option value="';
                            $cusoptioncont = '">';
                            $cusoptionend = 'customer </option>';

                            $sql = "SELECT DISTINCT Customer FROM income WHERE creditRemaining!=0";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="creditcustomers" name="creditcustomer" placeholder="Search For Customer:">
                  <datalist id="creditcustomers">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $cusoptionstart . $row["Customer"] . $cusoptioncont . $cusoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?>
                        </span>
                        <span class="space">Search By Creditor: <span class="glyphicon glyphicon-user"></span> 
                            <?php
                            $supoptionstart = '<option value="';
                            $supoptioncont = '">';
                            $supoptionend = 'supplier </option>';

                            $sql = "SELECT DISTINCT Supplier FROM expenses WHERE creditRemaining!=0";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="creditsuppliers" name="creditsupplier" placeholder="Search For Supplier:">
                                <datalist id="creditsuppliers">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $supoptionstart . $row["Supplier"] . $supoptioncont . $supoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?>
                        </span>
                        <br/><br/>
                        <span><button class="btn btn-success" name="searchitembtn1"><span class="glyphicon glyphicon-search pull-left"></span>&nbsp;SEARCH</button></span>
                        <span>Search By Date: From <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="startdate"></span> 
                        <span>To <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="enddate"></span>
                        <span><button class="btn btn-success" name="searchitembtn2"><span class="glyphicon glyphicon-search pull-left"></span>&nbsp;SEARCH</button></span>
                    </div>
                    <div  class="col-md-3 col-sm-3">
                        <textarea name="staffN" class="form-control"><?php echo $Note . $staffNotes . $staffNote; ?></textarea>
                    </div>
                </div><br/>
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        <div class="tablecontainer" title="Click Checkbox To Select">
                            <div class="row">
                                <div  class="col-md-6 col-sm-6 borderedright"> 
                                    <?php
                                    $checkboxform = "<form action='credits.php' method='POST' name='form13'>";
                                    $checkbox = "<input type='submit' value='' name='boxes'> <br/>";
                                    $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                                    $hiddenboxend = "'>";

                                    if (isset($_POST['searchitembtn1'])) {
                                        $credic = $_POST['credititemc'];
                                        $credc = $_POST['creditcustomer'];
                                        $sdate = $_POST['startdate'];
                                        $edate = $_POST['enddate'];

                                        $sql = "SELECT id, Customer, cashPaid, transferPaid, creditRemaining, salesDesc, lastDate, salesDate FROM income WHERE creditRemaining!=0 AND (salesDesc='$credic' OR (salesDate='$sdate' OR salesDate='$edate' OR (salesDate<'$edate' AND salesDate>'$sdate')) OR Customer='$credc')";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // output data of each row 
                                            echo "<table id='itemlist'>
                                            <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Debtor</th><th>Credit Owed</th><th>Sales Description</th><th>Sales Cost</th><th>Sales Date</th><th>Last Payment Date</th></tr>";
                                            // output data of each row

                                            while ($row = $result->fetch_assoc()) {
                                                $costPaid = $row["cashPaid"] + $row["transferPaid"] + $row["creditRemaining"];
                                                echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Customer"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["salesDesc"] . "</td><td>" . $costPaid . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                            } echo "</table>";
                                        } else {
                                            echo "0 results";
                                        }
                                    } else {

                                    $sql = "SELECT id, Customer, cashPaid, transferPaid, creditRemaining, salesDesc, lastDate, salesDate FROM income WHERE creditRemaining!=0;";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo "<table id='itemlist'>
                                        <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Debtor</th><th>Credit Owed</th><th>Sales Description</th><th>Sales Cost</th><th>Sales Date</th><th>Last Payment Date</th></tr>";
                                        // output data of each row

                                        while ($row = $result->fetch_assoc()) {
                                            $costPaid = $row["cashPaid"] + $row["transferPaid"] + $row["creditRemaining"];
                                            echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Customer"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["salesDesc"] . "</td><td>" . $costPaid . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "0 results";
                                    }
                                }
                                    ?> 
                                </div>  
                                <div  class="col-md-6 col-sm-6 borderedleft">
                                    <?php
                                    $checkboxform = "<form action='credits.php' method='POST' name='form9'>";
                                    $checkbox = "<input type='submit' value='' name='box'> <br/>";
                                    $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                                    $hiddenboxend = "'>";

                                    if (isset($_POST['searchitembtn2'])) {
                                        $credis = $_POST['credititems'];
                                        $creds = $_POST['creditsupplier'];
                                        $sdate = $_POST['startdate'];
                                        $edate = $_POST['enddate'];

                                        $sql = "SELECT id, Supplier, cashPaid, transferPaid, creditRemaining, purchaseDesc, lastDate, purchaseDate FROM expenses WHERE creditRemaining!=0 AND (purchaseDesc='$credis' OR (purchaseDate='$sdate' OR purchaseDate='$edate' OR (purchaseDate<'$edate' AND purchaseDate>'$sdate')) OR Supplier='$creds')";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // output data of each row 
                                            echo "<table id='itemlist'>
                                            <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Creditor</th><th>Credit Owed</th><th>Purchase Description</th><th>Purchase Cost</th><th>Purchase Date</th><th>Last Payment Date</th></tr>";
                                            // output data of each row

                                            while ($row = $result->fetch_assoc()) {
                                                $costPaid = $row["cashPaid"] + $row["transferPaid"] + $row["creditRemaining"];
                                                echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["purchaseDesc"] . "</td><td>" . $costPaid . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                            } echo "</table>";
                                        } else {
                                            echo "0 results";
                                        }
                                    } else {

                                    $sql = "SELECT id, Supplier, cashPaid, transferPaid, creditRemaining, purchaseDesc, lastDate, purchaseDate FROM expenses WHERE creditRemaining!=0;";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo "<table id='itemlist'>
                                        <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Creditor</th><th>Credit Owed</th><th>Purchase Description</th><th>Purchase Cost</th><th>Purchase Date</th><th>Last Payment Date</th></tr>";
                                        // output data of each row

                                        while ($row = $result->fetch_assoc()) {
                                            $costPaid = $row["cashPaid"] + $row["transferPaid"] + $row["creditRemaining"];
                                            echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["purchaseDesc"] . "</td><td>" . $costPaid . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "0 results";
                                    }
                                }
                                    ?> 
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3">
                                <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                            </div>
                        <div class="col-sm-3 col-md-3"><a href="payin.php">See Income <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a>
                            <input class="pull-left" type='hidden' name='hiddenbox9' value= '<?php echo $hiddentexts . $hiddentext; ?>' >
                        </div>
                            <input class="pull-left" type='hidden' name='serialCoded' value= '<?php echo $serialCode . $serialCodes; ?>' >
                            <div class="col-sm-3 col-md-3">
                            <a href="payBalance.php">See Book Balance <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <a href="payout.php">Expenses <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a>
                            </div>
                        </div>
                    </div>
                </div> 
            </form> 
        </div><br>
        <div class="container-fluid copy">
                <footer class="text-center">
                    <small>&copy; 2018 All rights Reserved, Intelogiic Global Resources</small>
                </footer>
        </div>


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