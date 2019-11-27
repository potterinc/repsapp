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
        <link rel="stylesheet" type="text/css" href="css/order.css">
        <script src="js/myscript.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Order List</title>

        <?php
        $fig = "";

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

        $hiddenid = "";
        $oldserialCode = "";
        $oldCategory = "";
        $oldBrand = "";
        $oldModel = "";
        $oldSpecs = "";
        $oldQuantity = "";
        $oldcostPrice = "";
        $addorder = "";
        if (isset($_POST['addorderbtn'])) {
            $hiddenid = $_POST["hiddenbox4"];

            $sql = "SELECT serialCode, Category, Brand, Model, Specs, Quantity, costPrice FROM allitems WHERE id= $hiddenid";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldserialCode = $storevalue["serialCode"];
                $oldCategory = $storevalue["Category"];
                $oldBrand = $storevalue["Brand"];
                $oldModel = $storevalue["Model"];
                $oldSpecs = $storevalue["Specs"];
                $oldQuantity = $storevalue["Quantity"];
                $oldcostPrice = $storevalue["costPrice"];
            } else {
                $addorder = '0 results. ';
            }
        }

        $updAct2 = "";
        $oldqDm = "";
        $copyRecord = "";
        $enterOrder = "";
        if (isset($_POST['addtoorderbtn'])) {
            $snCode = $_POST['snCode'];
            $qtynow = $_POST['quantity'];
            $cprnow = $_POST['costprice'];
            $lDate = date("Y-m-d");

            $sql = "SELECT serialCode, quantityDemand FROM ordereditems WHERE serialCode='$snCode'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldqDm = $storevalue["quantityDemand"];
                $newqDm = $oldqDm + $qtynow;

                $sql = "UPDATE ordereditems SET quantityDemand='$newqDm', costPrice='$cprnow' WHERE serialCode='$snCode'";

                if ($conn->query($sql) === TRUE) {
                    $enterOrder = ' Order updated successfully.';
                } else {
                    $enterOrder = ' Error updating Order. ' . $conn->error;
                }

                $actv = ' Updated Item ' . $snCode . ' in order list to' . $newqDm . '.';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lDate')";

                if ($conn->query($sql) === TRUE) {
                    $updAct2 = 'User activity updated.';
                } else {
                    $updAct2 = 'Error updating user activity. ' . $conn->error;
                }
            } else {
                $sql = "INSERT INTO ordereditems (serialCode, Category, Brand, Model, Specs) SELECT serialCode, Category, Brand, Model, Specs FROM allitems WHERE serialCode='$snCode'";

                if ($conn->query($sql) === TRUE) {
                    $copyRecord = 'Added ';
                } else {
                    $copyRecord = 'Error in Item entry. ' . $conn->error;
                }

                $sql = "UPDATE ordereditems SET quantityDemand='$qtynow', costPrice='$cprnow' WHERE serialCode='$snCode'";

                if ($conn->query($sql) === TRUE) {
                    $enterOrder = 'successfully.';
                } else {
                    $enterOrder = ' Error in entry completion. ' . $conn->error;
                }

                $actv = ' Added ' . $qtynow . ' of Item ' . $snCode . ' to order list';
                $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lDate')";

                if ($conn->query($sql) === TRUE) {
                    $updAct2 = 'User activity updated.';
                } else {
                    $updAct2 = 'Error updating user activity. ' . $conn->error;
                }
            }
            $next = header('Refresh:1; URL=orderList.php');
            echo $next;
        }

        $updAct1 = "";
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
                $updAct1 = 'User activity updated.';
            } else {
                $updAct1 = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=orderList.php');
            echo $next;
        }

        $hiddentext = "";
        $serialCodes = "";
        $quantityReceived = "";
        $purchasePrice = "";
        $sellingPrice = "";
        $lowQuantity = "";
        $totalPrice = "";
        $createResults = "";
        $checkbox = "";
        $updateOrder = "";
        if (isset($_POST['savepricebtn'])) {
            $hiddenbox6 = $_POST['hiddenbox6'];
            $qtyreceived = $_POST['qtyreceived'];
            $purchaseprice = $_POST['purchaseprice'];
            $sellingprice = $_POST['sellingprice'];
            $lowqty = $_POST['lowqty'];
            $totalPrice = $purchaseprice * $qtyreceived;

            $sql = "UPDATE ordereditems SET quantityReceived='$qtyreceived', purchasePrice='$purchaseprice', totalPrice='$totalPrice', sellingPrice='$sellingprice', lowQuantity='$lowqty' WHERE id=$hiddenbox6";
            // sql to update a record
            if ($conn->query($sql) === TRUE) {
                $updateOrder = 'Received Order updated successfully. ';
            } else {
                $updateOrder = 'Error updating Received Order: ' . $conn->error;
            }
        }

        $updAct3 = "";
        $rmvOrder = "";
        if (isset($_POST['removeorderbtn'])) {
            $id = $_POST["remove"];
            $lDate = date("Y-m-d");
            // sql to delete a record
            $sql = "DELETE FROM ordereditems WHERE Id=$id";

            if ($conn->query($sql) === TRUE) {
                $removeOrder = 'Order deleted successfully. ';
            } else {
                $removeOrder = 'Error deleting order: ' . $conn->error;
            }

            $actv = ' Removed Item ' . $id . ' from order list';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lDate')";

            if ($conn->query($sql) === TRUE) {
                $updAct3 = 'User activity updated.';
            } else {
                $updAct3 = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=orderList.php');
            echo $next;
        }

        $updAct = "";
        $updateStock = "";
        $compbuy = "";
        $payment = "";
        $updateSupplier = "";
        $sucOrder = "";
        $oldQtty = "";
        $qRm = "";
        $tPr = "";
        $sPr = "";
        $pPr = "";
        $qRd = "";
        $qDd = "";
        $Spc = "";
        $Mdl = "";
        $Brnd = "";
        $Catg = "";
        $serialC = "";
        $newqty = "";
        $oldAll = "";
        $oldSuc = "";
        $oldCash = "";
        $oldTran = "";
        $oldCred = "";
        $OrderId;
        if (isset($_POST['completepurchasebtn'])) {
            $serialCoded = $_POST["serialNo"];
            $OrderId = $_POST["placeOrder"];
            $lowqty = $_POST["lowqty"];
            $qtyR = $_POST['qtyreceived'];
            $purP = $_POST['purchaseprice'];
            $selP = $_POST['sellingprice'];
            $purD = date("Y-m-d");
            $qtypre = 0;
            $pType = "Stock";
            $pDate = date("Y-m-d");
            $aBy = $_POST['userauthorized'];
            $pBy = $_POST['userpaid'];
            $Sup = $_POST['suppliers'];
            $cashP = $_POST['cashpaid'];
            $transP = $_POST['transferpaid'];
            $totPd = $_POST['totalpaid'];
            $credR = $_POST['credit'];
            $lDate = date("Y-m-d");
            $lastD = date("Y-m-d");
            $sNote = $_POST['staffnote'];
            $totP = $purP * $qtyR;
            $newqRd = 0;

            $sql = "SELECT Quantity FROM allitems WHERE serialCode='$serialCoded'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldQtty = $storevalue["Quantity"];
            }
            $newqty = $oldQtty + $qtyR;

            $sql = "UPDATE allitems SET Quantity='$newqty', lowQuantity='$lowqty', costPrice='$purP', sellingPrice='$selP' WHERE serialCode='$serialCoded'";
            // sql to update a record
            if ($conn->query($sql) === TRUE) {
                $updateStock = 'Stock updated successfully. ';
            } else {
                $updateStock = 'Error updating Stock: ' . $conn->error;
            }

            $sql = "SELECT serialCode, Category, Brand, Model, Specs, quantityDemand, quantityReceived, purchasePrice, totalPrice, sellingPrice FROM ordereditems WHERE Id='$OrderId'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $serialC = $storevalue["serialCode"];
                $Catg = $storevalue["Category"];
                $Brnd = $storevalue["Brand"];
                $Mdl = $storevalue["Model"];
                $Spc = $storevalue["Specs"];
                $qDd = $storevalue["quantityDemand"];
                $qRd = $storevalue["quantityReceived"];
                $pPr = $storevalue["purchasePrice"];
                $tPr = $storevalue["totalPrice"];
                $sPr = $storevalue["sellingPrice"];
            }
            $pDesc = $serialC . " " . $Brnd . " " . $Mdl . " " . $Spc . " " . $Catg;
            $qRm = $qDd - $qtyR;
            $newtPr = $purP * $newqRd;

            if ($qtyR >= $qDd) {
                // sql to delete a record
                $sql = "DELETE FROM ordereditems WHERE Id='$OrderId'";

                if ($conn->query($sql) === TRUE) {
                    $sucOrder = 'Order completed & removed. ';
                } else {
                    $sucOrder = 'Error completing & removing order: ' . $conn->error;
                }
            } else {
                $sql = "UPDATE ordereditems SET quantityDemand='$qRm', quantityReceived='$newqRd', costPrice='$purP', purchasePrice='$purP', totalPrice='$newtPr', sellingPrice='$selP', lowQuantity='$lowqty' WHERE Id='$OrderId'";

                if ($conn->query($sql) === TRUE) {
                    $sucOrder = 'Order completed & removed. ';
                } else {
                    $sucOrder = 'Error completing & removing order: ' . $conn->error;
                }
            }

            $sql = "INSERT INTO receiveditems (serialCode, Category, Brand, Model, Specs, quantityReceived, purchasePrice, totalPrice, amountPaid, creditOwed, sellingPrice, purchaseDate, Supplier, staffNote) VALUES ('$serialC', '$Catg', '$Brnd', '$Mdl', '$Spc', '$qtyR', '$purP', '$totP', '$totPd', '$credR', '$selP', '$purD', '$Sup', '$sNote')";

            if ($conn->query($sql) === TRUE) {
                $compbuy = 'Item Received successfully.';
            } else {
                $compbuy = 'Error in entry completion. ' . $conn->error;
            }

            $sql = "INSERT INTO expenses (purchaseType, purchaseDesc, purchaseDate, authorizedBy, purchaseBy, Supplier, cashPaid, transferPaid, creditRemaining, lastDate, staffNote, serialCode) VALUES ('$pType', '$pDesc', '$pDate', '$aBy', '$pBy', '$Sup', '$cashP', '$transP', '$credR', '$lDate', '$sNote', '$serialC')";

            if ($conn->query($sql) === TRUE) {
                $payment = ' Payment Recorded.';
            } else {
                $payment = 'Error in recording Payment. ' . $conn->error;
            }

            $sql = "SELECT allSupplies, sucSupplies, cashSupplies, tranSupplies, creditRem FROM suppliers WHERE Name='$Sup'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $storevalue = $result->fetch_assoc();
                $oldAll = $storevalue["allSupplies"];
                $oldSuc = $storevalue["sucSupplies"];
                $oldCash = $storevalue["cashSupplies"];
                $oldTran = $storevalue["tranSupplies"];
                $oldCred = $storevalue["creditRem"];
            }
            $allS = $oldAll + $qtyR;
            $sucS = $oldSuc + $qtyR;
            $cashS = $oldCash + $cashP;
            $tranS = $oldTran + $transP;
            $credRem = $oldCred + $credR;

            $sql = "UPDATE suppliers SET allSupplies='$allS', sucSupplies='$sucS', cashSupplies='$cashS', tranSupplies='$tranS', creditRem='$credRem', lastDate='$lastD' WHERE Name='$Sup'";
            // sql to update a record
            if ($conn->query($sql) === TRUE) {
                $updateSupplier = ' Supplier Account updated successfully. ';
            } else {
                $updateSupplier = 'Error updating Supplier Account: ' . $conn->error;
            }

            $actv = ' Purchased ' . $qtyR . ' of Item ' . $serialCoded . ' from ' . $Sup . '.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lDate')";

            if ($conn->query($sql) === TRUE) {
                $updAct = 'User activity updated.';
            } else {
                $updAct = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=orderList.php');
            echo $next;
        }
        ?>
    </head>
    <body>
        <div class="container"><br>
            <form class="mg-top-5" method="POST" action="<?php $_PHP_SELF ?>">
                <button class="pull-left btn btn-danger" data-toggle="tooltip" title="Logout" name="logoutbtn">LOGOUT&nbsp;<span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;<?php echo $fstN . " " . $lasN; ?></button>
                <span class="label label-info text-capitalize pull-right"><h5><?php echo $compNam; ?></h5></span>
                <div class="pull-right btn-group">
                    <button class="btn btn-primary" name="dashboardbtn" data-toggle="tooltip" title="Goto Dashboard">PROCEED TO DASHBOARD&nbsp;<span class="glyphicon glyphicon-dashboard"></span></button>
                </div>
            </form>
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

        <i class="alertbar"><?php echo $addorder . $checkbox . $copyRecord . $enterOrder . $enterSupplier . $createResults . $updateOrder . $rmvOrder . $updateStock . $compbuy . $payment . $updateSupplier . $sucOrder . $updAct . $updAct1 . $updAct2 . $updAct3; ?></i> 
        <header class="text-center"><h1><span class="label label-primary">ORDER LIST</span></h1></header>

        <div class="container-fluid">
            <form action="orderList.php" method="POST">
                <div class="row addrow">
                    <div  class="col-md-12 col-sm-12 addcol">
                        <div>
                            <h4>
                                <span class="label label-primary">Select Item(s)</span>
                                <span class="date pull-right">The date is <i id="dateholder" class="badge"><?php $today = date("D, d-M-Y");
        echo "$today"; ?></i></span>
                            </h4>
                        </div> 
                        <?php
                        $itemstart = '<div class="col-xs-4"><input class="form-control" list="items" name="allitems" value= "';
                        $itemend = '" placeholder="Search For Items: .." ><datalist id="items">';
                        $itemoptionstart = '<option value="';
                        $itemoptioncont = '">';
                        $itemoptionend = '</option>';

                        $sql = "SELECT serialCode, Category, Brand, Model, Specs, costPrice FROM allitems";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo $itemstart . $oldBrand . "" . $oldModel . "" . $oldSpecs . "" . $oldCategory . "" . $oldserialCode . $itemend;
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo $itemoptionstart . $row["serialCode"] . " " . $row["Brand"] . " " . $row["Model"] . " " . $row["Specs"] . " " . $row["Category"] . " " . "#" . $row["costPrice"] . $itemoptioncont . $itemoptionend;
                            }
                            echo '</datalist></div>';
                        } else {
                            echo '0 results';
                        }
                        ?>
                        <div class="col-xs-2">
                            <input type="number" class="form-control" name="quantity" placeholder="Enter Quantity: .." value= "<?php echo $oldQuantity; ?>"  required>
                        </div>
                        <div class="col-xs-2">
                            <input type='text' class="form-control" name='snCode' placeholder='Enter S/N Code: ..' value= '<?php echo $oldserialCode; ?>' required>
                        </div>
                        <div class="col-xs-2">
                            <input type="text" class="form-control" name="costprice" placeholder="Enter Cost &#8358;: .." value= "<?php echo $oldcostPrice; ?>" required>
                        </div>
                        <button class="btn btn-success" name="addtoorderbtn">
                            <span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;&nbsp;<span>Add To Cart</span>
                        </button> 
                        </form>
                        <form method="POST" action="orderList.php">
                            <div class="row">
                                <div  class="col-md-12 col-sm-12">
                                    <div class="tablecontainer" data-toggle="tooltip" title="Click Checkbox To Select"> 
                                        <?php
                                        $checkboxform = "<form action='orderList.php' method='POST' name='form3'>";
                                        $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                                        $hiddenboxend = "'>";

                                        $sql = "SELECT Id, serialCode, Category, Brand, Model, Specs, quantityDemand, quantityReceived, costPrice, purchasePrice, totalPrice, sellingPrice, lowQuantity FROM ordereditems";

                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            echo "<table id='itemlist'>
                <tr><th><span class='glyphicon glyphicon-check'></span></th><th>S/N Code</th><th>Category</th><th>Brand</th><th>Model</th><th>Specs</th><th>Quantity Demand</th><th>Quantity Received</th><th>Cost Price</th><th>Actual Purchase Price</th><th>Total Item Price</th><th>Selling Price</th><th>Low Quantity</th></tr>";
                                            // output data of each row

                                            while ($row = $result->fetch_assoc()) {
                                                $selection = $row["Id"];
                                                echo "<tr><td><input id='checkBtn' type='radio' name='box' value='" . $selection . "' onclick='_(this)' />" . "</td><td style='display:none;'" . $hiddenboxstart . $row["Id"] . $hiddenboxend . "</form>" . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["Category"] . "</td><td>" . $row["Brand"] . "</td><td>" . $row["Model"] . "</td><td>" . $row["Specs"] . "</td><td>" . $row["quantityDemand"] . "</td><td>" . $row["quantityReceived"] . "</td><td>" . $row["costPrice"] . "</td><td>" . $row["purchasePrice"] . "</td><td>" . $row["totalPrice"] . "</td><td>" . $row["sellingPrice"] . "</td><td>" . $row["lowQuantity"] . "</td></tr>";
                                            }
                                            echo "</table>";
                                        } else {
                                            echo "0 results";
                                        }
                                        ?> 
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid row">
                                <div class="col-md-2 col-sm-2">
                                    <button class="btn btn-danger" name="removeorderbtn"><span class="glyphicon glyphicon-trash pull-left"></span>&nbsp;&nbsp;REMOVE
                                    </button>
                                </div>
                                <input type="hidden" name="remove" value="" id="delBtn">
                                </form>

                                <div class="col-md-2 col-sm-2">
                                    <button class="btn btn-success" name="orderBtn"><span class="glyphicon glyphicon-send"></span>&nbsp;&nbsp;<span>PROCESS ORDER</span></button>
                                    <input type="hidden" name="itemId" value="" id="itemId">
                                </div>

                                <form method="POST" action="<?php $_PHP_SELF ?>">
                                    <?php
                                    $data = "";
                                    $recievedQty = "";
                                    $recievedId = "";
                                    $sellingP = "";
                                    $purchaseP = "";
                                    $lowQ = "";
                                    $totalP = "";
                                    $serial;

                                    if (isset($_POST["orderBtn"])) {
                                        $id = $_POST['itemId'];
                                        $sql = "SELECT Id, serialCode, quantityReceived, sellingPrice, purchasePrice, lowQuantity FROM ordereditems WHERE Id = $id";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            $data = $result->fetch_assoc();
                                            $recievedQty = $data["quantityReceived"];
                                            $serial = $data["serialCode"];
                                            $recievedId = $data["Id"];
                                            $sellingP = $data["sellingPrice"];
                                            $purchaseP = $data["purchasePrice"];
                                            $lowQ = $data["lowQuantity"];
                                            $totalP = $data["sellingPrice"] * $data["quantityReceived"];
                                        }
                                    }
                                    ?>


                                    <div class="col-xs-2">
                                        <input type="text" class="form-control" name="lowqty" placeholder="Low Qty" value='<?php echo $lowQ; ?>' >
                                    </div>
                                    <div class="col-xs-2">
                                        <input class="form-control" type="text" name="sellingprice" placeholder="Selling Price &#8358;:" value='<?php echo $sellingP; ?>'>
                                    </div>
                                    <div class="col-xs-2">
                                        <input id='purcP' class="form-control" type="text" name="purchaseprice" placeholder="Purchase Price&#8358:" value='<?php echo $purchaseP; ?>' >
                                    </div>
                                    <div class="col-xs-2">
                                        <input onchange="changeTotal();" id='qttyR' class="form-control" type="text" name="qtyreceived" placeholder="Quantity Received:" value='<?php echo $recievedQty; ?>' >
                                    </div>


                            </div><hr class="line-blue">
                            <div>
                                <input id="total" type="hidden" name="total" value="<?php echo $totalP; ?> ">
                                <header id="totalAmount" class="paymentheader text-center">
                                    <h4>
                                        Payment reciept for item(s) worth&nbsp;<span id="totalBadge" class="badge badge-success">
