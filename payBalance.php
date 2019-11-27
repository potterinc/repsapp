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
        <link rel="stylesheet" type="text/css" href="css/balance.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Book Balance</title>

        <?php
        $Note = "Transaction Note: ...";

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

        $hiddentexts = "";
        $incN = "";
        $createResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";

            $sql = "SELECT id, staffNote FROM income WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $hiddentexts = $storevalue["id"];
                $incN = $storevalue["staffNote"];
                $checkbox = 'Option Selected';
            } else {
                $createResults = '0 results. ';
            }
        }

        $hiddentext = "";
        $expN = "";
        $createResult = "";
        if (isset($_POST['boxes'])) {
            $hiddenid = $_POST['hiddenid'];
            $Note = "";

            $sql = "SELECT id, staffNote FROM expenses WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $hiddentext = $storevalue["id"];
                $expN = $storevalue["staffNote"];
            } else {
                $createResult = '0 results. ';
            }
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= payBalance.php');
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
            <header class="text-center"><h1><span class="label label-primary">BOOK BALANCE&nbsp;&nbsp;<span class="glyphicon glyphicon-book"></span></span></h1></header>
        </div><hr class="line-blue">

        <div class="container-fluid">
            <form action="payBalance.php" method="POST" target="_self" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate> 
                <div class="row">
                    
                        <h4 class="my-center">Today is <span id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></span>
                        </h4>
                </div><br/>
                <div class="row">
                    <div  class="col-md-9 col-sm-9 text-center">
                    <div class="col-sm-6 col-md-6">
                        <span class="text-center">Search By Customer: <span class="glyphicon glyphicon-user"></span> 
                            <?php
                            $cusoptionstart = '<option value="';
                            $cusoptioncont = '">';
                            $cusoptionend = 'customer </option>';

                            $sql = "SELECT DISTINCT Customer FROM income";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<input list="customer" name="customer" placeholder="Search for Customers:">
                                              <datalist id="customer">';
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo $cusoptionstart . $row["Customer"] . $cusoptioncont . $cusoptionend;
                                }
                                echo '</datalist>';
                            } else {
                                echo '0 results';
                            }
                            ?></span>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <span class="text-center">Search By Supplier: <span class="glyphicon glyphicon-user"></span> 
                                <?php
                                $supoptionstart = '<option value="';
                                $supoptioncont = '">';
                                $supoptionend = 'supplier </option>';

                                $sql = "SELECT DISTINCT Supplier FROM expenses";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo '<input list="supplier" name="supplier" placeholder="Search for Suppliers:">
                                    <datalist id="supplier">';
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $supoptionstart . $row["Supplier"] . $supoptioncont . $supoptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                                ?> </span>
                            </div>
                            <div class="clearfix"></div><hr class="line-black">
                                <span><button class="btn btn-primary" name="searchitembtn1"><span class="glyphicon glyphicon-search pull-left"></span>&nbsp;SEARCH</button></span> 
                                <span class="space">Search By Last Payment Date: From <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="startdate" required></span> 
                                <span> To <span class="glyphicon glyphicon-calendar"></span> <input type="date" name="enddate"></span> 
                                <span><button class="btn btn-primary" name="searchitembtn2"><span class="glyphicon glyphicon-search"></span>&nbsp;SEARCH</button></span> 
                                </div>
                                <div  class="col-md-3 col-sm-3">
                                    <textarea name="transNote" class="form-control" placeholder="Transaction Note:" required><?php echo $incN . $expN; ?></textarea>
                                </div>
                                </div> 
                                </form> 
                                </div><br/>

                                <div class="container-fluid">
                                    <div class="row">
                                        <div  class="col-md-12 col-sm-12 text-center">
                                            <div class="tablecontainer" title="Click Checkbox To Select">
                                                <div class="row">
                                                    <div  class="col-md-6 col-sm-6 borderedright"> 
<?php
$checkboxform = "<form action='payBalance.php' method='POST' name='form13'>";
$checkbox = "<input type='submit' value='' name='box'> <br/>";
$hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
$hiddenboxend = "'>";

