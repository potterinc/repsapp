<?php
session_start();
if (!isset($_SESSION["fname"]) AND !isset($_SESSION["lname"])) {
    header('Location: index.php');
}
require_once "qIconnection.php"; 
?> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="set.css">
        <link rel="stylesheet" href="mybar.css">
        <link rel="stylesheet" type="text/css" href="css/pay.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Income</title>

        <?php
        $zero = 0;

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

        $removeRecord = "";
        $idd = "";
        $sql = "SELECT id FROM income WHERE cashPaid=0 AND transferPaid=0 AND creditRemaining=0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($storevalue = $result->fetch_assoc()) {
                $idd = $storevalue["id"];
            }

            // sql to delete a record
            $sql = "DELETE FROM income WHERE id=$idd";

            if ($conn->query($sql) === TRUE) {
                $removeRecord = 'Record deleted successfully. ';
            } else {
                $removeRecord = 'Error deleting record: ' . $conn->error;
            }
        }

        $costPaid = "";
        $hiddentext = "";
        $salesType = "";
        $salesBy = "";
        $paidTo = "";
        $Customer = "";
        $cashPaid = "";
        $transferPaid = "";
        $creditRemaining = "";
        $staffNote = "";
        $serialCodes = "";
        $cashPurch = "";
        $tranPurch = "";
        $creditRem = "";
        $anCP;
        $anTP;
        $anCR;
        $carryResult = "";
        $carryResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";
            $zero = "";

            $sql = "SELECT Id, salesType, salesBy, paidTo, Customer, cashPaid, transferPaid, creditRemaining, staffNote FROM income WHERE Id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $hiddentext = $storevalue["Id"];
                $salesType = $storevalue["salesType"];
                $salesBy = $storevalue["salesBy"];
                $paidTo = $storevalue["paidTo"];
                $Customer = $storevalue["Customer"];
                $cashPaid = $storevalue["cashPaid"];
                $transferPaid = $storevalue["transferPaid"];
                $creditRemaining = $storevalue["creditRemaining"];
                $staffNote = $storevalue["staffNote"];
                $costPaid = $cashPaid + $transferPaid + $creditRemaining;
                $checkbox = 'Option Selected';
                
            } else {
                $carryResult = '0 results. ';
            }

            $sql = "SELECT cashPurchase, tranPurchase, creditRem FROM customers WHERE Name='$Customer'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $cashPurch = $storevalue["cashPurchase"];
                $tranPurch = $storevalue["tranPurchase"];
                $creditRem = $storevalue["creditRem"];
            } else {
                $carryResults = '0 results. ';
            }

            $anCP = $cashPurch - $cashPaid;
            $anTP = $tranPurch - $transferPaid;
            $anCR = $creditRem - $creditRemaining;
        }

        $updAct = "";
        $cPd = "";
        $tPd = "";
        $cRm = "";
        $cp;
        $tp;
        $cr;
        $sdUpd = "";
        $cusUpd = "";
        $incUpdated = "";
        if (isset($_POST['updateitembtn'])) {
            $hiddenboxid = $_POST['hiddenbox8'];
            $hCP = $_POST['hiddenCP'];
            $hTP = $_POST['hiddenTP'];
            $hCR = $_POST['hiddenCR'];
            $sCoded = $_POST['serialCoded'];
            $incomes = $_POST['incomes'];
            $cashP = $_POST['cashpayment'];
            $tranP = $_POST['transferpayment'];
            $cred = $_POST['credit'];
            $mdBy = $_POST['madeBy'];
            $pdTo = $_POST['paidTo'];
            $pdBy = $_POST['paidBy'];
            $stafN = $_POST['staffN'];
            $desc = $_POST['desc'];
            $lastD = date("Y-m-d");
            
            //updating credit paid
            
            $sql = "SELECT Id, creditRemaining FROM income WHERE id= '$hiddenboxid'";
            $result = $conn->query($sql);
            $dataTable = $result->fetch_assoc();
            $oldCredit = $dataTable["creditRemaining"];
            $newCredit = $oldCredit - $cred;
            if ($result->num_rows > 0) {

                $sql = "UPDATE income SET salesBy='$mdBy', creditPaid=$newCredit, paidTo='$pdTo', Customer='$pdBy', cashPaid='$cashP', transferPaid='$tranP', creditRemaining='$cred', lastDate='$lastD', staffNote='$stafN' WHERE id= $hiddenboxid";

                if ($conn->query($sql) === TRUE) {
                    $incUpdated = 'Income Updated. ';
                } else {
                    $incUpdated = 'Error Updating Income: ' . $conn->error;
                }

                $actv = ' Updated Income for past transaction with customer- ' . $pdBy . '.';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

                if ($conn->query($sql) === TRUE) {
                    $updAct = ' User activity updated.';
                } else {
                    $updAct = ' Error updating user activity. ' . $conn->error;
                }
            } else {

                $sql = "INSERT INTO income(salesType, salesDesc, salesDate, salesBy, paidTo, Customer, cashPaid, transferPaid, creditRemaining, lastDate, staffNote)VALUES ('$incomes', '$desc', '$lastD', '$mdBy', '$pdTo', '$pdBy', '$cashP', '$tranP', '$cred', '$lastD', '$stafN')";

                if ($conn->query($sql) === TRUE) {
                    $incUpdated = 'New Income added. ';
                } else {
                    $incUpdated = 'Error Adding Income. ' . $conn->error;
                }

                $actv = ' Updated Income for new transaction with customer- ' . $pdBy . '.';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

                if ($conn->query($sql) === TRUE) {
                    $updAct = 'User activity updated.';
                } else {
                    $updAct = 'Error updating user activity. ' . $conn->error;
                }
            }

            $sql = "SELECT Customer FROM income WHERE Customer= '$pdBy'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $cp = $cashP + $hCP;
                $tp = $tranP + $hTP;
                $cr = $cred + $hCR;

                $sql = "UPDATE customers SET cashPurchase='$cp', tranPurchase='$tp', creditRem='$cr' WHERE Name='$pdBy'";

                if ($conn->query($sql) === TRUE) {
                    $cusUpd = 'Customer Account Updated. ';
                } else {
                    $cusUpd = 'Error Updating Customer Account: ' . $conn->error;
                }
            }
            //$next = header('Refresh:1; URL=payin.php');
            //echo $next;
        }

        $updAct1 = "";
        $sucDel = "";
        if (isset($_POST['allClearBtn'])) {
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM income";

            if ($conn->query($sql) === TRUE) {
                $sucDel = ' All Income Records cleared. ';
            } else {
                $sucDel = ' Error clearing All Income Records: ' . $conn->error;
            }

            $actv = ' Cleared Entire Income Records.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = ' User activity updated.';
            } else {
                $updAct1 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=payin.php');
            echo $next;
        }

        $updAct2 = "";
        $incDel = "";
        if (isset($_POST['dateClearBtn'])) {
            $beginday = $_POST['beginday'];
            $endday = $_POST['endday'];
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM income WHERE salesDate='$beginday' OR salesDate='$endday' OR (salesDate<'$endday' AND salesDate>'$beginday')";

            if ($conn->query($sql) === TRUE) {
                $incDel = ' Income Records between ' . $beginday . ' and ' . $endday . ' cleared';
            } else {
                $incDel = ' Error clearing Income Records between ' . $beginday . ' and ' . $endday . ':' . $conn->error;
            }

            $actv = ' Cleared Income Records between ' . $beginday . ' and ' . $endday;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct2 = ' User activity updated.';
            } else {
                $updAct2 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=payin.php');
            echo $next;
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= payin.php');
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

        <i class="alertbar"><?php echo $checkbox . $removeRecord . $carryResult . $carryResults . $incUpdated . $cusUpd . $sdUpd . $sucDel . $incDel . $updAct . $updAct1 . $updAct2; ?></i> 
         
         <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">INCOME <span class="glyphicon glyphicon-hand-down"></span></span></h1></header>
        </div><hr class="line-blue">

        <div class="container-fluid"> 
            
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        
                        <h4 class="date my-center">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>
                    </div>
                </div>
                <div class="row">
                    <div  class="col-md-4 col-sm-4" style="border-right: dashed 1.5px #0066cc">
                    <form action="payin.php" method="POST" target="_self">
                        <h4><span class="label label-primary">Search By Sales Date</span></h4><small>From <span class="glyphicon glyphicon-calendar"></span></small><input type="date" name="startdate"> 
                        <small>To <span class="glyphicon glyphicon-calendar"></span></small><input type="date" name="enddate">
                        <br/><br/><span class="space">Search By Item: <span class="glyphicon glyphicon-gift"></span>  <?php
        $itemoptionstart = '<option value="';
        $itemoptioncont = '">';
        $itemoptionend = '</option>';

        $sql = "SELECT DISTINCT salesDesc FROM income";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<input list="incomeitems" name="incomeitem" class="pull-right" placeholder="Search For Items: ..">
                  <datalist id="incomeitems">';
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo $itemoptionstart . $row["salesDesc"] . $itemoptioncont . $itemoptionend;
            }
            echo '</datalist>';
        } else {
            echo '0 results';
        }
        ?></span>
                        <br/><br/> <span class="space">Search By Customer: <span class="glyphicon glyphicon-user"></span> 
                            <?php
                            $cusoptionstart = '<option value="';
                            $cusoptioncont = '">';
                            $cusoptionend = 'customer </option>';

                            $sql = "SELECT DISTINCT Customer FROM income";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="incomecustomers" name="incomecustomer" class="pull-right" placeholder="Search For Customers: ..">
                  <datalist id="incomecustomers">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $cusoptionstart . $row["Customer"] . $cusoptioncont . $cusoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?></span>
                        <br/><br/><span class="space"><button class="btn btn-sm btn-success" name="searchitembtn"><span class="glyphicon glyphicon-search pull-left"></span>&nbsp;&nbsp;SEARCH ITEMS</span></button></span> 
                        </form>
                    </div>
                    <form action="<?php $_PHP_SELF ?>" method="POST">
                        <div  class="col-md-8 col-sm-8">
                        <div class="row">
                            <div class="col-md-4 col-sm-4" style="border-right: dashed 1.5px #0066cc">
                                Type of Income: <input type="text" name="incomes" value="<?php echo $salesType; ?>" >
                                <br/>Cost: <input onchange="Cost();" id="costPaid" type="text" name="cost" value="<?php echo $costPaid . $zero; ?>" >
                                <br/>Amount Paid In Cash: <input onchange="Cash();" id="cashPaid" type="text" name="cashpayment" value="<?php echo $cashPaid . $zero; ?>" > 
                                <br/>Amount Paid By Transfer: <input onchange="Trans();" id="transPaid" type="text" name="transferpayment" value="<?php echo $transferPaid . $zero; ?>" >
                            </div>  
                            <div class="col-md-4 col-sm-4 biglist" style="border-right: dashed 1.5px #0066cc"> 
                                Payment Received From (Customer): 
                                <?php
                                $cusstart = '<input list="customer" name="paidBy" value= "';
                                $cusend = '" placeholder="Search For Customers: .." ><datalist id="customer">';
                                $cusoptionstart = '<option value="';
                                $cusoptioncont = '">';
                                $cusoptionend = 'customer </option>';

                                $sql = "SELECT DISTINCT Name FROM customers";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo $cusstart . $Customer . $cusend;
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $cusoptionstart . $row["Name"] . $cusoptioncont . $cusoptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                                ?> 
                                Transaction Made By (Staff): 
                                <?php
                                $userstart = '<input list="user" name="madeBy" value= "';
                                $userend = '" placeholder="Search For Staff: .." ><datalist id="user">';
                                $useroptionstart = '<option value="';
                                $useroptioncont = '">';
                                $useroptionend = 'staff </option>';

                                $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo $userstart . $salesBy . $userend;
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $useroptionstart . $row["lastName"] . " " . $row["firstName"] . " " . $row["accountType"] . $useroptioncont . $useroptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                                ?>
                                <br/>Payment Received By (Staff): <br/>
                                <?php
                                $userstart = '<input list="user" name="paidTo" value= "';
                                $userend = '" placeholder="Search For Staff: .." ><datalist id="user">';
                                $useroptionstart = '<option value="';
                                $useroptioncont = '">';
                                $useroptionend = 'staff </option>';

                                $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo $userstart . $paidTo . $userend;
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $useroptionstart . $row["lastName"] . " " . $row["firstName"] . " " . $row["accountType"] . $useroptioncont . $useroptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                                ?> 
                                <br/>Credit: <input id="credit" type="text" name="credit" value="<?php echo $creditRemaining . $zero; ?>" >
                            </div>
                            <div class="col-md-4 col-sm-4"> 
                                <textarea name="staffN" class="form-control input-lg" placeholder="Staff Note:"><?php echo $staffNote; ?></textarea><br>
                                <textarea name="desc" class="form-control input-lg" placeholder="Description:"></textarea> <br>
                                <div>
                                    <button class="btn btn-success btn-sm btn-block" name="updateitembtn"><span class="glyphicon glyphicon-refresh pull-left"></span>&nbsp;&nbsp;UPDATE EXPENDITURE</button>
                            <input class="pull-left" type='hidden' name='hiddenbox8' value= '<?php echo $hiddentext; ?>' >
                            <input class="pull-left" type='hidden' name='hiddenCP' value= '<?php echo $anCP; ?>' >
                            <input class="pull-left" type='hidden' name='hiddenTP' value= '<?php echo $anTP; ?>' > 
                            <input class="pull-left" type='hidden' name='hiddenCR' value= '<?php echo $anCR; ?>' >
                            <input type='hidden' name='serialCoded' value= '<?php echo $serialCodes; ?>' >
                        </form>
                                </div>
                            </div>
                    </form>
                    
                        </div> 
                    </div>
                </div><br>
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        <div class="tablecontainer" title="Click Checkbox To Select">
                            <?php
                            $checkboxform = "<form action='payin.php' method='POST' name='form8'>";
                            $checkbox = "<input type='submit' value='' name='box'> <br/>";
                            $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                            $hiddenboxend = "'>";

                            if (isset($_POST['searchitembtn'])) {
                                $itmI = $_POST['incomeitem'];
                                $cusI = $_POST['incomecustomer'];
                                $sdate = $_POST['startdate'];
                                $edate = $_POST['enddate'];

                                $sql = "SELECT Id, salesType, salesDesc, salesDate, salesBy, paidTo, Customer, cashPaid, transferPaid, creditRemaining, lastDate FROM income WHERE salesDesc='$itmI' OR (salesDate='$sdate' OR salesDate='$edate' OR (salesDate<'$edate' AND salesDate>'$sdate')) OR Customer='$cusI'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // output data of each row 
                                    echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Type Of Sales</th><th>Description</th><th>Sales Date</th><th>Sales Made By</th><th>Payment Received By</th><th>Payment Received From</th><th>Amount Received In Cash</th><th>Amount Received By Transfer</th><th>Credit Owed</th><th>Last Payment Date</th></tr>";
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["salesType"] . "</td><td>" . $row["salesDesc"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["salesBy"] . "</td><td>" . $row["paidTo"] . "</td><td>" . $row["Customer"] . "</td><td>" . $row["cashPaid"] . "</td><td>" . $row["transferPaid"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                    } echo "</table>";
                                } else {
                                    echo "0 results";
                                }
                            } else {

                            $sql = "SELECT Id, salesType, salesDesc, salesDate, salesBy, paidTo, Customer, cashPaid, transferPaid, creditRemaining, lastDate FROM income";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Type Of Sales</th><th>Description</th><th>Sales Date</th><th>Sales Made By</th><th>Payment Received By</th><th>Payment Received From</th><th>Amount Received In Cash</th><th>Amount Received By Transfer</th><th>Credit Owed</th><th>Last Payment Date</th></tr>";
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["salesType"] . "</td><td>" . $row["salesDesc"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["salesBy"] . "</td><td>" . $row["paidTo"] . "</td><td>" . $row["Customer"] . "</td><td>" . $row["cashPaid"] . "</td><td>" . $row["transferPaid"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "0 results";
                            }
                        }
                            ?> 
                        </div><br/>
                        <form method="POST" action="<?php $_PHP_SELF ?>">
                        <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                            <span id="clearentirelink" class=""><button class="btn btn-danger btn-sm" name="allClearBtn">CLEAR LIST&nbsp;&nbsp;<span class="glyphicon glyphicon-trash pull-right"></span></button></span> 
                        <span id="begindaylink" class="dates">From <span class="glyphicon glyphicon-calendar"></span> <input type="date" class="dates" name="beginday" ></span> 
                        <span id="enddaylink" class="space dates"> To <span class="glyphicon glyphicon-calendar"></span> <input type="date" class="dates" name="endday" ></span>  
                        <span id="cleardatelink" class="space dates"><button class="btn btn-danger btn-sm" name="dateClearBtn"><span>CLEAR BY PAYMENT DATE&nbsp;&nbsp;<span class="glyphicon glyphicon-trash pull-right"></span></button></span><hr class="line-black">
                        </form>
                        
                        <div class="col-sm-3 col-md-3 text-center">
                            <p id="balancelink" class="pull-left"><a href="payBalance.php">Book Balance <span class="glyphicon glyphicon-book" style="font-size:14px;"></span></a></p>
                        </div>
                        <div class="col-sm-3 col-md-3 text-center">
                            <p id="customerlink"><a href="customerHistory.php">Customers' History <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a></p> 
                        </div>
                        <div class="col-sm-3 col-md-3 text-center" id="saleslink">
                            <p class="pull-right"><a href="salesHistory.php">Sales History <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a></p>
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <p id="creditlink"><a href="credits.php">Credits <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a></p>
                        </div>
                        </div>
                    </div> 
                </div>
            </form> 
        </div> <div class="container-fluid copy">
                <footer class="text-center">
                    <small>&copy; 2018 All rights Reserved, Intelogiic Global Resources</small>
                </footer>
        </div>

        <script src="js/myscript.js"></script>           
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <script>
                  function Cash() {
                      var cashPaid = document.getElementById("cashPaid").value;
                      var transPaid = document.getElementById("transPaid").value;
                      var costPaid = document.getElementById("costPaid").value;
                      cashPaid = parseInt(cashPaid);
                      transPaid = parseInt(transPaid);
                      costPaid = parseInt(costPaid);
                      calcCredit = costPaid - (transPaid + cashPaid);

                      document.getElementById("credit").value = calcCredit;
                  }

                  function Trans() {
                      var transPaid = document.getElementById("transPaid").value;
                      var cashPaid = document.getElementById("cashPaid").value;
                      var costPaid = document.getElementById("costPaid").value;
                      transPaid = parseInt(transPaid);
                      cashPaid = parseInt(cashPaid);
                      costPaid = parseInt(costPaid);
                      calcCredit = costPaid - (cashPaid + transPaid);

                      document.getElementById("credit").value = calcCredit;
                  }

                  function Cost() {
                      var costPaid = document.getElementById("costPaid").value;
                      var cashPaid = document.getElementById("cashPaid").value;
                      var transPaid = document.getElementById("transPaid").value;
                      costPaid = parseInt(costPaid);
                      cashPaid = parseInt(cashPaid);
                      transPaid = parseInt(transPaid);
                      calcCredit = costPaid - (transPaid + cashPaid);

                      document.getElementById("credit").value = calcCredit;
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
                document.getElementById("clearentirelink").style.display = "none";
                document.getElementById("begindaylink").style.display = "none";
                document.getElementById("enddaylink").style.display = "none";
                document.getElementById("cleardatelink").style.display = "none";
                document.getElementById("balancelink").style.display = "none";
                document.getElementById("customerlink").style.display = "none";
                document.getElementById("creditlink").style.display = "none";
            }

            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script> 

    </body>
</html>