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
        <title>QuickInventory:View Sales History</title>

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
        $sql = "SELECT id FROM solditems WHERE quantitySold=0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($storevalue = $result->fetch_assoc()) {
                $idd = $storevalue["id"];
            }

            // sql to delete a record
            $sql = "DELETE FROM solditems WHERE id=$idd";

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
        $quantitySold = "";
        $ITotalPrice = "";
        $totalCost = "";
        $sellDate = "";
        $Customer = "";
        $staffNt = "";
        $salesPrice = "";
        $id = "";
        $selectResults = "";
        $carryResult = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];
            $sql = "SELECT id FROM solditems WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $id = $storevalue["id"];
                $checkbox = 'Option Selected';
            } else {
                $selectResults = '0 results. ';
            }

            $sql = "SELECT serialCode, Id, Category, Brand, Model, Specs, quantitySold, totalItemPrice, totalCost, salesDate, Customer, staffNote FROM solditems WHERE id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $serialCodes = $storevalue["serialCode"];
                $Category = $storevalue["Category"];
                $Brand = $storevalue["Brand"];
                $Model = $storevalue["Model"];
                $id = $storevalue["Id"];
                $Specs = $storevalue["Specs"];
                $quantitySold = $storevalue["quantitySold"];
                $ITotalPrice = $storevalue["totalItemPrice"];
                $totalCost = $storevalue["totalCost"];
                $sellDate = $storevalue["salesDate"];
                $Customer = $storevalue["Customer"];
                $staffNt = $storevalue["staffNote"];
                $salesPrice = $ITotalPrice / $quantitySold;
            } else {
                $carryResult = '0 results. ';
            }
        } echo $id . " " . $serialCodes . " " . $Category . " " . $Brand . " " . $Model . " " . $Specs . " " . $quantitySold . " " . $ITotalPrice . " " . $sellDate . " " . $Customer . " " . $staffNt . " " . $salesPrice;


        $updAct = "";
        $incUpdd = "";
        $cusUpdatedd = "";
        $allItems = "";
        $returnEdd = "";
        $recItemss = "";
        $qSld;
        $tprice;
        $aPaid = "";
        $cOwed = "";
        $sDate = "";
        $stNote = "";
        $oldQtt = "";
        $cPd = "";
        $tPd = "";
        $cRm = "";
        
        if (isset($_REQUEST["cancelSales"])) {
            $lastidd = $_REQUEST["lastid"];
            $canci = $_REQUEST["cancelitem"];
            $cancd = $_REQUEST["cancelcode"];
            $cancq = $_REQUEST["cancelquantity"];
            $canct = $_REQUEST["canceltcost"];
            $cancc = $_REQUEST["cancelcost"];
            $cancn = $_REQUEST["cancelnote"];
            $sellDate = $_REQUEST["sellDate"];
            $tCost = $_REQUEST["tCost"];
            $retN = $_REQUEST["retNote"];
            $recby = $_REQUEST["receivedby"];
            $colby = $_REQUEST["collectedby"];
            $cust = $_REQUEST["customer"];
            $rDate = date("Y-m-d");
            
            $query = "SELECT quantitySold, totalItemPrice FROM solditems WHERE Id=$lastidd";
            $queryResult = mysqli_query($conn, $query);
            if(mysqli_num_rows($queryResult) > 0){
                while($dataTable = mysqli_fetch_assoc($queryResult)){
                    $qSld = $dataTable["quantitySold"];
                    $tprice = $dataTable["totalItemPrice"];
                }
            }
            
            $qtySld = $qSld - $cancq;
            $totPrc = $tprice - $canct;
            
            $sql = "UPDATE solditems SET quantitySold=$qtySld, totalItemPrice=$totPrc, Customer='$cust', staffNote='$cancn', totalCost=$tCost WHERE Id=$lastidd";
            $queryResult = mysqli_query($conn, $sql);
            if ($queryResult === TRUE) {
                $recItemss = ' Sold items Updated. ';
            } else {
                $recItemss = 'Error Updating Sold items: ' . mysqli_error($conn);
            }
            
            $query = "SELECT salesDate, staffNote FROM solditems WHERE Id=$lastidd";
            $queryResult = $conn->query($query);
//
            if (mysqli_num_rows($queryResult) > 0) {
                // output data of each row
                $storevalue = mysqli_fetch_assoc($queryResult);
                $sDate = $storevalue["salesDate"];
                $stNote = $storevalue["staffNote"];
            }

            $sql = "INSERT INTO returnedItemsc(serialCode, salesDesc, salesDate, collectedBy, returnedTo, Customer, quantityReturned, amountReturned, returnDate, returnNote, staffNote)VALUES ('$cancd', '$canci', '$sDate', '$colby', '$recby', '$cust', $cancq, $canct, '$rDate', '$retN', '$stNote')";

            if (mysqli_query($conn, $sql) === TRUE) {
                $returnEdd = ' Returned Stock Updated. ';
            } else {
                $returnEdd = ' Error Updating Returned Stock. ' . mysqli_error($conn);
            }
//
            $actv = ' Received ' . $cancq . ' of Item ' . $cancd . ' Returned from customer permanently.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$rDate')";

            if (mysqli_query($conn, $sql) === TRUE) {
                $updAct = ' User activity updated.';
            } else {
                $updAct = ' Error updating user activity. ' . mysqli_error($conn);
            }
