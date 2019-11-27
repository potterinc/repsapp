<?php
session_start();
if (!isset($_SESSION["fname"]) AND !isset($_SESSION["lname"])) {
    header('Location: index.php');
}
require_once "qIconnection.php"; 


//require __DIR__ . '../escpos-php-development/autoload.php';
//use Mike42\Escpos\Printer;
//use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/w3.css" >
        <link rel="stylesheet" href="set.css">
        <link rel="stylesheet" type="text/css" href="css/cart.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css" >
        <title>QuickInventory:Sell Item</title>
        <?php

        $usre = "";
        $pasd = "";
        $fstN = "";
        $lasN = "";
        $accT = "";
        $acsR = "";

        $msgalerts = "";
        $oldqSd = "";
        $sellP = "";
        $copyRecord = "";
        $enterOrder = "";
        $snCode = "";

        $updAct1 = "";
        $enterCustomer = "";

        if (isset($_POST['addcustomerbtn'])) {
            $dtcna = $_POST['customername'];
            $dtcnu = $_POST['customernumber'];
            $dtcad = $_POST['customeraddress'];
            $dtdat = date("d-M-Y");

            $sql = "INSERT INTO customers(Name, contactAddress, phoneNumber, additionDate)VALUES ('$dtcna', '$dtcad', '$dtcnu', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $enterCustomer = 'New Customer added successfully. ';
            } else {
                $enterCustomer = 'Error in Customer entry. ' . $conn->error;
            }

            $actv = ' Added New Customer ' . $dtcna . ' to list.';
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates) VALUES('$fstN', '$lasN', '$actv', '$dtdat')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = 'User activity updated.';
            } else {
                $updAct1 = 'Error updating user activity. ' . $conn->error;
            }
            $next = header('Refresh:1; URL=sellItem.php');
            echo $next;
        }

        $hiddentext = "";
        $serialCodes = "";
        $quantitySold = "";
        $sellingPrice = "";
        $totalPrice = "";
        $Cust = "";
        $createResults = "";
        $checkbox = "";
        $updateOrder = "";
        $sId = "";
        $qSold = "";