<?php
if ($totalP == "") {
    $totalP = "0";
    echo "&#8358;" . " " . $totalP . "</span>";
} else {
    echo "&#8358;" . " " . $totalP . "</span>";
}
?>
                                    </h4>
                                </header>
                            </div>
                            <div class="row">
                                <div  class="col-md-8 col-sm-8">
                                    <div class="col-sm-3 col-md-3">
                                        <small>Cash Payment&nbsp;&#8358:</small>
                                        <input onchange="debitCash();" id='paidCash' class="form-control" type="text" name="cashpaid" value= '<?php echo 0; ?>' >
                                    </div>
                                    <div class="col-x-m3 col-md-3">
                                        <small>E-Payment&nbsp;&#8358:</small>
                                        <input onchange="debitTran();" id="paidTran" class="form-control" type="text" name="transferpaid" value= '<?php echo 0; ?>' >
                                    </div>
                                    <div  class="col-sm-3 col-md-3">
                                        <small>Total Amount&nbsp;&#8358:</small>
                                        <input id="totalAmountPaid" class="form-control" type="text" name="totalpaid" value= '<?php echo 0; ?>' >
                                    </div>
                                    <div class="col-sm-3 col-md-3">
                                        <small>Total Credit&nbsp;&#8358:</small>
                                        <input id='totalCredit' class="form-control" type="text" name="credit" value="<?php echo $totalP; ?> ">
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <h4><span class="label label-primary">Select Suppliers</span></h4>
<?php
$supoptionstart = '<option value="';
$supoptioncont = '">';
$supoptionend = 'supplier </option>';

