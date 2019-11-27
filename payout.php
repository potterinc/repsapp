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
        <link rel="stylesheet" type="text/css" href="css/payout.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Expenses</title>

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
        $sql = "SELECT id FROM expenses WHERE cashPaid=0 AND transferPaid=0 AND creditRemaining=0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($storevalue = $result->fetch_assoc()) {
                $idd = $storevalue["id"];
            }

            // sql to delete a record
            $sql = "DELETE FROM expenses WHERE id=$idd";

            if ($conn->query($sql) === TRUE) {
                $removeRecord = 'Record deleted successfully. ';
            } else {
                $removeRecord = 'Error deleting record: ' . $conn->error;
            }
        }

        $costPaid = "";
        $hiddentext = "";
        $purchaseType = "";
        $authorizedBy = "";
        $purchaseBy = "";
        $Supplier = "";
        $cashPaid = "";
        $transferPaid = "";
        $creditRemaining = "";
        $staffNote = "";
        $serialCodes = "";
        $cashSupp = "";
        $tranSupp = "";
        $creditRem = "";
        $anCS = "";
        $anTS = "";
        $anCR = "";
        $carryResult = "";
        $carryResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";
            $zero = "";

            $sql = "SELECT id, purchaseType, authorizedBy, purchaseBy, Supplier, cashPaid, transferPaid, creditRemaining, staffNote, serialCode FROM expenses WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $hiddentext = $storevalue["id"];
                $purchaseType = $storevalue["purchaseType"];
                $authorizedBy = $storevalue["authorizedBy"];
                $purchaseBy = $storevalue["purchaseBy"];
                $Supplier = $storevalue["Supplier"];
                $cashPaid = $storevalue["cashPaid"];
                $transferPaid = $storevalue["transferPaid"];
                $creditRemaining = $storevalue["creditRemaining"];
                $staffNote = $storevalue["staffNote"];
                $serialCodes = $storevalue["serialCode"];
                $costPaid = $cashPaid + $transferPaid + $creditRemaining;
                $checkbox = 'Option Selected';
            } else {
                $carryResult = '0 results. ';
            }

            $sql = "SELECT cashSupplies, tranSupplies, creditRem FROM suppliers WHERE Name='$Supplier'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $cashSupp = $storevalue["cashSupplies"];
                $tranSupp = $storevalue["tranSupplies"];
                $creditRem = $storevalue["creditRem"];
            } else {
                $carryResults = '0 results. ';
            }

            $anCS = $cashSupp - $cashPaid;
            $anTS = $tranSupp - $transferPaid;
            $anCR = $creditRem - $creditRemaining;
        }

        $updAct = "";
        $cPd = "";
        $tPd = "";
        $cRm = "";
        $cs = "";
        $ts = "";
        $cr = "";
        $rcdUpd = "";
        $supUpd = "";
        $expUpdated = "";
        if (isset($_POST['updateitembtn'])) {
            $hiddenboxid = $_POST['hiddenbox8'];
            $hCS = $_POST['hiddenCS'];
            $hTS = $_POST['hiddenTS'];
            $hCR = $_POST['hiddenCR'];
            $sCoded = $_POST['serialCoded'];
            $expd = $_POST['expenditure'];
            $cashP = $_POST['cashpayment'];
            $tranP = $_POST['transferpayment'];
            $cred = $_POST['credit'];
            $pdTo = $_POST['paidto'];
            $pdBy = $_POST['paidby'];
            $issue = $_POST['issuer'];
            $stafN = $_POST['staffN'];
            $desc = $_POST['desc'];
            $lastD = date("Y-m-d");

            $sql = "SELECT id FROM expenses WHERE id= '$hiddenboxid'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {

                $sql = "UPDATE expenses SET authorizedBy='$issue', purchaseBy='$pdBy', Supplier='$pdTo', cashPaid='$cashP', transferPaid='$tranP', creditRemaining='$cred', lastDate='$lastD', staffNote='$stafN' WHERE id= $hiddenboxid";

                if ($conn->query($sql) === TRUE) {
                    $expUpdated = 'Expense Updated. ';
                } else {
                    $expUpdated = 'Error Updating Expense: ' . $conn->error;
                }

                $actv = ' Updated Expense for past transaction with supplier- ' . $pdTo . '.';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

                if ($conn->query($sql) === TRUE) {
                    $updAct = ' User activity updated.';
                } else {
                    $updAct = ' Error updating user activity. ' . $conn->error;
                }
            } else {

                $sql = "INSERT INTO expenses(purchaseType, purchaseDesc, purchaseDate, authorizedBy, purchaseBy, Supplier, cashPaid, transferPaid, creditRemaining, lastDate, staffNote, serialCode)VALUES ('$expd', '$desc', '$lastD', '$issue', '$pdBy', '$pdTo', '$cashP', '$tranP', '$cred', '$lastD', '$stafN', '$sCoded')";

                if ($conn->query($sql) === TRUE) {
                    $expUpdated = 'New Expense added. ';
                } else {
                    $expUpdated = 'Error Adding Expense. ' . $conn->error;
                }

                $actv = ' Updated Expense for new transaction with supplier- ' . $pdTo . '.';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

                if ($conn->query($sql) === TRUE) {
                    $updAct = ' User activity updated.';
                } else {
                    $updAct = ' Error updating user activity. ' . $conn->error;
                }
            }

            $sql = "SELECT serialCode FROM receiveditems WHERE serialCode= '$sCoded'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $cs = $cashP + $hCS;
                $ts = $tranP + $hTS;
                $cr = $cred + $hCR;

                $sql = "UPDATE suppliers SET cashSupplies='$cs', tranSupplies='$ts', creditRem='$cr' WHERE Name='$pdTo'";

                if ($conn->query($sql) === TRUE) {
                    $supUpd = 'Supplier Account Updated. ';
                } else {
                    $supUpd = 'Error Updating Supplier Account: ' . $conn->error;
                }
            }

            $sql = "SELECT cashPaid, transferPaid, creditRemaining FROM expenses WHERE serialCode='$sCoded' AND Supplier='$pdTo'AND staffNote='$stafN'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $cPd = $storevalue["cashPaid"];
                $tPd = $storevalue["transferPaid"];
                $cRm = $storevalue["creditRemaining"];
            }
            $aPd = $cPd + $tPd;

            $sql = "UPDATE receiveditems SET amountPaid='$aPd', creditOwed='$cRm' WHERE serialCode='$sCoded' AND Supplier='$pdTo'AND staffNote='$stafN'";

            if ($conn->query($sql) === TRUE) {
                $rcdUpd = ' Received Items account Updated. ';
            } else {
                $rcdUpd = ' Error Updating Received Items account: ' . $conn->error;
            }
            $next = header('Refresh:1; URL=payout.php');
            echo $next;
        }

        $updAct1 = "";
        $sucDel = "";
        if (isset($_POST['allClearBtn'])) {
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM expenses";

            if ($conn->query($sql) === TRUE) {
                $sucDel = ' All Expense Records cleared. ';
            } else {
                $sucDel = ' Error clearing All Expense Records: ' . $conn->error;
            }

            $actv = ' Cleared Entire Expense Records.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = ' User activity updated.';
            } else {
                $updAct1 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=payout.php');
            echo $next;
        }

        $updAct2 = "";
        $expDel = "";
        if (isset($_POST['dateClearBtn'])) {
            $beginday = $_POST['beginday'];
            $endday = $_POST['endday'];
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM expenses WHERE purchaseDate='$beginday' OR purchaseDate='$endday' OR (purchaseDate<'$endday' AND purchaseDate>'$beginday')";

            if ($conn->query($sql) === TRUE) {
                $expDel = ' Expense Records between ' . $beginday . ' and ' . $endday . ' cleared';
            } else {
                $expDel = ' Error clearing Expense Records between ' . $beginday . ' and ' . $endday . ':' . $conn->error;
            }

            $actv = ' Cleared Expense Records between ' . $beginday . ' and ' . $endday;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct2 = ' User activity updated.';
            } else {
                $updAct2 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=payout.php');
            echo $next;
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= payout.php');
        }
        ?>
        <style>

           

        </style>
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

        <i class="alertbar"><?php echo $checkbox . $removeRecord . $carryResult . $carryResults . $expUpdated . $supUpd . $rcdUpd . $sucDel . $expDel . $updAct . $updAct1 . $updAct2; ?></i> 
        <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">EXPENSES <span class="glyphicon glyphicon-euro"></span></span></h1></header>
        </div><hr class="line-blue">

        <div class="container-fluid">
            <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        
                        <h4 class="my-center">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>
                    </div>
            </div>
            <div class="row">
                <form method="POST" action="<?php $_PHP_SELF ?>">
                    <div  class="col-md-4 col-sm-4" style="border-right: dashed 1.5px #0066cc">
                        <h4><span class="label label-primary">Search By Purchase Date: </span></h4><small>
                            From <span class="glyphicon glyphicon-calendar"></span></small><input type="date" name="startdate"></span> 
                        <span class="space"><small>To <span class="glyphicon glyphicon-calendar"></span></small><input type="date" name="enddate"></span>
                        <br/><br/><span class="space">Search By Item: <span class="glyphicon glyphicon-gift"></span>  <?php
        $itemoptionstart = '<option value="';
        $itemoptioncont = '">';
        $itemoptionend = '</option>';

        $sql = "SELECT DISTINCT purchaseDesc FROM expenses";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<input list="expenseitems" name="expenseitem" class="pull-right" placeholder="Search For Items: ..">
                  <datalist id="expenseitems">';
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo $itemoptionstart . $row["purchaseDesc"] . $itemoptioncont . $itemoptionend;
            }
            echo '</datalist>';
        } else {
            echo '0 results';
        }
        ?></span>
                        <br/><br/><span class="space">Search By Dealer: <span class="glyphicon glyphicon-user"></span> 
                            <?php
                            $supoptionstart = '<option value="';
                            $supoptioncont = '">';
                            $supoptionend = 'suppliers </option>';

                            $sql = "SELECT DISTINCT Supplier FROM expenses";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="expensesuppliers" name="expensesupplier" class="pull-right" placeholder="Search For Dealers: ..">
                  <datalist id="expensesuppliers">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $supoptionstart . $row["Supplier"] . $supoptioncont . $supoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?></span>
                        <br/><br/><span class="space"><button class="btn btn-success btn-sm" name="searchitembtn"><span class="glyphicon glyphicon-search pull-left"></span>&nbsp;SEARCH</button></span>
                        <span class="space">S/N Code: <input class="serialtext" type='text' name='serialCoded' value= '<?php echo $serialCodes; ?>' ></span> 
                    </div>
                    </form>

                    <div class="col-md-8 col-sm-8">
                    <form method="POST" action="<?php $_PHP_SELF ?>">
                        <div class="row">
                            <div class="col-md-4 col-sm-4" style="border-right: dashed 1.5px #0066cc"> 
                                Type of Expenditure: <input type="text" name="expenditure" value="<?php echo $purchaseType; ?>" >
                                <br/>Cost: <input onchange="Cost();" id="costPaid" type="text" name="cost" value="<?php echo $costPaid . $zero; ?>" >
                                <br/>Amount Paid In Cash: <input onchange="Cash();" id="cashPaid" type="text" name="cashpayment" value="<?php echo $cashPaid . $zero; ?>" > 
                                <br/>Amount Paid By Transfer: <input onchange="Trans();" id="transPaid" type="text" name="transferpayment" value="<?php echo $transferPaid . $zero; ?>" >
                            </div> 
                            <div class="col-md-4 col-sm-4 biglist" style="border-right: dashed 1.5px #0066cc">
                                Expenses Paid To (Dealer): 
                                <?php
                                $supstart = '<input list="supplier" name="paidto" value= "';
                                $supend = '" placeholder="Search For Suppliers: .." ><datalist id="supplier">';
                                $supoptionstart = '<option value="';
                                $supoptioncont = '">';
                                $supoptionend = 'supplier </option>';

                                $sql = "SELECT Name FROM suppliers";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo $supstart . $Supplier . $supend;
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $supoptionstart . $row["Name"] . $supoptioncont . $supoptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                                ?> 
                                <br/>Expenses Made By (Staff): 
                                <?php
                                $userstart = '<input list="user" name="paidby" value= "';
                                $userend = '" placeholder="Search For Staff: .." ><datalist id="user">';
                                $useroptionstart = '<option value="';
                                $useroptioncont = '">';
                                $useroptionend = 'staff </option>';

                                $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo $userstart . $purchaseBy . $userend;
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $useroptionstart . $row["lastName"] . " " . $row["firstName"] . " " . $row["accountType"] . $useroptioncont . $useroptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                                ?>
                                <br/>Authorized By: <br/>
                                <?php
                                $userstart = '<input list="user" name="issuer" value= "';
                                $userend = '" placeholder="Search For Staff: .." ><datalist id="user">';
                                $useroptionstart = '<option value="';
                                $useroptioncont = '">';
                                $useroptionend = 'staff </option>';

                                $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo $userstart . $authorizedBy . $userend;
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
                                <textarea class="form-control input-lg" name="staffN" placeholder="Staff Note:"><?php echo $staffNote; ?></textarea><br>
                                <textarea name="desc" class="form-control input-lg" placeholder="Description:"></textarea><br>
                                <div>
                                    <button class="btn btn-success btn-sm btn-block" name="updateitembtn"><span class="glyphicon glyphicon-refresh pull-left"></span>&nbsp;UPDATE EXPENDITURE</button>
                                </div>
                            <input class="pull-left" type='hidden' name='hiddenbox8' value= '<?php echo $hiddentext; ?>' >
                            <input class="pull-left" type='hidden' name='hiddenCS' value= '<?php echo $anCS; ?>' > 
                            <input class="pull-left" type='hidden' name='hiddenTS' value= '<?php echo $anTS; ?>' > 
                            <input class="pull-left" type='hidden' name='hiddenCR' value= '<?php echo $anCR; ?>' >
                            <input type='hidden' name='serialCoded' value= '<?php echo $serialCodes; ?>' >
                            </div>
                        </div>
                        </form> 
                    </div>
                </div><br>
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        <div class="tablecontainer" title="Click Checkbox To Select">
                            <?php
                            $checkboxform = "<form action='payout.php' method='POST' name='form8'>";
                            $checkbox = "<input type='submit' value='' name='box'> <br/>";
                            $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                            $hiddenboxend = "'>";

                            if (isset($_POST['searchitembtn'])) {
                                $itmE = $_POST['expenseitem'];
                                $cusE = $_POST['expensesupplier'];
                                $sdate = $_POST['startdate'];
                                $edate = $_POST['enddate'];

                                $sql = "SELECT Id, purchaseType, purchaseDesc, purchaseDate, authorizedBy, purchaseBy, Supplier, cashPaid, transferPaid, creditRemaining, lastDate FROM expenses WHERE purchaseDesc='$itmE' OR (purchaseDate='$sdate' OR purchaseDate='$edate' OR (purchaseDate<'$edate' AND purchaseDate>'$sdate')) OR Supplier='$cusE'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // output data of each row 
                                    echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Type Of Expenditure</th><th>Description</th><th>Purchase Date</th><th>Authorized By</th><th>Expenses Made By</th><th>Paid To</th><th>Amount Paid In Cash</th><th>Amount Paid By Transfer</th><th>Credit Owed</th><th>Last Payment Date</th></tr>";
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["purchaseType"] . "</td><td>" . $row["purchaseDesc"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["authorizedBy"] . "</td><td>" . $row["purchaseBy"] . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["cashPaid"] . "</td><td>" . $row["transferPaid"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                    } echo "</table>";
                                } else {
                                    echo "0 results";
                                }
                            } else {

                            $sql = "SELECT Id, purchaseType, purchaseDesc, purchaseDate, authorizedBy, purchaseBy, Supplier, cashPaid, transferPaid, creditRemaining, lastDate FROM expenses";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Type Of Expenditure</th><th>Description</th><th>Purchase Date</th><th>Authorized By</th><th>Expenses Made By</th><th>Paid To</th><th>Amount Paid In Cash</th><th>Amount Paid By Transfer</th><th>Credit Owed</th><th>Last Payment Date</th></tr>";
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["purchaseType"] . "</td><td>" . $row["purchaseDesc"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["authorizedBy"] . "</td><td>" . $row["purchaseBy"] . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["cashPaid"] . "</td><td>" . $row["transferPaid"] . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "0 results";
                            }
                        }
                            ?> 
                        </div><br/>
                        <div>
                            <form method="POST" action="<?php $_PHP_SELF ?>">
                        <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                                <span class=""><button class="btn btn-danger btn-sm" name="allClearBtn">CLEAR LIST&nbsp;<span class="glyphicon glyphicon-trash pull-right"></span></button></span> 
                                <span class="dates">From <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="beginday"></span> 
                                <span class="space dates"> To <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="endday"></span>  
                                <span class="space dates"><button class="btn btn-danger btn-sm" name="dateClearBtn">CLEAR BY PAYMENT DATE&nbsp;<span class="glyphicon glyphicon-trash pull-right"></span></button></span><hr class="line-black">
                            </form>
                        </div>
                                    <div class="col-sm-3 col-md-3 text-center">
                                        <span class="pull-left"><a href="payBalance.php">Book Balance <span class="glyphicon glyphicon-book" style="font-size:14px;"></span></a></span> 
                                    </div>
                                    <div class="col-sm-3 col-md-3 text-center">
                                        <span style="margin-left:4%;"><a href="supplierHistory.php">Suppliers' History <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a></span> 
                                    </div>
                                    <div class="col-sm-3 col-md-3 text-center">
                                        <span class="pull-right"><a href="receivedHistory.php">Received Item History <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a></span>
                                    </div>
                                    <div class="col-sm-3 col-md-3 text-center">
                                        <span class=""><a href="credits.php">Credits <span class="glyphicon glyphicon-eye-open" style="font-size:14px;"></span></a></span>
                                    </div>
                                </div>
                </div>
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
            }

        </script> 

    </body>
</html>