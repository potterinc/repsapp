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
        <link rel="stylesheet" type="text/css" href="css/salesLog.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:View Received Item History</title>

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

        $removeRecord = "";
        $idd = "";
        $sql = "SELECT id FROM receiveditems WHERE amountPaid=0 AND creditOwed=0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($storevalue = $result->fetch_assoc()) {
                $idd = $storevalue["id"];
            }

            // sql to delete a record
            $sql = "DELETE FROM receiveditems WHERE id=$idd";

            if ($conn->query($sql) === TRUE) {
                $removeRecord = 'Record deleted successfully. ';
            } else {
                $removeRecord = 'Error deleting record: ' . $conn->error;
            }
        }

        $serialCodes = "";
        $Category = "";
        $Brand = "";
        $Model = "";
        $Specs = "";
        $quantityReceived = "";
        $purchasePrice = "";
        $Supplier = "";
        $staffNt = "";
        $totalPrice = "";
        $id = "";
        $selectResults = "";
        $carryResult = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];
            $sql = "SELECT id FROM receiveditems WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $id = $storevalue["id"];
                $checkbox = 'Option Selected';
            } else {
                $selectResults = '0 results. ';
            }

            $sql = "SELECT serialCode, Category, Brand, Model, Specs, quantityReceived, purchasePrice, Supplier, staffNote FROM receiveditems WHERE id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $serialCodes = $storevalue["serialCode"];
                $Category = $storevalue["Category"];
                $Brand = $storevalue["Brand"];
                $Model = $storevalue["Model"];
                $Specs = $storevalue["Specs"];
                $quantityReceived = $storevalue["quantityReceived"];
                $purchasePrice = $storevalue["purchasePrice"];
                $Supplier = $storevalue["Supplier"];
                $staffNt = $storevalue["staffNote"];
                $totalPrice = $purchasePrice * $quantityReceived;
            } else {
                $carryResult = '0 results. ';
            }
        }

        $updAct = "";
        $expUpdd = "";
        $supUpdatedd = "";
        $allItems = "";
        $returnEdd = "";
        $recItemss = "";
        $qRec = "";
        $pPrice = "";
        $aPaid = "";
        $cOwed = "";
        $pDate = "";
        $oldQtt = "";
        $cPd = "";
        $tPd = "";
        $cRm = "";
        $oldAmt = "";
        $oldRet = "";
        $oldSuc = "";
        if (isset($_POST['cancelpurchasecompbtn'])) {
            $lastidd = $_POST['lastid'];
            $canci = $_POST['cancelitem'];
            $cancd = $_POST['cancelcode'];
            $cancq = $_POST['cancelquantity'];
            $canct = $_POST['canceltcost'];
            $cancc = $_POST['cancelcost'];
            $cancn = $_POST['cancelnote'];
            $retN = $_POST['retNote'];
            $madeby = $_POST['madeby'];
            $autby = $_POST['authorizedby'];
            $supp = $_POST['supplier'];
            $rDate = date("Y-m-d");

            $sql = "SELECT quantityReceived, purchasePrice, amountPaid, creditOwed FROM receiveditems WHERE id='$lastidd' AND Supplier='$supp' AND staffNote='$cancn'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $qRec = $storevalue["quantityReceived"];
                $pPrice = $storevalue["purchasePrice"];
                $aPaid = $storevalue["amountPaid"];
                $cOwed = $storevalue["creditOwed"];
            }
            $qtyRvd = $qRec - $cancq;
            $totPrc = $pPrice * $qtyRvd;
            $credOd = $cOwed - $canct;

            if ($credOd < 0) {
                $newcred = 0;
                $newPd = $aPaid + $credOd;
            } else {
                $newcred = $credOd;
                $newPd = $aPaid;
            }

            $sql = "UPDATE receiveditems SET quantityReceived='$qtyRvd', totalPrice='$totPrc', amountPaid='$newPd', creditOwed='$newcred' WHERE id='$lastidd' AND Supplier='$supp' AND staffNote='$cancn'";

            if ($conn->query($sql) === TRUE) {
                $recItemss = ' Received items Updated. ';
            } else {
                $recItemss = 'Error Updating Received items: ' . $conn->error;
            }

            $sql = "SELECT purchaseDate FROM receiveditems WHERE id='$lastidd'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $pDate = $storevalue["purchaseDate"];
            }

            $sql = "INSERT INTO returnedItemss(serialCode, purchaseDesc, purchaseDate, authorizedBy, returnedBy, Supplier, quantityReturned, amountReturned, returnDate, returnNote)VALUES ('$cancd', '$canci', '$pDate', '$autby', '$madeby', '$supp', '$cancq', '$canct', '$rDate', '$retN')";

            if ($conn->query($sql) === TRUE) {
                $returnEdd = ' Returned Stock Updated. ';
            } else {
                $returnEdd = ' Error Updating Returned Stock. ' . $conn->error;
            }

            $actv = ' Returned ' . $cancq . ' of Item ' . $cancd . ' to supplier permanently.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$rDate')";

            if ($conn->query($sql) === TRUE) {
                $updAct = ' User activity updated.';
            } else {
                $updAct = ' Error updating user activity. ' . $conn->error;
            }

            $sql = "SELECT Quantity FROM allitems WHERE serialCode='$cancd'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldQtt = $storevalue["Quantity"];
            }
            $newQtt = $oldQtt - $cancq;

            $sql = "UPDATE allitems SET Quantity='$newQtt' WHERE serialCode='$cancd'";

            if ($conn->query($sql) === TRUE) {
                $allItems = ' Stock list Updated. ';
            } else {
                $allItems = ' Error Updating Stock list: ' . $conn->error;
            }

            $sql = "SELECT allReturns, sucSupplies, amountReturned FROM suppliers WHERE Name='$supp'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldRet = $storevalue["allReturns"];
                $oldSuc = $storevalue["sucSupplies"];
                $oldAmt = $storevalue["amountReturned"];
            }
            $sucR = $oldRet + $cancq;
            $sucS = $oldSuc - $cancq;
            $sucA = $oldAmt + $canct;

            $sql = "UPDATE suppliers SET allReturns='$sucR', sucSupplies='$sucS', amountReturned='$sucA', lastReturnD='$rDate' WHERE Name='$supp'";

            if ($conn->query($sql) === TRUE) {
                $supUpdatedd = 'Supplier History Updated. ';
            } else {
                $supUpdatedd = 'Error Updating Supplier History: ' . $conn->error;
            }

            $sql = "SELECT cashPaid, transferPaid, creditRemaining FROM expenses WHERE serialCode='$cancd' AND Supplier='$supp' AND staffNote='$cancn'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $cPd = $storevalue["cashPaid"];
                $tPd = $storevalue["transferPaid"];
                $cRm = $storevalue["creditRemaining"];
            }
            $anCR = $cRm - $canct;

            if ($anCR < 0) {
                $newCR = 0;
                $anTP = $tPd + $anCR;
            } else {
                $newCR = $anCR;
                $anTP = $tPd;
            }

            if ($anTP < 0) {
                $newTP = 0;
                $anCP = $cPd + $anTP;
            } else {
                $newTP = $anTP;
                $anCP = $cPd;
            }

            $newCP = $anCP;

            $sql = "UPDATE expenses SET cashPaid='$newCP', transferPaid='$newTP', creditRemaining='$newCR' WHERE serialCode='$cancd' AND Supplier='$supp' AND staffNote='$cancn'";

            if ($conn->query($sql) === TRUE) {
                $expUpdd = ' Expense account Updated. ';
            } else {
                $expUpdd = ' Error Updating Expense account: ' . $conn->error;
            }
            //$next = header('Refresh:1; URL=receivedHistory.php');
            //echo $next;
        }

        $updAct1 = "";
        $expUpd = "";
        $supUpdated = "";
        $allItem = "";
        $returnEd = "";
        $recItems = "";
        $qRec = "";
        $pPrice = "";
        $aPaid = "";
        $cOwed = "";
        $pDate = "";
        $oldQtt = "";
        $cPd = "";
        $tPd = "";
        $cRm = "";
        if (isset($_POST['exchangepurchasebtn'])) {
            $lastidd = $_POST['lastid'];
            $canci = $_POST['cancelitem'];
            $cancd = $_POST['cancelcode'];
            $cancq = $_POST['cancelquantity'];
            $canct = $_POST['canceltcost'];
            $cancc = $_POST['cancelcost'];
            $cancn = $_POST['cancelnote'];
            $retN = $_POST['retNote'];
            $madeby = $_POST['madeby'];
            $autby = $_POST['authorizedby'];
            $supp = $_POST['supplier'];
            $rDate = date("Y-m-d");

            $sql = "SELECT quantityReceived, purchasePrice, amountPaid, creditOwed FROM receiveditems WHERE id='$lastidd' AND Supplier='$supp'AND staffNote='$cancn'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $qRec = $storevalue["quantityReceived"];
                $pPrice = $storevalue["purchasePrice"];
                $aPaid = $storevalue["amountPaid"];
                $cOwed = $storevalue["creditOwed"];
            }
            $qtyRvd = $qRec - $cancq;
            $totPrc = $pPrice * $qtyRvd;
            $credOd = $cOwed - $canct;

            if ($credOd < 0) {
                $newcred = 0;
                $newPd = $aPaid + $credOd;
            } else {
                $newcred = $credOd;
                $newPd = $aPaid;
            }

            $sql = "UPDATE receiveditems SET quantityReceived='$qtyRvd', totalPrice='$totPrc', amountPaid='$newPd', creditOwed='$newcred' WHERE id='$lastidd' AND Supplier='$supp'AND staffNote='$cancn'";

            if ($conn->query($sql) === TRUE) {
                $recItems = ' Received items Updated. ';
            } else {
                $recItems = 'Error Updating Received items: ' . $conn->error;
            }

            $sql = "SELECT purchaseDate FROM receiveditems WHERE id='$lastidd'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $pDate = $storevalue["purchaseDate"];
            }

            $sql = "INSERT INTO returnedItemss(serialCode, purchaseDesc, purchaseDate, authorizedBy, returnedBy, Supplier, quantityReturned, amountReturned, returnDate, returnNote)VALUES ('$cancd', '$canci', '$pDate', '$autby', '$madeby', '$supp', '$cancq', '$canct', '$rDate', '$retN')";

            if ($conn->query($sql) === TRUE) {
                $returnEd = ' Returned Stock Updated. ';
            } else {
                $returnEd = ' Error Updating Returned Stock. ' . $conn->error;
            }

            $actv = ' Returned ' . $cancq . ' of Item ' . $cancd . ' to supplier for exchange.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$rDate')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = ' User activity updated.';
            } else {
                $updAct1 = ' Error updating user activity. ' . $conn->error;
            }

            $sql = "SELECT Quantity FROM allitems WHERE serialCode='$cancd'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldQtt = $storevalue["Quantity"];
            }
            $newQtt = $oldQtt - $cancq;

            $sql = "UPDATE allitems SET Quantity='$newQtt' WHERE serialCode='$cancd'";

            if ($conn->query($sql) === TRUE) {
                $allItem = ' Stock list Updated. ';
            } else {
                $allItem = ' Error Updating Stock list: ' . $conn->error;
            }

            $sql = "SELECT allReturns, sucSupplies, amountReturned FROM suppliers WHERE Name='$supp'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldRet = $storevalue["allReturns"];
                $oldSuc = $storevalue["sucSupplies"];
                $oldAmt = $storevalue["amountReturned"];
            }
            $sucR = $oldRet + $cancq;
            $sucS = $oldSuc - $cancq;
            $sucA = $oldAmt + $canct;

            $sql = "UPDATE suppliers SET allReturns='$sucR', sucSupplies='$sucS', amountReturned='$sucA', lastReturnD='$rDate' WHERE Name='$supp'";

            if ($conn->query($sql) === TRUE) {
                $supUpdated = 'Supplier History Updated. ';
            } else {
                $supUpdated = 'Error Updating Supplier History: ' . $conn->error;
            }

            $sql = "SELECT cashPaid, transferPaid, creditRemaining FROM expenses WHERE serialCode='$cancd' AND Supplier='$supp'AND staffNote='$cancn'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $cPd = $storevalue["cashPaid"];
                $tPd = $storevalue["transferPaid"];
                $cRm = $storevalue["creditRemaining"];
            }
            $anCR = $cRm - $canct;

            if ($anCR < 0) {
                $newCR = 0;
                $anTP = $tPd + $anCR;
            } else {
                $newCR = $anCR;
                $anTP = $tPd;
            }

            if ($anTP < 0) {
                $newTP = 0;
                $anCP = $cPd + $anTP;
            } else {
                $newTP = $anTP;
                $anCP = $cPd;
            }

            $newCP = $anCP;

            $sql = "UPDATE expenses SET cashPaid='$newCP', transferPaid='$newTP', creditRemaining='$newCR' WHERE serialCode='$cancd' AND Supplier='$supp'AND staffNote='$cancn'";

            if ($conn->query($sql) === TRUE) {
                $expUpd = ' Expense account Updated. ';
            } else {
                $expUpd = ' Error Updating Expense account: ' . $conn->error;
            }
            $next = header('Refresh:1; URL=viewItems.php');
            echo $next;
        }

        $updAct2 = "";
        $sucDel = "";
        if (isset($_POST['allClearBtn'])) {
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM receiveditems";

            if ($conn->query($sql) === TRUE) {
                $sucDel = ' Received Items History cleared. ';
            } else {
                $sucDel = ' Error clearing Received Items History: ' . $conn->error;
            }

            $actv = ' Cleared Entire Received Items list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct2 = ' User activity updated.';
            } else {
                $updAct2 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=receivedHistory.php');
            echo $next;
        }

        $updAct3 = "";
        $purcDel = "";
        if (isset($_POST['dateClearBtn'])) {
            $beginday = $_POST['beginday'];
            $endday = $_POST['endday'];
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM receiveditems WHERE purchaseDate='$beginday' OR purchaseDate='$endday' OR (purchaseDate<'$endday' AND purchaseDate>'$beginday')";

            if ($conn->query($sql) === TRUE) {
                $purcDel = ' Received Items History between ' . $beginday . ' and ' . $endday . ' cleared';
            } else {
                $purcDel = ' Error clearing Received Items History between ' . $beginday . ' and ' . $endday . ':' . $conn->error;
            }

            $actv = ' Cleared All Received Items between ' . $beginday . ' and ' . $endday . ' from list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct3 = ' User activity updated.';
            } else {
                $updAct3 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=receivedHistory.php');
            echo $next;
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= receivedHistory.php');
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

        <i class="alertbar"><?php echo $checkbox . $removeRecord . $selectResults . $carryResult . $recItemss . $returnEdd . $allItems . $supUpdatedd . $expUpdd . $recItems . $returnEd . $allItem . $supUpdated . $expUpd . $sucDel . $purcDel . $updAct . $updAct1 . $updAct2 . $updAct3; ?></i> 
        
        <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">Recieved Order(s)&nbsp;&nbsp;<span class="glyphicon glyphicon-download-alt"></span></span></h1></header>
        </div><hr class="line-blue">

            <form action="receivedHistory.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate>
        <div class="container-fluid">
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">   
                        <h4 class="">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>
                        <div class="col-xs-3">
                            <small>Purchase Date: From <span class="glyphicon glyphicon-calendar"></span></small>
                            <input type="date" class="form-control" name="startdate" autofocus>
                        </div>
                        <div class="col-xs-3">
                            <small>To <span class="glyphicon glyphicon-calendar"></span></small>
                            <input type="date" class="form-control" name="enddate" autofocus>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <small>Search By Item: <span class="glyphicon glyphicon-gift"></span></small>
<?php
$itemoptionstart = '<option value="';
$itemoptioncont = '">';
$itemoptionend = '</option>';

$sql = "SELECT DISTINCT serialCode, Category, Brand, Model, Specs FROM receiveditems";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<input list="rvditems" name="rvditem" placeholder="Search For Items:">
                <datalist id="rvditems">';
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo $itemoptionstart . $row["serialCode"] . $itemoptioncont . $itemoptionend;
        echo $itemoptionstart . $row["Category"] . $itemoptioncont . $itemoptionend;
        echo $itemoptionstart . $row["Brand"] . $itemoptioncont . $itemoptionend;
        echo $itemoptionstart . $row["Model"] . $itemoptioncont . $itemoptionend;
        echo $itemoptionstart . $row["Specs"] . $itemoptioncont . $itemoptionend;
    }
    echo '</datalist>';
} else {
    echo '0 results';
}
?>
</div>
                    <div class="col-xs-2">
                        <small>Search By Supplier:</small> <span class="glyphicon glyphicon-user"></span> 
                            <?php
                            $supoptionstart = '<option value="';
                            $supoptioncont = '">';
                            $supoptionend = 'supplier </option>';

                            $sql = "SELECT DISTINCT Supplier FROM receiveditems";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="supplier" name="supplier" placeholder="Search For Suppliers: ..">
                                    <datalist id="supplier">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $supoptionstart . $row["Supplier"] . $supoptioncont . $supoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?>
                            </div>
                            <div class="col-xs-2">
                                <button class="btn btn-success" name="searchitembtn"><span class="glyphicon glyphicon-search"></span> <span>&nbsp;SEARCH</span></button>
                            </div>
                    </div>
                    </div>
                </div><hr class="line-black">

                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        <div class="tablecontainer" data-toggle="tooltip" title="Click Checkbox To Select">
