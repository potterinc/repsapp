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
        <link rel="stylesheet" type="text/css" href="css/customer.css">
        <link rel="stylesheet" href="css/bootstrap.min.css"> 
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Customers' History</title> 

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
            header('Location: dashboard.php');
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
            header('Location: logout.php');
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
        $enterCustomer = "";
        if (isset($_POST['addcustomerbtn'])) {
            $dtcna = $_POST['customername'];
            $dtcnu = $_POST['customernumber'];
            $dtcad = $_POST['customeraddress'];
            $dtdat = date("Y-m-d");

            $sql = "INSERT INTO customers(Name, contactAddress, phoneNumber, additionDate)VALUES ('$dtcna', '$dtcad', '$dtcnu', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $enterCustomer = 'New Customer added successfully. ';
            } else {
                $enterCustomer = 'Error in Customer entry. ' . $conn->error;
            }

            $actv = ' Added New Customer ' . $dtcna . ' to list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $updAct = 'User activity updated.';
            } else {
                $updAct = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=customerHistory.php');
            echo $next;
        }

        $Name = "";
        $createResults = "";
        $checkbox = "";
        if (isset($_POST['box'])) {
            $hiddenid = $_POST['hiddenid'];

            $sql = "SELECT Name FROM customers WHERE id= $hiddenid";
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
        $removeCustomer = "";
        if (isset($_POST['removecustomerbtn'])) {
            $cusNames = $_POST["cusNames"];
            $dtdat = date("Y-m-d");
            // sql to delete a record
            $sql = "DELETE FROM customers WHERE Name='$cusNames'";

            if ($conn->query($sql) === TRUE) {
                $removeCustomer = 'Customer Account removed . ';
            } else {
                $removeCustomer = 'Error removing Customer Account: ' . $conn->error;
            }

            $actv = ' Removed Customer ' . $cusNames . ' from list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = 'User activity updated.';
            } else {
                $updAct1 = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=customerHistory.php');
            echo $next;
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= customerHistory.php');
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

        <div class="container">
            <header class="text-center"><h1><span class="label label-primary">Customers' Purchase &amp; Return History&nbsp;&nbsp;<span class="glyphicon glyphicon-time"></span></span></h1></header>
        </div><hr class="line-blue">

        <div class="container-fluid"> 
                <div class="row">
                        <h4 class="my-center">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>
                    <div  class="col-md-8 col-sm-8 text-center">
                        <form action="customerHistory.php" method="POST" target="_self">
                            <div  class="col-xs-3 col-md-3">
                                <small>Purchase Date From: <span class="glyphicon glyphicon-calendar"></span></small>
                                <input type="date" class="form-control" name="startdate">
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <small>To <span class="glyphicon glyphicon-calendar"></span></small>
                                <input type="date" class="form-control" name="enddate">
                            </div>
                            <div class="col-xs-3 col-md-3">
                            <small>Customer's Name: <span class="glyphicon glyphicon-user"></span></small> 
<?php
$cusoptionstart = '<option value="';
$cusoptioncont = '">';
$cusoptionend = 'customer </option>';

$sql = "SELECT DISTINCT Name FROM customers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<input list="customer" class="form-control" name="customers" placeholder="Search">
                  <datalist id="customer">';
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo $cusoptionstart . $row["Name"] . $cusoptioncont . $cusoptionend;
    }
    echo '</datalist>';
} else {
    echo '0 results';
}
?> 
                        </div>
                        <div class="col-xs-3 col-sm-3">
                            <span onclick="document.getElementById('addCustomer').style.display='block'" style="cursor: pointer;">Add New Customer <span class="glyphicon glyphicon-send"></span></span>
                        </div>
                            <div class="clearfix"></div><hr class="line-black">
                        <div class="text-center btn-group">
                            <button class="btn btn-success" name="searchitembtn"><span class="glyphicon glyphicon-search"></span>&nbsp;SEARCH</button>
                            <button class="btn btn-danger" name="removecustomerbtn"><span class="glyphicon glyphicon-remove"></span>&nbsp;REMOVE CUSTOMER
                            <input class="pull-left" type='hidden' name='cusNames' value= '<?php echo $Name; ?>' ></button>
                            <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                        </div>
                        </div>
                        <div class="col-sm-4 col-md-4" style="border: 2px solid black; border-radius: 20px; height:140px; overflow-y: auto;">
                            <?php
                                echo "<i class='custrans pull-right'>Purchases & Returns <span class='glyphicon glyphicon-chevron-down'></span></i><br/>";
                                echo "<span style='width:49%; float:left; border-right:1px dotted black;'>";

                                $sql = "SELECT quantitySold, Category, Brand, serialCode, totalItemPrice, salesDate FROM solditems WHERE Customer='$Name'";
                                $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table>";
                                // output data of each row

                                while ($row = $result->fetch_assoc()) {
                                $itemSalePrice = $row["totalItemPrice"] / $row["quantitySold"];
                                    echo "<tr><td><i style='color:red;'>Purchased</i>" . " " . $row["quantitySold"] . " " . $row["Brand"] . " " . $row["Category"] . " " . $row["serialCode"] . " " . "at" . " " . $itemSalePrice . " / piece on" . " " . $row["salesDate"] . "<hr/></td></tr>";
                                } echo "</table>";
                            }
                            echo "</span><span style='width:49%; float:left; margin-left:1%;'>";

                            $sql = "SELECT quantityReturned, salesDesc, amountReturned, returnDate FROM returneditemsc WHERE Customer='$Name'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                            echo "<table>";
                            // output data of each row

                            while ($row = $result->fetch_assoc()) {
                                
                                $retPrice = $row["amountReturned"] / $row["quantityReturned"];
                                echo "<tr><td><i style='color:red;'>Returned</i>" . " " . $row["quantityReturned"] . " " . $row["salesDesc"] . " " . "at" . " " . $retPrice . "/piece on" . " " . $row["returnDate"] . " and received #" . $row["amountReturned"] . " " . "as balance" . ".<hr/></td></tr>";
                                } echo "</table>";
                            }
                            echo "</span>";
                            ?>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    <hr class="line-blue">
    <div class="container-fluid">
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        <div class="tablecontainer" data-toggle="tooltip" title="Click Checkbox To Select">
                            <?php
                            $checkboxform = "<form action='customerHistory.php' method='POST' name='form7'>";
                            $checkbox = "<input type='submit' value='' name='box'> <br/>";
                            $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                            $hiddenboxend = "'>";

                            if (isset($_POST['searchitembtn'])) {
                                $credc = $_POST['customers'];
                                $sdate = $_POST['startdate'];
                                $edate = $_POST['enddate'];

                                $sql = "SELECT id, Name, contactAddress, phoneNumber, additionDate, allPurchase, allReturns, sucPurchase, cashPurchase, tranPurchase, creditRem, amountReturned, lastDate, lastReturnD FROM customers WHERE Name='$credc' OR (lastDate='$sdate' OR lastDate='$edate' OR (lastDate<'$edate' AND lastDate>'$sdate'))";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // output data of each row 
                                    echo "<table id='itemlist'>
                                            <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Name</th><th>Address</th><th>Phone Number</th><th>Date Of Addition</th><th>Item Purchased</th><th>Item Returned</th><th>Successful Purchases</th><th>All Purchases</th><th>All Transfers</th><th>All Credit Remaining</th><th>Amount Returned</th><th>Last Purchase Date</th><th>Last Return Date</th></tr>";
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Name"] . "</td><td>" . $row["contactAddress"] . "</td><td>" . $row["phoneNumber"] . "</td><td>" . $row["additionDate"] . "</td><td>" . $row["allPurchase"] . "</td><td>" . $row["allReturns"] . "</td><td>" . $row["sucPurchase"] . "</td><td>" . $row["cashPurchase"] . "</td><td>" . $row["tranPurchase"] . "</td><td>" . $row["creditRem"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["lastDate"] . "</td><td>" . $row["lastReturnD"] . "</td></tr>";
                                    }
                                    echo "</table>";
                                } else {
                                    echo "0 results";
                                }
                            } else {

                            $sql = "SELECT id, Name, contactAddress, phoneNumber, additionDate, allPurchase, allReturns, sucPurchase, cashPurchase, tranPurchase, creditRem, amountReturned, lastDate, lastReturnD FROM customers";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table id='itemlist'>
                                            <tr><th><span class='glyphicon glyphicon-check'></span></th><th>Name</th><th>Contact Address</th><th>Phone Number</th><th>Date Of Addition</th><th>Stock Purchased</th><th>Stock Returned</th><th>Successful Purchases</th><th>All Cash Purchase</th><th>All Transfers</th><th>All Credit Remaining</th><th>Total Amount Returned</th><th>Last Purchase Date</th><th>Last Return Date</th></tr>";
                                // output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . $checkboxform . $checkbox . $hiddenboxstart . $row["id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["Name"] . "</td><td>" . $row["contactAddress"] . "</td><td>" . $row["phoneNumber"] . "</td><td>" . $row["additionDate"] . "</td><td>" . $row["allPurchase"] . "</td><td>" . $row["allReturns"] . "</td><td>" . $row["sucPurchase"] . "</td><td>" . $row["cashPurchase"] . "</td><td>" . $row["tranPurchase"] . "</td><td>" . $row["creditRem"] . "</td><td>" . $row["amountReturned"] . "</td><td>" . $row["lastDate"] . "</td><td>" . $row["lastReturnD"] . "</td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "0 results";
                            }
                        }
                            ?>
                        </div><br/>
                        <div class="col-sm-4 col-md-4">
                            <a href="sellItem.php">Sales <span class="glyphicon glyphicon-eye-open"></span></a>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <a href="payin.php">View Income <span class="glyphicon glyphicon-eye-open"></span></a>
                        </div>
                        <div class="col-sm-4 col-md-4">
                            <a href="salesHistory.php">View Sales History <span class="glyphicon glyphicon-eye-open"></span></a>
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

        <!-- The New Customer Modal -->
        <div id="addCustomer" class="my-modal">
            <div class="my-modal-content my-card-4 my-animate-top">
                <div class="my-container">
                    <span class="my-closebtn" onclick="document.getElementById('addCustomer').style.display='none';">&times;</span>
                    <h2 class="my-center"><span class="label label-danger">New Customer<span class="glyphicon-user glyphicon"></span></span></h2><hr class="line-red">        
                    <form action="customerHistory.php" method="POST" target="_self">
                    <div class="col-sm-6 col-md-6">
                        <small>Customer Name:</small>
                        <input type="text" name="customername" class="form-control" required>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <small>Phone Number:</small>
                        <input type="text" class="form-control" name="customernumber" required>
                    </div>
                    <div class="form-group col-md-12 col-sm-12">
                    <small>Customer's Address:</small>
                        <textarea name="customeraddress" class="form-control" id="caddr" placeholder="Contact Address" required></textarea>
                    </div><div class="clearfix"></div>
                    <br/><hr class="line-black"/>
                    <div class="my-center">
                        <button class="btn btn-success" name="addcustomerbtn">REGISTER&nbsp;<span class="glyphicon glyphicon-check"></span></button>
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
                          document.getElementById("caddr").innerHTML = "";
                      }

            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
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