if (isset($_POST['searchitembtn1'])) {
    $credc = $_POST['customer'];
    $sdate = $_POST['startdate'];
    $edate = $_POST['enddate'];

    $sql = "SELECT id, salesType, cashPaid, transferPaid, creditRemaining, Customer, salesDate, lastDate FROM income WHERE Customer='$credc' OR (lastDate='$sdate' OR lastDate='$edate' OR (lastDate<'$edate' AND lastDate>'$sdate'))";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row 
        echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Income</th><th>Credit</th><th>Received From</th><th>Transaction Type</th><th>Transaction Date</th><th>Last Payment Date</th></tr>";
        // output data of each row

        while ($row = $result->fetch_assoc()) {
            $totalPay = $row["cashPaid"] + $row["transferPaid"];
            echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $totalPay . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["Customer"] . "</td><td>" . $row["salesType"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
        } echo "</table>";
    } else {
        echo "0 results";
    }
} else {

$sql = "SELECT id, salesType, cashPaid, transferPaid, creditRemaining, Customer, salesDate, lastDate FROM income";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Income</th><th>Credit</th><th>Received From</th><th>Transaction Type</th><th>Transaction Date</th><th>Last Payment Date</th></tr>";
    // output data of each row 

    $count = 0;

    while ($row = $result->fetch_assoc()) {
        $count += 1;
        $totalPay = $row["cashPaid"] + $row["transferPaid"];
        echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td id='totalIncome$count'>" . $totalPay . "</td><td id='totalCredit$count'>" . $row["creditRemaining"] . "</td><td>" . $row["Customer"] . "</td><td>" . $row["salesType"] . "</td><td>" . $row["salesDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>   

                  ";
    }
    echo "</table>";

    echo "<p hidden id='totalcount'>$count</p>";
} else {
    echo "0 results";
}
}
?> 
                                                    </div>  
                                                    <div  class="col-md-6 col-sm-6 borderedleft"> 
<?php
$checkboxform = "<form action='payBalance.php' method='POST' name='form13'>";
$checkbox = "<input type='submit' value='' name='boxes'> <br/>";
$hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
$hiddenboxend = "'>";