//
            $query = "SELECT Quantity FROM allitems WHERE serialCode='$cancd'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                $storevalue = mysqli_fetch_assoc($result);
                $oldQtt = $storevalue["Quantity"];
            }
            $newQtt = $oldQtt + $cancq;

            $sql = "UPDATE allitems SET Quantity=$newQtt WHERE serialCode='$cancd'";

            if ($conn->query($sql) === TRUE) {
                $allItems = ' Stock list Updated. ';
            } else {
                $allItems = ' Error Updating Stock list: ' . mysqli_error($conn);
            }

            $sql = "SELECT allReturns, sucPurchase, amountReturned FROM customers WHERE Name='$cust'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                $storevalue = mysqli_fetch_assoc($result);
                $oldRet = $storevalue["allReturns"];
                $oldSuc = $storevalue["sucPurchase"];
                $oldAmt = $storevalue["amountReturned"];
            }
            $sucR = $oldRet + $cancq;
            $sucS = $oldSuc - $cancq;
            $sucA = $oldAmt + $canct;

            $sql = "UPDATE customers SET allReturns=$sucR, sucPurchase=$sucS, amountReturned=$sucA, lastReturnD='$rDate' WHERE Name='$cust'";

            if (mysqli_query($conn, $sql) === TRUE) {
                $cusUpdatedd = 'Customer History Updated. ';
            } else {
                $cusUpdatedd = 'Error Updating Customer History: ' . mysqli_error($conn);
            }

            $sql = "SELECT cashPaid, transferPaid, creditRemaining FROM income WHERE salesDate='$sDate' AND Customer='$cust' AND staffNote='$cancn' AND totalCost='$tCost'";
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

            $sql = "UPDATE income SET cashPaid='$newCP', transferPaid='$newTP', creditRemaining='$newCR' WHERE salesDate='$sDate' AND Customer='$cust' AND staffNote='$cancn' AND totalCost='$tCost'";

            if ($conn->query($sql) === TRUE) {
                $incUpdd = ' Income account Updated. ';
            } else {
                $incUpdd = ' Error Updating Income account: ' . $conn->error;
            }
            $next = header('Refresh:1; URL=salesHistory.php');
            echo $next;
        }

        if (isset($_REQUEST["exchangesalesbtn"])) {
            $lastidd = $_REQUEST["lastid"];
            $canci = $_REQUEST["cancelitem"];
            $cancd = $_REQUEST["cancelcode"];
            $cancq = $_REQUEST["cancelquantity"];
            $canct = $_REQUEST["canceltcost"];
            $cancc = $_REQUEST["cancelcost"];
            $cancn = $_REQUEST["cancelnote"];
            $sellDate = $_REQUEST["sellDate"];
            $tCost = $_REQUEST["tCost"];
            $retN = $_REQUEST["retNote"];
            $recby = $_REQUEST["receivedby"];
            $colby = $_REQUEST["collectedby"];
            $cust = $_REQUEST["customer"];
            $rDate = date("Y-m-d");
            
            $query = "SELECT quantitySold, totalItemPrice FROM solditems WHERE Id=$lastidd";
            $queryResult = mysqli_query($conn, $query);
            if(mysqli_num_rows($queryResult) > 0){
                while($dataTable = mysqli_fetch_assoc($queryResult)){
                    $qSld = $dataTable["quantitySold"];
                    $tprice = $dataTable["totalItemPrice"];
                }
            }
            
            $qtySld = $qSld - $cancq;
            $totPrc = $tprice - $canct;
            
            $sql = "UPDATE solditems SET quantitySold=$qtySld, totalItemPrice=$totPrc, Customer='$cust', staffNote='$cancn', totalCost=$tCost WHERE Id=$lastidd";
            $queryResult = mysqli_query($conn, $sql);
            if ($queryResult === TRUE) {
                $recItemss = ' Sold items Updated. ';
            } else {
                $recItemss = 'Error Updating Sold items: ' . mysqli_error($conn);
            }
            

            $query = "SELECT salesDate, staffNote FROM solditems WHERE Id=$lastidd";
            $queryResult = $conn->query($query);

            if (mysqli_num_rows($queryResult) > 0) {
                // output data of each row
                $storevalue = mysqli_fetch_assoc($queryResult);
                $sDate = $storevalue["salesDate"];
                $stNote = $storevalue["staffNote"];
            }

            $sql = "INSERT INTO returnedItemsc(serialCode, salesDesc, salesDate, collectedBy, returnedTo, Customer, quantityReturned, amountReturned, returnDate, returnNote, staffNote)VALUES ('$cancd', '$canci', '$sDate', '$colby', '$recby', '$cust', $cancq, $canct, '$rDate', '$retN', '$stNote')";

            if (mysqli_query($conn, $sql) === TRUE) {
                $returnEdd = ' Returned Stock Updated. ';
            } else {
                $returnEdd = ' Error Updating Returned Stock. ' . mysqli_error($conn);
            }

            $actv = ' Received ' . $cancq . ' of Item ' . $cancd . ' Returned from customer permanently.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$rDate')";

            if (mysqli_query($conn, $sql) === TRUE) {
                $updAct = ' User activity updated.';
            } else {
                $updAct = ' Error updating user activity. ' . mysqli_error($conn);
            }

            $query = "SELECT Quantity FROM allitems WHERE serialCode='$cancd'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                $storevalue = mysqli_fetch_assoc($result);
                $oldQtt = $storevalue["Quantity"];
            }
            $newQtt = $oldQtt + $cancq;

            $sql = "UPDATE allitems SET Quantity=$newQtt WHERE serialCode='$cancd'";

            if ($conn->query($sql) === TRUE) {
                $allItems = ' Stock list Updated. ';
            } else {
                $allItems = ' Error Updating Stock list: ' . mysqli_error($conn);
            }

            $sql = "SELECT allReturns, sucPurchase, amountReturned FROM customers WHERE Name='$cust'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                $storevalue = mysqli_fetch_assoc($result);
                $oldRet = $storevalue["allReturns"];
                $oldSuc = $storevalue["sucPurchase"];
                $oldAmt = $storevalue["amountReturned"];
            }
            $sucR = $oldRet + $cancq;
            $sucS = $oldSuc - $cancq;
            $sucA = $oldAmt + $canct;

            $sql = "UPDATE customers SET allReturns=$sucR, sucPurchase=$sucS, amountReturned=$sucA, lastReturnD='$rDate' WHERE Name='$cust'";

            if (mysqli_query($conn, $sql) === TRUE) {
                $cusUpdatedd = 'Customer History Updated. ';
            } else {
                $cusUpdatedd = 'Error Updating Customer History: ' . mysqli_error($conn);
            }

            $sql = "SELECT cashPaid, transferPaid, creditRemaining FROM income WHERE salesDate='$sDate' AND Customer='$cust' AND staffNote='$cancn' AND totalCost='$tCost'";
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

            $sql = "UPDATE income SET cashPaid='$newCP', transferPaid='$newTP', creditRemaining='$newCR' WHERE salesDate='$sDate' AND Customer='$cust' AND staffNote='$cancn' AND totalCost='$tCost'";

            if ($conn->query($sql) === TRUE) {
                $incUpdd = ' Income account Updated. ';
            } else {
                $incUpdd = ' Error Updating Income account: ' . $conn->error;
            }
            $next = header('Location: viewItems.php');
            echo $next;
        }

        $updAct2 = "";
        $sucDel = "";
        if (isset($_POST['allClearBtn'])) {
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM solditems";

            if ($conn->query($sql) === TRUE) {
                $sucDel = ' Sales History cleared. ';
            } else {
                $sucDel = ' Error clearing Sales History: ' . $conn->error;
            }

            $actv = ' Cleared Entire Sales list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct2 = ' User activity updated.';
            } else {
                $updAct2 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Location: salesHistory.php');
            echo $next;
        }

        $updAct3 = "";
        $saleDel = "";
        if (isset($_POST['dateClearBtn'])) {
            $beginday = $_POST['beginday'];
            $endday = $_POST['endday'];
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM solditems WHERE salesDate='$beginday' OR salesDate='$endday' OR (salesDate<'$endday' AND salesDate>'$beginday')";

            if ($conn->query($sql) === TRUE) {
                $saleDel = ' Sales History between ' . $beginday . ' and ' . $endday . ' cleared';
            } else {
                $saleDel = ' Error clearing Sales History between ' . $beginday . ' and ' . $endday . ':' . $conn->error;
            }

            $actv = ' Cleared All Sales between ' . $beginday . ' and ' . $endday . ' from list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct3 = ' User activity updated.';
            } else {
                $updAct3 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Location:salesHistory.php');
            echo $next;
        }

        if (isset($_POST['refreshbtn'])) {
            header('Location: salesHistory.php');
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

        <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">Sales History&nbsp;&nbsp;<span class="glyphicon glyphicon-time"></span></span></h1></header>
        </div><hr class="line-blue">

        <form action="salesHistory.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate>
            <div class="container-fluid">  
                <div class="row">
                    <div class="col-md-12 col-sm-12 text-center">
                        <h4 class="date">Today is <i id="dateholder" class="badge my-blue"><?php
                                $today = date("D, d-M-Y");
                                echo "$today";
                                ?></i>
                        </h4><br>
                        <form role="form">
                            <div class="col-xs-3 col-md-3">
                                <small>Sales Date: From <span class="glyphicon glyphicon-calendar"></span></small>
                                <input type="date" class="form-control" name="startdate" autofocus>
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <small>To <span class="glyphicon glyphicon-calendar"></span></small>
                                <input type="date" class="form-control" name="enddate" autofocus>
                            </div>
                            <div class="col-xs-2 col-md-2">
                                <small>Search By Item: <span class="glyphicon glyphicon-gift"></span></small>
                                <?php
                                $itemoptionstart = '<option value="';
                                $itemoptioncont = '">';
                                $itemoptionend = '</option>';

                                $sql = "SELECT DISTINCT serialCode, Category, Brand, Model, Specs FROM solditems";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo '<input list="slditems" name="slditem" placeholder="Search For Items:">
                <datalist id="slditems">';
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
                            <div class="col-xs-2 col-md-2">
                                <small>Search By Customer:</small> <span class="glyphicon glyphicon-user"></span> 
                                <?php
                                $cusoptionstart = '<option value="';
                                $cusoptioncont = '">';
                                $cusoptionend = 'customer </option>';

                                $sql = "SELECT DISTINCT Customer FROM solditems";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo '<input list="customer" name="customer" placeholder="Search For Customers: ..">
                                    <datalist id="customer">';
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $cusoptionstart . $row["Customer"] . $cusoptioncont . $cusoptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                                ?>
                            </div>
                            <div class="col-xs-2 col-md-2">
                                <button class="btn btn-success" name="searchitembtn"><span class="glyphicon glyphicon-search"></span>&nbsp;SEARCH</button>
                            </div>
                    </div>
                </div>
            </div><hr class="line-black">

            <div class="row">
                <div  class="col-md-12 col-sm-12 text-center">
                    <div class="tablecontainer" data-toggle="tooltip" title="Click Checkbox To Select"> 
                        <?php
                        $checkboxform = "<form action='salesHistory.php' method='POST' name='form12'>";
                        $checkbox = "<input type='submit' value='' name='box'> <br/>";
                        $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                        $hiddenboxend = "'>";

                        if (isset($_POST['searchitembtn'])) {
                            $sldI = $_POST['slditem'];
                            $cust = $_POST['customer'];
                            $sdate = $_POST['startdate'];
                            $edate = $_POST['enddate'];

                            $sql = "SELECT Id, serialCode, Category, Brand, Model, Specs, quantitySold, totalItemPrice, salesDate, Customer FROM solditems WHERE serialCode='$sldI' OR Category='$sldI' OR Brand='$sldI' OR Model='$sldI' OR Specs='$sldI' OR (salesDate='$sdate' OR salesDate='$edate' OR (salesDate<'$edate' AND salesDate>'$sdate')) OR Customer='$cust'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // output data of each row 
                                echo "<table id='itemlist'>
                                    <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Category</th><th>Brand</th><th>Model</th><th>Specs</th><th>Quantity Sold</th><th>Total Item Price</th><th>Sales Date</th><th>Customer</th></tr>";
                                // output data of each row

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["Category"] . "</td><td>" . $row["Brand"] . "</td><td>" . $row["Model"] . "</td><td>" . $row["Specs"] . "</td><td>" . $row["quantitySold"] . "</td><td>" . $row["totalItemPrice"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["Customer"] . "</td></tr>";
                                } echo "</table>";
                            } else {
                                echo "0 results";
                            }
                        } else {

                            $sql = "SELECT Id, serialCode, Category, Brand, Model, Specs, quantitySold, totalItemPrice, salesDate, Customer FROM solditems";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table id='itemlist'>
                                    <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Category</th><th>Brand</th><th>Model</th><th>Specs</th><th>Quantity Sold</th><th>Total Item Price</th><th>Sales Date</th><th>Customer</th></tr>";
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["Category"] . "</td><td>" . $row["Brand"] . "</td><td>" . $row["Model"] . "</td><td>" . $row["Specs"] . "</td><td>" . $row["quantitySold"] . "</td><td>" . $row["totalItemPrice"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["Customer"] . "</td></tr>";
                                } echo "</table>";
                            } else {
                                echo "0 results";
                            }
                        }
                        ?>
                    </div><br/>
                    <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                    <br><br>
                    <div class="col-xs-1 col-sm-1">
                        <small>Sales</small>
                        <div class="btn btn-danger btn-sm" onclick="document.getElementById('cancel-sales').style.display = 'block';">CANCEL<span class="glyphicon glyphicon-remove"></span></div> 
                    </div>
                    <div class="col-xs-2 col-sm-2" id="clearentirelink">
                        <small>Clear Entire List</small><br/>
                        <button class="btn btn-danger btn-sm" name="allClearBtn">CLEAR<span class="glyphicon glyphicon-trash"></span></button>
                    </div>
                    <div class="col-xs-2 col-sm-2" id="begindaylink">
                        <small>From <span class="glyphicon glyphicon-calendar"></span></small>
                        <input type="date" class="form-control" name="beginday">
                    </div>
                    <div class="col-xs-2 col-sm-2" id="enddaylink">
                        <small>To <span class="glyphicon glyphicon-calendar"></span></small>
                        <input type="date" class="form-control" name="endday">
                    </div>
                    <div class="col-xs-2 col-sm-2" id="cleardatelink">
                        <small>Clear Sales By <span class="glyphicon glyphicon-calendar"></span></small><br/>
                        <button class="btn btn-danger btn-sm" name="dateClearBtn">CLEAR <span class="glyphicon glyphicon-trash"></span></button>
                    </div>
                    <div class="col-xs-1 col-sm-1" id="customerlink">
                        <small><a href="customerHistory.php">Customers' History <span class="glyphicon glyphicon-time"></span></a></small>
                    </div>
                    <div class="col-xs-1 col-sm-1" id="incomelink">
                        <small><a href="payin.php">Income <span class="glyphicon glyphicon-eye-open"></span></a></small>
                    </div>
                    <div class="col-xs-1 col-sm-1" id="returnslink">
                        <small><a href="returnedItems.php">Returned Items <span class="glyphicon glyphicon-eye-open"></span></a></small>
                    </div>
                </div>
            </div>
            <form>

                <div class="clearfix"></div>
                <!-- The Modal -->
                <div id="cancel-sales" class="my-modal">
                    <div class="my-modal-content my-animate-top my-card-4">
                        <div class="my-container">
                            <span class="my-closebtn" onclick="document.getElementById('cancel-sales').style.display = 'none';">&times;</span>
                            <h1 class="my-center"><span class="label label-danger">Cancel Sales</span></h1><hr class="line-black">
                            <form action="<?php $_PHP_SELF ?>" method="POST">
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
                                            <input onchange="totalCash();" id="quantity" class="form-control" type="number" name="cancelquantity" placeholder="Quantity:... " value= '<?php echo $quantitySold; ?>' required>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <small>Cost of Item:</small>
                                            <input id="totalCost" type="text" class="form-control" name="canceltcost" placeholder="Total Cost" value= '<?php echo $ITotalPrice; ?>' required> 
                                        </div>
                                    </div>
                                </div><br/>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <textarea name="retNote" class="input-sm form-control" id="retnote" placeholder="Note" style="resize: none;" required></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <small>Item Received By:</small>
                                    <?php
                                    $useroptionstart = '<option value="';
                                    $useroptioncont = '">';
                                    $useroptionend = 'staff </option>';

                                    $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo '<input list="user" class="form-control" name="receivedby" value= "" required><datalist id="user">';
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
                                    <small>Payment Returned By:</small>
                                    <?php
                                    $useroptionstart = '<option value="';
                                    $useroptioncont = '">';
                                    $useroptionend = 'staff </option>';

                                    $sql = "SELECT DISTINCT lastName, firstName, accountType FROM users";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo '<input list="user" class="form-control" name="collectedby" value= "" required><datalist id="user">';
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
                                    <small>Item Returned By:</small>
                                    <?php
                                    $cusstart = '<input type="text" class="form-control" placeholder="Item Returned By:" name="customer" value= "';
                                    $cusend = '" required><datalist id="customers">';
                                    echo $cusstart . $Customer . $cusend;
                                    ?>
                                </div><div class="clearfix"></div><br/>
                                <div class="text-center">
                                    <button class="btn-danger btn" name="cancelSales">CANCEL&nbsp;<span class="glyphicon glyphicon-remove"></span></button>
                                    <button class="btn btn-success" name="exchangesalesbtn">EXCHANGE ITEM&nbsp;<span class="glyphicon glyphicon-transfer"></span></button>
                                </div>
                                <input id="cost" type="hidden" name="cancelcost" value= '<?php echo $salesPrice; ?>' >
                                <input type="hidden" name="cancelnote" value= '<?php echo $staffNt; ?>' > 
                                <input type="hidden" name="sellDate" value= '<?php echo $sellDate; ?>' > 
                                <input type="hidden" name="tCost" value= '<?php echo $totalCost; ?>' > 
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
                        document.getElementById("clearentirelink").style.display = "none";
                        document.getElementById("begindaylink").style.display = "none";
                        document.getElementById("enddaylink").style.display = "none";
                        document.getElementById("cleardatelink").style.display = "none";
                        document.getElementById("customerlink").style.display = "none";
                        document.getElementById("returnslink").style.display = "none";
                    }
                    $(document).ready(function () {
                        $('[data-toggle="tooltip"]').tooltip();
                    });
                </script> 
                </body>
                </html>