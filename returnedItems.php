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
        <link rel="stylesheet" type="text/css" href="css/returns.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:All Returned Items</title> 

        <?php
        $Note = "Return Note: ...";

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

        $noResult = "";
        $serNC = "";

        $sql = "SELECT serialCode FROM returneditemsc";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row 
            $count = 0;

            while ($row = $result->fetch_assoc()) {
                $count += 1;
                $serNC = $row["serialCode"];
                echo "<p hidden id='totalSC$count'>" . $serNC . "</p>";
            } echo "<p hidden id='totalcounted'>$count</p>";
        } else {
            $noResult = '0 ';
        }

        $noResultt = "";
        $serNC = "";

        $sql = "SELECT serialCode FROM returneditemss";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row 
            $count = 0;

            while ($row = $result->fetch_assoc()) {
                $count += 1;
                $serNC = $row["serialCode"];
                echo "<p hidden id='totalSN$count'>" . $serNC . "</p>";
            } echo "<p hidden id='totalcountt'>$count</p>";
        } else {
            $noResultt = '0 ';
        }

        $hiddentexts = "";
        $retNs = "";
        $createResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";

            $sql = "SELECT id, returnNote FROM returneditemss WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $hiddentexts = $storevalue["id"];
                $retNs = $storevalue["returnNote"];
                $checkbox = 'Option Selected';
            } else {
                $createResults = '0 results. ';
            }
        }

        $hiddentext = "";
        $retNc = "";
        $createResult = "";
        if (isset($_POST['boxes'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";

            $sql = "SELECT id, returnNote FROM returneditemsc WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $hiddentext = $storevalue["id"];
                $retNc = $storevalue["returnNote"];
            } else {
                $createResult = '0 results. ';
            }
        }

        $updAct = "";
        $sucDel = "";
        if (isset($_POST['clearcustreturns'])) {
            $rDate = date("Y-m-d");

            $sql = "DELETE FROM returneditemsc";

            if ($conn->query($sql) === TRUE) {
                $sucDel = ' Customer Returns cleared. ';
            } else {
                $sucDel = ' Error clearing Customer Returns: ' . $conn->error;
            }

            $actv = ' Cleared Entire list of Items Returned by Customers.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$rDate')";

            if ($conn->query($sql) === TRUE) {
                $updAct = ' User activity updated.';
            } else {
                $updAct = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=returnedItems.php');
            echo $next;
        }

        $updAct1 = "";
        $sucDelt = "";
        if (isset($_POST['clearsuppreturns'])) {
            $rDate = date("Y-m-d");

            $sql = "DELETE FROM returneditemss";

            if ($conn->query($sql) === TRUE) {
                $sucDelt = ' Supplier Returns cleared. ';
            } else {
                $sucDelt = ' Error clearing Supplier Returns: ' . $conn->error;
            }

            $actv = ' Cleared Entire list of Items Returned to Suppliers.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$rDate')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = ' User activity updated.';
            } else {
                $updAct1 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=returnedItems.php');
            echo $next;
        }

        $updAct2 = "";
        $cusDel = "";
        if (isset($_POST['custClearBtn'])) {
            $beginday = $_POST['beginday'];
            $endday = $_POST['endday'];
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM returneditemsc WHERE returnDate='$beginday' OR returnDate='$endday' OR (returnDate<'$endday' AND returnDate>'$beginday')";

            if ($conn->query($sql) === TRUE) {
                $cusDel = ' Customer Returns between ' . $beginday . ' and ' . $endday . ' cleared';
            } else {
                $cusDel = ' Error clearing Customer Returns between ' . $beginday . ' and ' . $endday . ':' . $conn->error;
            }

            $actv = ' Cleared list of Items Returned by Customers between ' . $beginday . ' and ' . $endday;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct2 = ' User activity updated.';
            } else {
                $updAct2 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=returnedItems.php');
            echo $next;
        }

        $updAct3 = "";
        $supDel = "";
        if (isset($_POST['suppClearBtn'])) {
            $beginday = $_POST['beginday'];
            $endday = $_POST['endday'];
            $lastD = date("Y-m-d");

            $sql = "DELETE FROM returneditemss WHERE returnDate='$beginday' OR returnDate='$endday' OR (returnDate<'$endday' AND returnDate>'$beginday')";

            if ($conn->query($sql) === TRUE) {
                $supDel = ' Supplier Returns between ' . $beginday . ' and ' . $endday . ' cleared';
            } else {
                $supDel = ' Error clearing Supplier Returns between ' . $beginday . ' and ' . $endday . ':' . $conn->error;
            }

            $actv = ' Cleared list of Items Returned to Suppliers between ' . $beginday . ' and ' . $endday;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct3 = ' User activity updated.';
            } else {
                $updAct3 = ' Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=returnedItems.php');
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
            <div id="mnguser"> <label style="border-top:2px solid black;"><a href="manageusers.php">Manage Users' Accounts</a></label> </div>
        </nav>

        <i class="alertbar"><?php echo $checkbox . $createResults . $createResult . $sucDel . $sucDelt . $cusDel . $supDel . $updAct . $updAct1 . $updAct2 . $updAct3; ?></i> 
        <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">RETURNED ITEMS&nbsp;&nbsp;<span class="glyphicon glyphicon-repeat"></span></h1></header>
        </div><hr class="line-blue">

        <div class="container-fluid"> 
            <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        
                        <h4 class="my-center">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>
                    </div>
                </div>
            <div class="row">
                <div  class="col-md-12 col-sm-12 text-center"> 
                    <div class="my-accordion searchbar">
                        <button onclick="myFunction('Demo1')" class="btn btn-block btn-success"><span>SEARCH ITEMS </span> <span class="glyphicon glyphicon-search"></span></button><br>
                        <form action="returnedItems.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate> 
                            <div id="Demo1" class="my-accordion-content my-center my-container my-animate-zoom"><br/>
                                <span class="space"><span class="glyphicon glyphicon-gift"></span> 
                                    <?php
                                    $itemoptionstart = '<option value="';
                                    $itemoptioncont = '">';
                                    $itemoptionend = '</option>';

                                    $sql = "SELECT DISTINCT salesDesc FROM returneditemsc";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo '<input list="returnitems" name="returnitemc" placeholder="Search For Item Returned By Customer: ">
                  <datalist id="returnitems">';
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            echo $itemoptionstart . $row["salesDesc"] . $itemoptioncont . $itemoptionend;
                                        }
                                        echo '</datalist>';
                                    } else {
                                        echo '0 results';
                                    }
                                    ?></span> 
                                <span class="space"><span class="glyphicon glyphicon-user"></span> 
                                    <?php
                                    $cusoptionstart = '<option value="';
                                    $cusoptioncont = '">';
                                    $cusoptionend = 'customer </option>';

                                    $sql = "SELECT DISTINCT Customer FROM returneditemsc";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo '<input list="returncustomers" name="returncustomer" class="little" placeholder="Search For Customer: ">
                  <datalist id="returncustomers">';
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            echo $cusoptionstart . $row["Customer"] . $cusoptioncont . $cusoptionend;
                                        }
                                        echo '</datalist>';
                                    } else {
                                        echo '0 results';
                                    }
                                    ?></span>    
                                <span class="space"><span class="glyphicon glyphicon-gift"></span> 
                                    <?php
                                    $itemoptionstart = '<option value="';
                                    $itemoptioncont = '">';
                                    $itemoptionend = '</option>';

                                    $sql = "SELECT DISTINCT purchaseDesc FROM returneditemss";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo '<input list="returnitem" name="returnitems" placeholder="Search For Item Returned To Supplier: ">
                  <datalist id="returnitem">';
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            echo $itemoptionstart . $row["purchaseDesc"] . $itemoptioncont . $itemoptionend;
                                        }
                                        echo '</datalist>';
                                    } else {
                                        echo '0 results';
                                    }
                                    ?></span>
                                <span class="space"><span class="glyphicon glyphicon-user"></span> 
                                    <?php
                                    $supoptionstart = '<option value="';
                                    $supoptioncont = '">';
                                    $supoptionend = 'supplier </option>';

                                    $sql = "SELECT DISTINCT Supplier FROM returneditemss";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        echo '<input list="returnsuppliers" name="returnsupplier" class="little" placeholder="Search For Supplier: ">
                  <datalist id="returnsuppliers">';
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            echo $supoptionstart . $row["Supplier"] . $supoptioncont . $supoptionend;
                                        }
                                        echo '</datalist>';
                                    } else {
                                        echo '0 results';
                                    }
                                    ?></span> 
                                <br/><br/>
                                <span class="space"><button class="btn btn-primary" name="searchitembtn1" style="vertical-align:middle"><span class="glyphicon glyphicon-zoom-in pull-left"></span> <span>SEARCH CUSTOMER</span></button></span> 
                                <span class="space">Search By Return Date: From <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="startdate"></span> 
                                <span class="space"> To <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="enddate"></span> 
                                <span class="space"><button class="btn btn-primary" name="searchitembtn2" style="vertical-align:middle"><span class="glyphicon glyphicon-zoom-in pull-left"></span>SEARCH SUPPLIER</button></span> 
                            </div>
                        </form> 
                    </div> 
                    <div class="my-accordion searchbar">
                        <button onclick="myFunction('Demo2')" class="btn btn-danger btn-block">CLEAR LIST<span class="glyphicon glyphicon-trash"></span></button> 
                        <form action="returnedItems.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate> 
                            <div id="Demo2" class="my-accordion-content my-center my-container my-animate-zoom"><br/> 
                                <span class="pull-left btn btn-danger cancelsales">Entire List <span class="glyphicon glyphicon-trash pull-right"></span></span> 
                                <span>By Return Date: From <span class="glyphicon glyphicon-calendar"></span><input type="date" name="beginday"></span> 
                                To <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="endday">
                                <button class="btn btn-danger" name="custClearBtn" style="vertical-align:middle"><span>Clear Customer List</span> <span class="glyphicon glyphicon-trash pull-right"></span></button> 
                                <button class="btn btn-danger" name="suppClearBtn" style="vertical-align:middle"><span>Clear Supplier List</span> <span class="glyphicon glyphicon-trash pull-right"></span></button> 
                            </div> 
                        </form> 
                    </div>
                </div>
            </div>
        </div><br/>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 col-sm-2 text-center">
                    <form method="POST" action="<?php $_PHP_SELF ?>">
                        <button class="btn btn-success pull-right" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                    </form>
                </div>
                <div class="col-md-3 col-sm-3 text-center">
                    <input type='hidden' name='hiddenbox11' value= '<?php echo $hiddentexts . $hiddentext; ?>' > 
                    <span class=""><a href="salesHistory.php">See Sales History <span class="glyphicon glyphicon-backward" style="font-size:14px;"></span></a></span>
                </div>
                <div class="col-md-3 col-sm-3 text-center">
                    <span class="pull-right"><a href="receivedHistory.php">See Received History <span class="glyphicon glyphicon-backward" style="font-size:14px;"></span></a></span>
                </div>
                <div class="col-md-4 col-sm-4 text-center">
                    <textarea class="form-control input-lg" name="staffN" placeholder="Return Note:"><?php echo $retNs . $retNc; ?></textarea>
                </div>
            </div><br/>
            <div class="row">
                <div class="col-md-6 col-sm-6 text-center">
                    <div id="topseebar"><span id="sumCusRets"><?php echo $noResult; ?></span> <span>Items Returned By Customer</span> <span class="glyphicon glyphicon-log-in"></span></div> 
                    <div class="tablecontainer" title="Click Checkbox To Select">
                        <?php
                        $checkboxform = "<form action='returnedItems.php' method='POST' name='form12'>";
                        $checkbox = "<input type='submit' value='' name='boxes'> <br/>";
                        $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                        $hiddenboxend = "'>";

                        if (isset($_POST['searchitembtn1'])) {
                            $rItem = $_POST['returnitemc'];
                            $rcust = $_POST['returncustomer'];
                            $sdate = $_POST['startdate'];
                            $edate = $_POST['enddate'];

                            $sql = "SELECT id, serialCode, salesDesc, salesDate, collectedBy, returnedTo, Customer, quantityReturned, amountReturned, returnDate, returnNote FROM returneditemsc WHERE salesDesc='$rItem' OR (returnDate='$sdate' OR returnDate='$edate' OR (returnDate<'$edate' AND returnDate>'$sdate')) OR Customer='$rcust'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // output data of each row 
                                echo "<table id='itemlist'>
                                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Sales Description</th><th>Sales Date</th><th>Payment Returned By</th><th>Return Received By</th><th>Customer</th><th>Quantity Returned</th><th>Amount Returned</th><th>Return Date</th></tr>";
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["salesDesc"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["collectedBy"] . "</td><td>" . $row["returnedTo"] . "</td><td>" . $row["Customer"] . "</td><td>" . $row["quantityReturned"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["returnDate"] . "</td></tr>";
                                } echo "</table>";
                            } else {
                                echo "0 results";
                            }
                        } else {

                        $sql = "SELECT id, serialCode, salesDesc, salesDate, collectedBy, returnedTo, Customer, quantityReturned, amountReturned, returnDate, returnNote FROM returneditemsc";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo "<table id='itemlist'>
                            <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Sales Description</th><th>Sales Date</th><th>Payment Returned By</th><th>Return Received By</th><th>Customer</th><th>Quantity Returned</th><th>Amount Returned</th><th>Return Date</th></tr>";
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["salesDesc"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["collectedBy"] . "</td><td>" . $row["returnedTo"] . "</td><td>" . $row["Customer"] . "</td><td>" . $row["quantityReturned"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["returnDate"] . "</td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "0 results";
                        }
                    }
                        ?>
                    </div><br/>
                    </div>
                    <div class="col-md-6 col-sm-6 text-center">
                    <div id="topseebar"><span id="sumSupRets"><?php echo $noResultt; ?></span> <span>Items Returned To Supplier</span> <span class="glyphicon glyphicon-log-out"></span></div> 
                    <div class="tablecontainer" title="Click Checkbox To Select"> 
                        <?php
                        $checkboxform = "<form action='returnedItems.php' method='POST' name='form10'>";
                        $checkbox = "<input type='submit' value='' name='box'> <br/>";
                        $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                        $hiddenboxend = "'>";

                        if (isset($_POST['searchitembtn2'])) {
                            $rItm = $_POST['returnitems'];
                            $rsupl = $_POST['returnsupplier'];
                            $sdate = $_POST['startdate'];
                            $edate = $_POST['enddate'];

                            $sql = "SELECT id, serialCode, purchaseDesc, purchaseDate, authorizedBy, returnedBy, Supplier, quantityReturned, amountReturned, returnDate, returnNote FROM returneditemss WHERE purchaseDesc='$rItm' OR (returnDate='$sdate' OR returnDate='$edate' OR (returnDate<'$edate' AND returnDate>'$sdate')) OR Supplier='$rsupl'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // output data of each row 
                                echo "<table id='itemlist'>
                                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Purchase Description</th><th>Purchase Date</th><th>Return Authorized By</th><th>Return Made By</th><th>Dealer / Supplier</th><th>Quantity Returned</th><th>Amount Returned</th><th>Return Date</th></tr>";
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["purchaseDesc"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["authorizedBy"] . "</td><td>" . $row["returnedBy"] . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["quantityReturned"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["returnDate"] . "</td></tr>";
                                } echo "</table>";
                            } else {
                                echo "0 results";
                            }
                        } else {

                        $sql = "SELECT id, serialCode, purchaseDesc, purchaseDate, authorizedBy, returnedBy, Supplier, quantityReturned, amountReturned, returnDate, returnNote FROM returneditemss";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo "<table id='itemlist'>
                            <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Purchase Description</th><th>Purchase Date</th><th>Return Authorized By</th><th>Return Made By</th><th>Dealer / Supplier</th><th>Quantity Returned</th><th>Amount Returned</th><th>Return Date</th></tr>";
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["purchaseDesc"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["authorizedBy"] . "</td><td>" . $row["returnedBy"] . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["quantityReturned"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["returnDate"] . "</td></tr>";
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
            </div> 
        </div>

        <div class="clearfix"></div>
        <!-- The Modal -->
        <div id="myModal" class="my-modal">
            <div class="my-modal-content my-animate-top my-card-4" style="height: 200px;">
            <div class="my-container">
                <span class="my-closebtn"onclick="document.getElementById('myModal').style.display='none';">&times;</span>
                <form action="returnedItems.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate> 
                    <div class="row text-center">
                    <h5>Are you sure you want to</h5>
                    <hr class="line-black"/>
                        <div  class="col-md-6 col-sm-6">
                            <h3> Clear Items Returned By Customers? </h3> 
                            <button class="btn btn-danger" name="clearcustreturns" style="vertical-align:middle">YES <span class="glyphicon glyphicon-floppy-remove"></span></button>
                        </div>
                        <div  class="col-md-6 col-sm-6">
                            <h3> Clear Items Returned By Suppliers? </h3> 
                            <button class="btn btn-danger" name="clearsuppreturns" style="vertical-align:middle">YES <span class="glyphicon glyphicon-floppy-remove"></span></button> 
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>

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

              var totalcountt = document.getElementById('totalcountt').innerHTML;

              document.getElementById("sumSupRets").innerHTML = totalcountt;

              var totalcounted = document.getElementById('totalcounted').innerHTML;

              document.getElementById("sumCusRets").innerHTML = totalcounted;

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