$sql = "SELECT Name FROM suppliers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="col-xs-4"><input class="form-control" list="supplier" name="suppliers" placeholder="Search For Suppliers: ..">
                                  <datalist id="supplier">';
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo $supoptionstart . $row["Name"] . $supoptioncont . $supoptionend;
    }
    echo '</datalist></div>';
} else {
    echo '0 results';
}
?>
                                        <div class="col-sm-4 col-md-4">
                                            <div><a href="#" onclick="document.getElementById('addSup').style.display='block';">Add Supplier <span class="glyphicon glyphicon-plus"></span></a></div>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <small><a href="supplierHistory.php">See Suppliers' History <span class="glyphicon glyphicon-eye-open"></span></a></small>
                                        </div>
                                    </div>
                                </div>
                                <div  class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <small>Payment Made By</small>
<?php
$useroptionstart = '<option value="';
$useroptioncont = '">';
$useroptionend = 'user </option>';

$sql = "SELECT DISTINCT accountType, firstName, lastName FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<input list="users" name="userpaid" class="form-control"><datalist id="users">';
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo $useroptionstart . $row["lastName"] . " " . $row["firstName"] . " " . $row["accountType"] . " " . $useroptioncont . $useroptionend;
    }
    echo '</datalist>';
} else {
    echo '0 results';
}
?>
                                    </div>
                                    <div class="form-group">
                                        <small>Payment authorized by:</small>
                                        <?php
                                        $useroptionstart = '<option value="';
                                        $useroptioncont = '">';
                                        $useroptionend = 'user </option>';

                                        $sql = "SELECT DISTINCT accountType, firstName, lastName FROM users";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            echo '<input list="users" name="userauthorized" class="form-control">
                      <datalist id="users">';
                                            // output data of each row
                                            while ($row = $result->fetch_assoc()) {
                                                echo $useroptionstart . $row["lastName"] . " " . $row["firstName"] . " " . $row["accountType"] . " " . $useroptioncont . $useroptionend;
                                            }
                                            echo '</datalist>';
                                        } else {
                                            echo '0 results';
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="staffnote" id="snote" placeholder="Staff Comment" class="form-control" onclick="note();" required></textarea>
                                    </div>
                                </div>
                            </div><hr class="line-black">
                            <div class="row">
                                <div  class="col-md-12 col-sm-12">
                                    <button onclick="note();" class="btn btn-success btn-block" name="completepurchasebtn">PLACE ORDER&nbsp;&nbsp;<span class="glyphicon glyphicon-check"></span></button>
                                    <input type="hidden" id="placeorder" name="placeOrder" value="<?php echo $recievedId; ?>">
                                    <input type="hidden" name="serialNo" value="<?php echo "$serial"; ?>">
                                </div>
                            </div>
                    </div>
                </div>
        </div>
    </form>
    <div class="clearfix"></div>
    <!-- The Modal -->
    <div id="addSup" class="my-modal">
        <div class="my-modal-content my-animate-top">
            <div class="my-container">
                <header>
                    <span class="my-closebtn" onclick="document.getElementById('addSup').style.display='none';">&times;</span>
                    <h2 class="my-center"><span class="label label-primary">Add New Supplier</span></h2><hr class="line-blue" />
                </header>
                <form action="orderList.php" method="POST" > 
                    <div class="col-sm-8 col-md-8">
                        <small>Supplier's Name:</small>
                        <input type="text" name="suppliername" class="form-control" required>
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <small>Phone Number:</small>
                        <input type="text" name="suppliernumber" class="form-control" placeholder="Phone" required>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <small>Contact Address:</small>
                        <textarea name="supplieraddress" class="form-control" required></textarea><br>
                        <button class="btn btn-success btn-block" name="addsupplierbtn">REGISTER <span class='glyphicon glyphicon-floppy-save'></span></button>
                    </div>
                </form>
            </div>
                    <br/>
        </div>
    </div><div class="clearfix"></div>
    <div class="container-fluid copy">
        <footer class="text-center copy">
            <small>&copy; 2018 All rights Reserved, Intelogiic Global Resources</small>
        </footer>
    </div>  
    <script type="text/javascript">



        var checkoutBtn = document.getElementById("itemId");
        var orderBtn = document.getElementById("placeorder")
        var delB = document.getElementById("delBtn");
        function _(tag) {
            checkoutBtn.value = tag.value;
            orderBtn.value = tag.value;
            delB.value = tag.value;
            console.log(delB.value);
        }

    </script>

    <script src="mymodalscript.js"></script>
    <script src="js/myscript.js"></script>           
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/order.js"></script>

</body>
</html>