if (isset($_POST['searchitembtn2'])) {
    $creds = $_POST['supplier'];
    $sdate = $_POST['startdate'];
    $edate = $_POST['enddate'];

    $sql = "SELECT id, purchaseType, cashPaid, transferPaid, creditRemaining, Supplier, purchaseDate, lastDate FROM expenses WHERE Supplier='$creds' OR (lastDate='$sdate' OR lastDate='$edate' OR (lastDate<'$edate' AND lastDate>'$sdate'))";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row 
        echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Expenses</th><th>Debit</th><th>Paid To</th><th>Transaction Type</th><th>Transaction Date</th><th>Last Payment Date</th></tr>";
        // output data of each row

        while ($row = $result->fetch_assoc()) {
            $totalPay = $row["cashPaid"] + $row["transferPaid"];
            echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $totalPay . "</td><td>" . $row["creditRemaining"] . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["purchaseType"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
        } echo "</table>";
    } else {
        echo "0 results";
    }
} else {

$sql = "SELECT id, purchaseType, cashPaid, transferPaid, creditRemaining, Supplier, purchaseDate, lastDate FROM expenses";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Expenses</th><th>Debit</th><th>Paid To</th><th>Transaction Type</th><th>Transaction Date</th><th>Last Payment Date</th></tr>";
    // output data of each row 

    $count = 0;

    while ($row = $result->fetch_assoc()) {
        $count += 1;
        $totalPay = $row["cashPaid"] + $row["transferPaid"];
        echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td id='totalExpense$count'>" . $totalPay . "</td><td id='totalDebit$count'>" . $row["creditRemaining"] . "</td><td>" . $row["Supplier"] . "</td><td>" . $row["purchaseType"] . "</td><td>" . $row["purchaseDate"] . "</td><td>" . $row["lastDate"] . "</td></tr>";
    }
    echo "</table>";

    echo "<p hidden id='totalcounts'>$count</p>";
} else {
    echo "0 results";
}
}
?> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div  class="col-md-12 col-sm-12 text-center">
                                                    <div class="tablecontainer tablecontainer2">
                                                        <span class="pull-left">Total Credit By Customers (#)</span> 
                                                        <span class="pull-right">Total Debit To Suppliers (#)</span> 
                                                        <span>Gross Income (#)</span> 
                                                        <span class="profitspace">Gross Profit (#)</span> 
                                                        <span>Gross Expenses (#)</span>  
                                                        <br/> 
                                                        <input id="resultSumCredit" type="text" class="pull-left">
                                                        <input id="resultSumDebit" type="text" class="pull-right">
                                                        <input id="resultSumIncome" type="text" class="space">
                                                        <input id="resultSumProfit" type="text" class="space">
                                                        <input id="resultSumExpense" type="text" class="space">
                                                    </div>
                                                </div>
                                            </div><hr class="line-black">
                                            <div class="container-fluid">
                                            <div class="row">
                                        <form method="POST" action="<?php $_PHP_SELF ?>">
                                            <div class="col-md-2">
                                                <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                                            </div>
                                        </form>
                                            <div class="col-md-2">
                                                <a href="payin.php">See Income <span class="glyphicon glyphicon-eye-open"></span></a>
                                                    <input type='hidden' name='hiddenboxes' value= '<?php echo $hiddentexts . $hiddentext; ?>' >
                                            </div>
                                            <div class="col-md-2">
                                                <a href="credits.php">See Credits <span class="glyphicon glyphicon-eye-open"></span></a>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="payout.php">See Expenses <span class="glyphicon glyphicon-backward"></span></a>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="report.php">Report <span class="glyphicon glyphicon-step-forward"></span></a>
                                            </div>
                                            </div>
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

                                      var totalcount = document.getElementById('totalcount').innerHTML;
                                      var allIncome = 0;
                                      var indIncome;
                                      var allCredit = 0;
                                      var indCredit;

                                      for (k = 1; k <= totalcount; k++) {
                                          indIncome = document.getElementById('totalIncome' + k).innerHTML;

                                          indCredit = document.getElementById('totalCredit' + k).innerHTML;
                                          //alert(indAmount); 
                                          indIncome = parseInt(indIncome);
                                          indCredit = parseInt(indCredit);
                                          allIncome += indIncome;
                                          allCredit += indCredit;

                                          if (k == totalcount) {
                                              document.getElementById("resultSumIncome").value = allIncome;

                                              document.getElementById("resultSumCredit").value = allCredit;
                                              //alert(totalAmount); 
                                          }
                                      }

                                      var totalcounts = document.getElementById('totalcounts').innerHTML;
                                      var allExpense = 0;
                                      var indExpense;
                                      var allDebit = 0;
                                      var indDebit;

                                      for (e = 1; e <= totalcounts; e++) {
                                          indExpense = document.getElementById('totalExpense' + e).innerHTML;

                                          indDebit = document.getElementById('totalDebit' + e).innerHTML;
                                          //alert(indAmount); 
                                          indExpense = parseInt(indExpense);
                                          indDebit = parseInt(indDebit);
                                          allExpense += indExpense;
                                          allDebit += indDebit;

                                          if (e == totalcounts) {
                                              document.getElementById("resultSumDebit").value = allDebit;

                                              document.getElementById("resultSumExpense").value = allExpense;
                                              //alert(totalAmount); 
                                          }
                                      }

                                      var Incom = document.getElementById("resultSumIncome").value;
                                      var Expens = document.getElementById("resultSumExpense").value;
                                      Incom = parseInt(Incom);
                                      Expens = parseInt(Expens);
                                      gProfit = Incom - Expens;

                                      document.getElementById("resultSumProfit").value = gProfit;

                                </script> 

                                <script>
                                    var accR = document.getElementById("accessR").innerHTML;
                                    if (accR == "Supervisor" || accR == "Sales Person") {
                                        document.getElementById("mnguser").style.display = "none";
                                        document.getElementById("report").style.display = "none";
                                        document.getElementById("reportlink").style.display = "none";
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