<?php
$checkboxform = "<form action='receivedHistory.php' method='POST' name='form6'>";
$checkbox = "<input type='submit' value='' name='box'> <br/>";
$hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
$hiddenboxend = "'>";

if (isset($_POST['searchitembtn'])) {
    $rvdI = $_POST['rvditem'];
    $supp = $_POST['supplier'];
    $sdate = $_POST['startdate'];
    $edate = $_POST['enddate'];

    $sql = "SELECT Id, serialCode, Category, Brand, Model, Specs, quantityReceived, purchasePrice, totalPrice, amountpaid, creditOwed, sellingPrice, purchaseDate, Supplier FROM receiveditems WHERE serialCode='$rvdI' OR Category='$rvdI' OR Brand='$rvdI' OR Model='$rvdI' OR Specs='$rvdI' OR (purchaseDate='$sdate' OR purchaseDate='$edate' OR (purchaseDate<'$edate' AND purchaseDate>'$sdate')) OR Supplier='$supp'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row 
        echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Category</th><th>Brand</th><th>Model</th><th>Specs</th><th>Quantity Received</th><th>Unit Purchase Price</th><th>Total Price For Item</th><th>Total Amount Paid</th><th>Credit Owed</th><th>Selling Price</th><th>Purchase Date</th><th>Supplier</th></tr>";
        // output data of each row

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["Category"] . "</td><td>" . $row["Brand"] . "</td><td>" . $row["Model"] . "</td><td>" . $row["Specs"] . "</td><td>" . $row["quantityReceived"] . "</td><td>" . $row["purchasePrice"] . "</td><td>" . $row["totalPrice"] . "</td><td>" . $row["amountpaid"] . "</td><td>" . $row["creditOwed"] . "</td><td>" . $row["sellingPrice"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["Supplier"] . "</td></tr>";
        } echo "</table>";
    } else {
        echo "0 results";
    }
} else {

$sql = "SELECT Id, serialCode, Category, Brand, Model, Specs, quantityReceived, purchasePrice, totalPrice, amountpaid, creditOwed, sellingPrice, purchaseDate, Supplier FROM receiveditems";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Category</th><th>Brand</th><th>Model</th><th>Specs</th><th>Quantity Received</th><th>Unit Purchase Price</th><th>Total Price For Item</th><th>Total Amount Paid</th><th>Credit Owed</th><th>Selling Price</th><th>Purchase Date</th><th>Supplier</th></tr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["Category"] . "</td><td>" . $row["Brand"] . "</td><td>" . $row["Model"] . "</td><td>" . $row["Specs"] . "</td><td>" . $row["quantityReceived"] . "</td><td>" . $row["purchasePrice"] . "</td><td>" . $row["totalPrice"] . "</td><td>" . $row["amountpaid"] . "</td><td>" . $row["creditOwed"] . "</td><td>" . $row["sellingPrice"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["Supplier"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
}
?>
</div><br>
                        <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                                <br><br>
                        <div class="col-xs-1 col-sm-1">
                            <small> Purchase</small>
                            <div class="btn btn-danger btn-sm" onclick="document.getElementById('cancel-sales').style.display='block';">CANCEL<span class="glyphicon glyphicon-remove"></span></div>
                        </div>
                        <div class="col-xs-2 col-sm-2">
                            <small>Clear Entire List</small><br/>
                            <button class="btn btn-danger btn-sm" name="allClearBtn">CLEAR <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </div>
                        <div class="col-xs-2 col-sm-2">
                            <small>From <span class="glyphicon glyphicon-calendar"></span></small>
                            <input type="date" class="form-control" name="beginday">
                        </div>
                        <div class="col-xs-2 col-sm-2">
                            <small> To <span class="glyphicon glyphicon-calendar"></span></small>
                            <input type="date" class="form-control" name="endday">
                        </div> 
                        <div class="col-xs-2 col-sm-2">
                            <small>Clear Purchase By <span class="glyphicon glyphicon-calendar"></span></small>
                            <button class="btn btn-danger btn-sm" name="dateClearBtn">CLEAR<span class="glyphicon glyphicon-trash"></span></button>
                        </div>
                        <div class="col-xs-1 col-sm-1">
                            <small>
                                <a href="supplierHistory.php">Suppliers' History <span class="glyphicon glyphicon-time"></span></a>
                            </small>
                        </div>
                        <div class="col-xs-1">
                            <small>
                                <a href="payout.php">Expenses <span class="glyphicon glyphicon-eye-open"></span></a>
                            </small>
                        </div>
                        <div class="col-xs-1 col-sm-1">
                            <small><a href="returnedItems.php">Returned Items <span class="glyphicon glyphicon-eye-open"></span></a></small>
                        </div>
                    </div>
                </div>
        </form>

        <div class="clearfix"></div>
        <!-- The Modal -->
        <div id="cancel-sales" class="my-modal">
            <div class="my-modal-content my-animate-top my-card-4">
            <div class="my-container">
                <span class="my-closebtn" onclick="document.getElementById('cancel-sales').style.display='none';">&times;</span>
                <h1 class="my-center"><span class="label label-danger">Cancel Purchase</span></h1><hr class="line-black">
                <form action="receivedHistory.php" method="POST">
                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <small>Item Description:</small>
                        <input type="text" class="form-control" name="cancelitem" value= '<?php echo $Brand . " " . $Model . " " . $Specs . " " . $Category . " " . $serialCodes; ?>' required>
                        <input type="hidden" name="lastid" value= "<?php echo $id; ?>" > 
                    </div>
                </div>
                <div class="row">
                <div class="col-sm-12 col-md-12">
                <div class="col-sm-4 col-md-4">
                <small>Serial Number:</small>
                    <input type="text" name="cancelcode" class="form-control" value= '<?php echo $serialCodes; ?>' required>
                </div>
                <div class="col-sm-4 col-md-4">
                <small>Quantity:</small>
                    <input onchange="totalCash();" id="quantity" class="form-control" type="number" name="cancelquantity" placeholder="Quantity:... " value= '<?php echo $quantityReceived; ?>' required>
                </div>
                <div class="col-sm-4 col-md-4">
                    <small>Cost of Item:</small>
                    <input id="totalCost" type="text" class="form-control" name="canceltcost" placeholder="Total Cost" value= '<?php echo $totalPrice; ?>' required> 
                </div>
                </div>
                </div><br/>
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                            <textarea name="retNote" class="input-sm form-control" id="retnote" placeholder="Note" style="resize: none;" required></textarea>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4">
                    <small>Returned By:</small>
                    <?php
                        $useroptionstart = '<option value="';
                        $useroptioncont = '">';
                        $useroptionend = 'staff </option>';

                        $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo '<input list="user" class="form-control" name="madeby" value= "" required><datalist id="user">';
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo $useroptionstart . $row["lastName"] . " " . $row["firstName"] . " " . $row["accountType"] . $useroptioncont . $useroptionend;
                            }
                            echo '</datalist>';
                        } else {
                            echo '0 results';
                        }
                    ?>
                    </div>
                    <div class="col-sm-4 col-md-4">
                    <small>Authorized By:</small>
                    <?php
                    $useroptionstart = '<option value="';
                    $useroptioncont = '">';
                    $useroptionend = 'staff </option>';

                    $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<input list="user" class="form-control" name="authorizedby" value= "" required><datalist id="user">';
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo $useroptionstart . $row["lastName"] . " " . $row["firstName"] . " " . $row["accountType"] . $useroptioncont . $useroptionend;
                        }
                        echo '</datalist>';
                    } else {
                        echo '0 results';
                    }
                    ?> 
                    </div>
                    <div class="col-sm-4 col-md-4">
                    <small>Returned To:</small>
                    <?php
                    $supstart = '<input list="suppliers" class="form-control" name="supplier" value= "';
                    $supend = '" placeholder="Item Returned To:" required><datalist id="suppliers">';
                    $supoptionstart = '<option value="';
                    $supoptioncont = '">';
                    $supoptionend = 'supplier </option>';

                    $sql = "SELECT Supplier FROM receiveditems";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo $supstart . $Supplier . $supend;
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo $supoptionstart . $row["Supplier"] . $supoptioncont . $supoptionend;
                        }
                        echo '</datalist>';
                    } else {
                        echo '0 results';
                    }
                    ?>
                    </div><div class="clearfix"></div><br/>
                        <div class="text-center">
                            <button class="btn-danger btn" name="cancelpurchasecompbtn">CANCEL&nbsp;<span class="glyphicon glyphicon-remove"></span></button>
                            <button class="btn btn-success" name="exchangepurchasebtn">EXCHANGE ITEM&nbsp;<span class="glyphicon glyphicon-transfer"></span></button>
                        </div>
                            <input id="cost" type="hidden" name="cancelcost" value= '<?php echo $purchasePrice; ?>' >
                            <input type="hidden" name="cancelnote" value= '<?php echo $staffNt; ?>' > 
                    </form><br>
                </div>
        </div>
        </div><div class="clearfix"></div><br>
        <div class="container-fluid copy">
                <footer class="text-center">
                    <small>&copy; 2018 All rights Reserved, Intelogiic Global Resources</small>
                </footer>
        </div>

        <script src="mymodalscript.js"></script>
        <script src="js/myscript.js"></script>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <script>
                        function totalCash() {
                            var quantity = document.getElementById("quantity").value;
                            var cost = document.getElementById("cost").value;
                            quantity = parseInt(quantity);
                            cost = parseInt(cost);
                            total = quantity * cost;

                            document.getElementById("totalCost").value = total;
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

              $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
              });
        </script> 

    </body>
</html>