//
//        if (isset($_POST['savepricebtn'])) {
//            $hiddenbox6 = $_POST['hiddenbox6'];
//            $qtysold = $_POST['qtysold'];
//            $sellprice = $_POST['sellingprice'];
//            $totalPrice = $sellprice * $qtysold;
//
//            $sql = "UPDATE sellitems SET quantitySold='$qtysold', sellingPrice='$sellprice', totalPrice='$totalPrice' WHERE id=$hiddenbox6";
//            // sql to update a record
//            if ($conn->query($sql) === TRUE) {
//                $updateOrder = 'Sales order updated successfully. ';
//            } else {
//                $updateOrder = 'Error updating Sales order: ' . $conn->error;
//            }
//        }

        $removeOrder = "";

        $msgalert = "";
        $updAct = "";
        $updateStock = "";
        $compbuy = "";
        $payment = "";
        $updateCustomer = "";
        $sucSales = "";
        $oldQtty;
        $qRm = "";
        $tPr = "";
        $sPr = "";
        $qSd = "";
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
        $selP;
        $serialCoded = "";
        ?>
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                <a class="navbar-brand w3-text-white" href="dashboard.php">Gadgets People</a>
            </div>
           <ul class="nav navbar-nav navbar-right">
           <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
               <li><a href="javascript:void(0)"><i class="fa fa-user"></i> <?= $_SESSION["fname"]." ". $_SESSION["lname"] ?></a></li>
               <li><a href="src/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
           </ul>
        </nav>

        <header class="text-center"><h1><span class="label w3-teal">SALES CART</span></h1></header>
        <hr class="line-blue">
        <div class="container-fluid">
            <div class="row">
                <div  class="col-md-12 col-sm-12">
                    <h4 class="text-center">Today is <i id="dateholder" class="badge"><?php
                            $today = date("D, d-M-Y");
                            echo "$today";
                            ?></i></h4>
                    <div class="pull-right">
                    
                        <h1><span>TOTAL PRICE: &nbsp;</span><span id="totalCost" class="label w3-teal">
                        <?php 
                    $query = "SELECT SUM(totalPrice) AS total_cost FROM sellitems";
                    $result = $conn->query($query);
                    if (mysqli_num_rows($result) == TRUE){
                        while ($data = mysqli_fetch_assoc($result)) {
                            print("{$data['total_cost']}");
                        } 
                      }
                      else
                            print("0");  
                            ?>
                        </span></h1>
                    </div>
                </div> 
            </div>
            <form method="POST" action="<?php $_PHP_SELF ?>">
            <div class="group-btn">
                <button title="Remove item" data-toggle="tooltip" class="btn w3-red text-uppercase" name="removeitembtn">
                <i class="fa fa-trash"></i>
                </button>
                <input type="hidden" id="del" name="deleteid" value="">
                <a href="item_list.php" class="group-btn btn w3-teal" title="Back" data-toggle="tooltip">
                <i class="fa fa-shopping-bag"></i>
                </a>
            
            </div>
            </form>
        </div>


        <?php
        if (isset($_POST['removeitembtn'])) {
            $id = $_POST["deleteid"];
            // sql to delete a record
            $sql = "DELETE FROM sellitems WHERE Id=$id";

            if ($conn->query($sql) === TRUE) {
                $removeOrder = 'Order deleted successfully. ';
            } else {
                $removeOrder = 'Error deleting order: ' . $conn->error;
            }
        }
        ?>
        <form method="POST" action="printer.php">
            <div class="container-fluid">
                <div class="row">
                    <div  class="col-md-12 col-sm-12">
                        <div class="tablecontainer" data-toggle="tooltip" title="Check to DELETE an item"> 
                            <?php
                            $hiddenboxstart = "<input type='hidden' name='hiddenid' value='";
                            $hiddenboxend = "'>";

                            $sql = "SELECT * FROM sellitems";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table id='itemlist'>
                <tr>
                <th><span class='glyphicon glyphicon-check'></span></th>
                <th>S/N Code</th><th>Category</th><th>Model</th><th>Quantity Sold</th><th>Price</th><th>Total Price</th></tr>";
                                // output data of each row

                                $count = 0;
                                while ($row = $result->fetch_assoc()) {
                                    $selection = $row["Id"];
                                    $checkbox = "<input id='checkBtn' type='radio' name='box' value='" . $selection . "' onclick='_(this.value);'; />";
                                    $hiddenboxstart = "<input type='hidden' name='hiddenid' value='" . $selection . "'>";
                                    echo "<tr id='itemRow' class='itemRow'><td>" . $checkbox . "</td><td style='display:none;'>" . $hiddenboxstart . "</td><td>" . $row["serialCode"] . "</td><td>" . $row["Category"] . "</td><td>" . $row["Model"] . "</td><td><input type='number' onchange='calc(this);' name='itemQty$count' value='" . $row["quantitySold"] . "' /></td><td id='price'>" . $row["sellingPrice"] . "</td><td id='totalSale$count' class='totalSale'>" . $row["totalPrice"] . "</td></tr>";

                                    $count += 1;
                                }
                                echo "</table>";

                                echo "<p hidden id='totalcount'>$count</p>";
                            } else {
                                echo "0 results";
                            }
                            ?>
                        </div>
                        <?php
                        if (isset($_POST['completesalesbtn'])) {
                            $totalSales = $_REQUEST["total"];
                            $cashPaid = $_REQUEST["cashpaid"];
                            $ePay = $_REQUEST["transferpaid"];
                            $totalPaid = $_REQUEST["totalpaid"];
                            $credit = $_REQUEST["credit"];
                            $customer = $_REQUEST["customers"];
                            $payRecieved = $_REQUEST["userpaid"];
                            $salesRep = $fstN . " " . $lasN . ": $accT";
                            $coment = $_REQUEST["staffnote"];
                            $desc = $receipt = "";


                            //POST each item quantity per row in table
                            for ($i = 0; $i < $count; $i++) {
                                $salesDesc = "";
                                //get item price from table
                                $query = "SELECT serialCode, Category, Model, sellingPrice FROM sellitems";
                                $queryResult = mysqli_query($conn, $query);

                                if (($i == 0) && ($queryResult == TRUE)) {
                                    $j = 0;
                                    while ($j < $count) {
                                        $itemQty[$j] = $_REQUEST["itemQty$j"];
                                        $dataTable[$j] = mysqli_fetch_assoc($queryResult);
                                        $serial[$j] = $dataTable[$j]["serialCode"];
                                        $price[$j] = $dataTable[$j]["sellingPrice"];
                                        $totalPrice = $itemQty[$j] * $price[$j];

                                        $category[$j] = $dataTable[$j]["Category"];
                                        $model[$j] = $dataTable[$j]["Model"];
                                        $desc .= $category[$j] . " | " . $model[$j] . " | " . $itemQty[$j] . " *\n";
                                        $cat .= $category[$j];
                                        $mod .= $model[$j];
                                        $qty .= $itemQty[$j];
                                        $amt .= $price[$j];
//                                        $receipt .= $category[$j] . " | " . $model[$j] . " | " . $itemQty[$j] . "\t\tAMOUNT: " . $price[$j] . "\n";
                                        //update item new quantity and total price to sales cart
                                        $query = "UPDATE sellitems SET quantitySold= $itemQty[$j], sellingPrice = $price[$j], totalPrice = $totalPrice WHERE serialCode = '$serial[$j]'";
                                        if (mysqli_query($conn, $query) === TRUE)
                                            $j++;
                                    }
                                }

                                //Check customer selected
                                if (empty($customer)) {
                                    echo "<script>alert('Please Select a CUSTOMER')</script>";
                                    $errorMsg = '<button class="btn btn-danger" onclick="location.href("sellitem.php");">REFRESH and select a Customer</button>';
                                    break;
                                } else {

                                    //make sales for first item in sales cart
                                    $cartQuery = "SELECT * FROM sellitems WHERE serialCode = 'serial[$i]'";
                                    $cartResult = mysqli_query($conn, $cartQuery);
                                    if (mysqli_num_rows($cartResult) > 0) {
                                        $dataTable = mysqli_fetch_assoc($cartResult);
                                        $itemQty[$i] = $dataTable[$i]["quantitySold"];
                                        $totalPrice[$i] = $dataTable[$i]["totalPrice"];
                                    }
//        update item quantity on inventory
                                    $inventory = "SELECT * FROM allitems WHERE serialCode = '$serial[$i]'";
                                    $invResult = mysqli_query($conn, $inventory);

                                    if (mysqli_num_rows($invResult) > 0) {
                                        $invTable = mysqli_fetch_assoc($invResult);
                                        $oldQty = $invTable["Quantity"];
                                        $category = $invTable["Category"];
                                        $brand = $invTable["Brand"];
                                        $model = $invTable["Model"];
                                        $specs = $invTable["Specs"];
                                        $sellPrice = $invTable["sellingPrice"];
                                        $newQty = $oldQty - $itemQty[$i];


                                        $inventory = "UPDATE allitems SET Quantity = $newQty WHERE serialCode = '$serial[$i]'";
                                        $invResult = mysqli_query($conn, $inventory);

                                        
                                        $totalItemPrice = $itemQty[$i] * $sellPrice;
                                        $itmPrice .= $totalItemPrice;

                                        $serial_no .= $serial[$i];
                                        $quantity .= $itemQty[$i];
                                    }
                                }
                                $salesDesc = $desc;
                            }
                            //updating sales history 
                            $sold_items = "INSERT INTO solditems (serialCode, Category, Model, Specs, quantitySold, totalItemPrice, totalCost, salesDate, Customer) 
                            VALUES ('{$serial_no}', '{$category}', '{$model}', '{$specs}', {$quantity}, {$totalItemPrice}, {$totalSales}, '{$dtdat}','{$customer}')";
                            $queryResult = mysqli_query($conn, $sold_items);

                                //updating income
                                $sType = "Stock";
                                $salesDate = date('d-M-Y');
                                $query = "INSERT INTO income (salesType, salesDesc, salesDate, salesBy, paidTo, totalCost, Customer, cashPaid, transferPaid, creditRemaining, lastDate, staffNote)
                                 VALUES ('$sType', '$salesDesc', '$salesDate', '$salesRep', '$payRecieved', $totalSales, '$customer', $cashPaid, $ePay, $credit, '$salesDate', '$coment')";
                                $queryResult = mysqli_query($conn, $query);
                                $lastId = mysqli_insert_id($conn);

                                //Updating user activities
                                $sql = "SELECT SUM(quantitySold) AS totalQuantitySold FROM sellitems";
                                $sqlResult = mysqli_query($conn, $sql);
                                $dataTable = mysqli_fetch_assoc($sqlResult);
                                $totalQtySold = $dataTable["totalQuantitySold"];

                                $actv = ' Sold ' . $totalQtySold . ' Item(s) to ' . $customer . '.';
                                $query = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$salesDate')";
                                $queryResult = mysqli_query($conn, $query);

                                //updating customer
                                $query = "SELECT allPurchase, sucPurchase, cashPurchase, tranPurchase, creditRem FROM customers WHERE Name='$customer'";
                                $queryResult = mysqli_query($conn, $query);

                                if (mysqli_num_rows($queryResult) == TRUE) {
                                    // output data of each row
                                    $storevalue = mysqli_fetch_assoc($queryResult);
                                    $oldAll = $storevalue["allPurchase"];
                                    $oldSuc = $storevalue["sucPurchase"];
                                    $oldCash = $storevalue["cashPurchase"];
                                    $oldTran = $storevalue["tranPurchase"];
                                    $oldCred = $storevalue["creditRem"];
                                }
                                $allS = $oldAll + $totalQtySold;
                                $sucS = $oldSuc + $totalQtySold;
                                $cashS = $oldCash + $cashPaid;
                                $tranS = $oldTran + $ePay;
                                $credRem = $oldCred + $credit;

                                $query = "UPDATE customers SET allPurchase='$allS', sucPurchase='$sucS', cashPurchase='$cashS', tranPurchase='$tranS', creditRem='$credRem', lastDate='$salesDate' WHERE Name='$customer'";
                                $queryResult = mysqli_query($conn, $query);


                                /*
                                 * Add nth Item to reciept array
                                 */
//                                $tableRow = "DESC            QTY  AMOUNT    TOTAL";
//                                $receipt = $cat."(".$mod.") ".$qty."  ".$amt."    ".$itmPrice."\n";
//
//try{
//                                $connector = new WindowsPrintConnector("BIXOLON");
//                                $printer = new Printer($connector);
//                                $justification = array(
//                                    Printer::JUSTIFY_LEFT,
//                                    Printer::JUSTIFY_CENTER,
//                                    Printer::JUSTIFY_RIGHT);
//                                $printer->setJustification($justification[1]);
//                                $printer->text($compNam . "\n". $compAdd. "\n".$compNum."\n");
//                                $printer->feed();
//                                $printer->setJustification();
//                                $printer->setJustification($justification[0]);
//                                $printer->text("TRANSACTION ID: " . $lastId);
//                                $printer->feed();
//                                $printer->text("==========================================");
//                                $printer->feed();
//                                $printer->setJustification();
//                                $printer->feed(1);
//                                $printer->text($tableRow."\n");
//                                $printer->text("------------------------------------------");
////                                for($i = 0; $i < $count; $i++){
//                                    $printer->text($receipt);
////                                }
//                                $printer->feed(1);
//                                $printer->setJustification(0);
//                                $printer->text("PAID:N".$totalPaid);
//                                $printer->setJustification(1);
//                                if($credit == NULL || $credit == ""){
//                                    $credit = 0;
//                                }
//                                $printer->text("  BAL:N". $credit);
//                                $printer->setJustification(2);
//                                $printer->text("  TOTAL: NGN" . $totalSales);
//                                $printer->feed();
//                                $printer->text("==========================================");
//                                $printer->feed();
//                                $printer->setJustification(1);
//                                $printer->text("Thank You ".$customer." for your Patronage\n");
//                                $printer->text("Sales made by: " . $salesRep . "\n DATE: " . $today . "\n");
//                                $printer->cut();
//                                $printer->close();
//}
// catch (Exception $e){
//     echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
// }
//
//                                echo '<script type="text/javascript">'
//                                . 'alert("SALES COMPLETED FOR ' . $customer
//                                . '\nTHANK YOU FOR YOUR PATRONAGE")'
//                                . '</script>';
//                                echo '<div class="my-modal my-card-8" id="receipt" style="display: block;"><div class="my-modal-content my-animate-bottom"><div class="my-container">'
//                                . '<header class="my-center text-uppercase"><small>'.$compNam.'<br>'.$compAdd.'<br>'.$compNum.'</small><h1><span class="my-closebtn" onclick="document.getElementById("receipt").style.display="none";">&times;</span>Payment Receipt</h1></header><hr class="line-blue">'
//                                . '<h3 class="text-center text-capitalize"><span class="label label-primary">' . $customer . '</span> Thank you for your patronage</h3>'
//                                . '<div class="well text-justify">TRANSACTION ID: ' . $lastId . '<br>'
//                                . '<table><tr><th>CATEGORY</th><th>MODEL</th><th>QUANTITY</th><th>PRICE</th></tr>'
//                                . '<tr><td>' . $cat . '</td><td>' . $mod . '</td><td>' . $qty . '</td><td>' . $amt . '</td></tr></table><br>'
//                                . '<h1 class="my-center"><span class="my-padding-4"><span class="label label-success">TOTAL: &#8358;' . $totalSales . '</span></h1></div>'
//                                . '<div class="my-black text-center my-padding-8">Sales authenticated by: ' . $salesRep . ' ON: ' . $today . '</div>'
//                                . '<button class="my-btn pull-right my-black my-hover-blue" onclick="location.href("viewItems.php");">PROCEED</button><br>'
//                                . '</div></div></div>';
//                                
                                //clearing cart
                                $query = "DELETE FROM sellitems";
                                $result = mysqli_query($conn, $query);
                                header("Location: ./printer.php");
                            
                        }
                        ?>
                        <script>
                            var delBtn = document.getElementById("del");

                            function _(tag) {
                                delBtn.value = tag;
                            }
                        </script>
                    </div>
                </div><hr class="line-blue">
                <div class="row">
                        <div class="col-lg-3">
                            <small>&nbsp;Cash Payment&nbsp;&#8356</small>
                            <input onchange="debitCash();" id='paidCash' class="form-control" type="text" name="cashpaid" value= '<?php echo 0; ?>' >
                        </div>
                        <!-- <div class="col-lg-1">
                            <small>&nbsp;E-Payment&nbsp;&#8358</small> -->
                            <input type="hidden" onchange="debitTran();" id="paidTran" class="form-control" type="text" name="transferpaid" value= '<?php echo 0; ?>' >
                        <!-- </div> -->
                        <!-- <div class="col-lg-2">
                            <small>&nbsp;Total Amount&nbsp;&#8358</small> -->
                            <input id="totalAmountPaid" class="form-control" type="hidden" name="totalpaid" value= '<?php echo 0; ?>' >
                        <!-- </div> -->
                        <div class="col-lg-3">
                            <small>&nbsp;Total Balance&nbsp;&#8356</small>
                            <input id='totalCredit' class="form-control"  type="text" name="credit" value= '<?php echo $totalPrice; ?>' >
                        </div>
                        
                        <div class="col-lg-3">
                                 <small>Payment Received By:</small>
                                <?php
                                $useroptionstart = '<option value="';
                                $useroptioncont = '">';
                                $useroptionend = 'user </option>';

                                $sql = "SELECT DISTINCT FirstName, LastName FROM RegUsers";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo '<input list="users" value="'.$_SESSION['fname'].' '.$_SESSION['lname'].'" class="form-control" name="userpaid" placeholder="Select User"><datalist id="users">';
                                    // output data of each row
                                    while ($row = $result->fetch_assoc()) {
                                        echo $useroptionstart . $row["firstName"] . " " . $row["lastName"] . " " . $useroptioncont . $useroptionend;
                                    }
                                    echo '</datalist>';
                                } else {
                                    echo '0 results';
                                }
                             ?>
                             </div>
                             <div class="col-lg-3">
                             <small>Customer:</small>
                             <div class="input-group">
                                <input name="customers" placeholder="Customer Name" class="form-control" required type="text" />
                             
                    <span class="input-group-btn">
                        <button title="Complete Sales" data-toggle="tooltip" class=" w3-teal btn" name="completesalesbtn"><i class="fa fa-shopping-bag"></i> SELL ITEM</button>
                        <input type="hidden" name="qSold" value="<?php echo $qSold; ?>" >
                        <input type="hidden" id="sell" value="<?php echo $sId; ?>" name="salesId" />

                        <input id="total" onchange="creditChange();" type="hidden" name="total" value="<?php 
                    $query = "SELECT SUM(totalPrice) AS total_cost FROM sellitems";
                    $result = $conn->query($query);
                    if (mysqli_num_rows($result) == TRUE){
                        while ($data = mysqli_fetch_assoc($result)) {
                            print("{$data['total_cost']}");
                        } 
                      }
                      else
                            print("0");  
                            ?>" />
                        </span>
                             </div>
                             </div>
                    </div>
                    </div>
                </div>
            </div>
        </form><br/><div class="clearfix"></div>
            <footer class="text-center w3-black w3-padding-8">
                <small>&copy; 2019 All rights Reserved, Gadgets People</small>
            </footer>


        <div class="clearfix"></div>
        <!-- The Modal -->
        <div id="addCust" class="my-modal">
            <div class="my-modal-content my-animate-top my-card-4">
                <div class="my-container my-justify">
                    <header>
                        <span class="my-closebtn" onclick="document.getElementById('addCust').style.display = 'none'">&times;</span>
                        <h2 class="text-center"><span class="label label-primary">Add Customer <span class="glyphicon glyphicon-user"></span></span></h2>
                    </header><hr class="line-blue">
                    <form action="sellItem.php" method="POST">
                        <div class="col-sm-6 col-md-6">
                            <small>Customer Name:</small>
                            <input type="text" name="customername" class="form-control" required>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <small>Phone Number:</small>
                            <input type="text" name="customernumber" placeholder="Phone Number" class="form-control" required>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <small>Address</small>
                            <textarea name="customeraddress" required placeholder="Address" class="form-control input-sm"></textarea>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <br>
                            <button class="btn btn-block btn-success" name="addcustomerbtn">REGISTER</span> <span class="glyphicon glyphicon-check"></span></button>
                            <br>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="mymodalscript.js"></script>       
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
            <script>
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
                                document.getElementById("customerlink").style.display = "none";
                            }

                            //Event Listener
                            var totalPrice = document.getElementsByClassName('itemRow').lastElementChild;
                            var price = document.getElementById('price').innerHTML;
                            function calc(doc) {
                                var totalPrice = document.getElementsByClassName('itemRow');
                                var parent = doc.parentNode;
                                var inputPrice = parent.nextSibling;
                                var total = inputPrice.nextSibling;
                                total.innerHTML = parseInt(inputPrice.innerHTML) * parseInt(doc.value);
                                sumTotal();
                            }

                            function sumTotal() {
                                var totalPrice = document.getElementsByClassName("totalSale");
                                var total = 0;
                                for (var i = 0; i < totalPrice.length; i++) {
                                    total += parseInt(totalPrice[i].innerHTML);
                                }
                                var totalCost = document.getElementById('totalCost').innerHTML = total;
								totalCost = total;
                                document.getElementById("totalCredit").value = total;

                                var paidCash = document.getElementById("paidCash").value;
                                var paidTran = document.getElementById("paidTran").value;
                                var new_total = document.getElementById("total").value = totalCost;

                                paidCash = parseInt(paidCash);
                                paidTran = parseInt(paidTran);
                                var totalcredt = total - (paidCash + paidTran);
                                if (paidCash != 0 || paidTran != 0) {
                                    document.getElementById("totalCredit").value = totalcredt;
                                }
                            }

        </script>
        <script src="js/myscript.js"></script>

    </body>
</html>