<?php
session_start();
if (!isset($_SESSION["fname"]) AND !isset($_SESSION["lname"])) {
    header('Location: index.php');
}
require_once "qIconnection.php"; 

//require __DIR__ . '../escpos-php-development/autoload.php';
//
//use Mike42\Escpos\Printer;
//use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/w3.css" /> 
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/gadgets.css" />
        <link rel="stylesheet" href="css/font-awesome.min.css" >
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:RECEIPT</title>
        <style>
            body{
                height: inherit;
            }
            #column, #item{
                border-bottom: solid 1.5px #000;
            }
        </style>
    </head>
    <body>
        <?php
        $sql = "SELECT * FROM sellitems";

        $result = $conn->query($sql);
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count = mysqli_num_rows($result);
        }

        $cat = "";
        $mod = "";
        $amt = "";
        $qty = "";
        $itmPrice = "";
        $errorMsg = $category = "";
        $oldAll = "";
        $oldSuc = "";
        $oldCash = "";
        $oldTran = "";
        $oldCred = $totalpaid = 0;
        $sellPrice = $dtdat = $coment = "";
        
        if (isset($_POST['completesalesbtn'])) {
            $totalSales = $_REQUEST["total"];
            $cashPaid = $_REQUEST["cashpaid"];
            $ePay = $_REQUEST["transferpaid"];
            $totalPaid = $_REQUEST["totalpaid"];
            $credit = $_REQUEST["credit"];
            $customer = $_REQUEST["customers"];
            $payRecieved = $_REQUEST["userpaid"];
            $salesRep = "{$_SESSION['fname']} {$_SESSION['lname']}";
            $desc = $receipt = $serial_no = $quantity = "";



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
                    $errorMsg = '<div class="my-modal" id="error" style="display:block;"><div class="my-modal-content my-animate-zoom"><div class="my-container>'
                            . '<div class="my-center"><h1><a class="my-btn my-red btn-block" href="./sellitem.php">REFRESH and select a Customer</a></h1></div>'
                            . '</div></div></div>';
                    
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
                        $model = $invTable["Model"];
                        $specs = $invTable["Spec"];
                        $sellPrice = $invTable["Price"];
                        $newQty = $oldQty - $itemQty[$i];


                        $inventory = "UPDATE allitems SET Quantity = $newQty WHERE serialCode = '$serial[$i]'";
                        $invResult = mysqli_query($conn, $inventory);
                        $cat .= $category . " (" . $model . ")<br>[" . $serial[$i] . "]<br>";
                        $qty .= $itemQty[$i] . "<br><br>";
                        $amt .= $sellPrice . "<br><br>";

                        $totalItemPrice = $itemQty[$i] * $sellPrice;
                        $itmPrice .= $totalItemPrice . "<br><br>";

                        $serial_no .= $serial[$i];
                        $quantity .= $itemQty[$i];
                    }
                }
                $salesDesc = $desc;
            }
			
            $today_date = date("d-M-Y");
                //updating sales history 
                $sales_query = "INSERT INTO solditems (serialCode, ItemCategory, Qty, AmtPaid, Balance, totalCost, salesDate, ClientName) 
				 VALUES ('{$serial_no}', '{$category}', '{$quantity}', '{$cashPaid}','{$credit}', '{$totalSales}', '{$today_date}','{$customer}')";
                $sales_result = mysqli_query($conn, $sales_query);
				
                $salesDesc = $desc;
                //updating income
                $sType = "Sales";
                $salesDate = date('Y-m-d');
                $sql_query = "INSERT INTO income (salesType, salesDesc, salesDate, salesBy, paidTo, totalCost, Customer, cashPaid, transferPaid, creditRemaining, lastDate, staffNote) VALUES 
                ('$sType', '$salesDesc', '$salesDate', '$salesRep', '$payRecieved', $totalSales, '$customer', $cashPaid, $ePay, $credit, '$salesDate')";
                $queryResult = $conn->query($sql_query);
                $lastId = mysqli_insert_id($conn);

                //Updating user activities
                $sql = "SELECT SUM(quantitySold) AS totalQuantitySold FROM sellitems";
                $sqlResult = mysqli_query($conn, $sql);
                $dataTable = mysqli_fetch_assoc($sqlResult);
                $totalQtySold = $dataTable["totalQuantitySold"];

                if ($queryResult->num_rows > 0) {
                    // output data of each row
                    $storevalue = mysqli_fetch_assoc($queryResult);
                    $oldAll = $storevalue["allPurchase"];
                    $oldSuc = $storevalue["sucPurchase"];
                    $oldCash = $storevalue["cashPurchase"];
                    $oldTran = $storevalue["tranPurchase"];
                    $oldCred = $storevalue["creditRem"];

                $allS = $oldAll + $totalQtySold;
                $sucS = $oldSuc + $totalQtySold;
                $cashS = $oldCash + $cashPaid;
                $tranS = $oldTran + $ePay;
                $credRem = $oldCred + $credit;

                $query = "UPDATE customers SET allPurchase='$allS', sucPurchase='$sucS', cashPurchase='$cashS', tranPurchase='$tranS', creditRem='$credRem', lastDate='$salesDate' WHERE Name='$customer'";
                $queryResult = mysqli_query($conn, $query);
                } else {
                    $query = "INSERT INTO customers (Name, allPurchase, sucPurchase, cashPurchase, tranPurchase, creditRem, lastDate) VALUES ('$customer', $totalQtySold, $totalQtySold, $cashPaid, $ePay, $credit, '$salesDate')";
                    $queryResult = mysqli_query($conn, $query);
                }


                /*
                 * Add nth Item to reciept array
                 */
                $tableRow = "DESC            QTY  AMOUNT    TOTAL";
                //$receipt = $cat . "(" . $mod . ") " . $qty . "  " . $amt . "    " . $itmPrice . "\n";
//                try {
//                    $connector = new WindowsPrintConnector("BIXOLON");
//                    $printer = new Printer($connector);
//                    $justification = array(
//                        Printer::JUSTIFY_LEFT,
//                        Printer::JUSTIFY_CENTER,
//                        Printer::JUSTIFY_RIGHT);
//                    $printer->setJustification($justification[1]);
//                    $printer->text($compNam . "\n" . $compAdd . "\n" . $compNum . "\n");
//                    $printer->feed();
//                    $printer->setJustification();
//                    $printer->setJustification($justification[0]);
//                    $printer->text("TRANSACTION ID: " . $lastId);
//                    $printer->feed();
//                    $printer->text("==========================================");
//                    $printer->feed();
//                    $printer->setJustification();
//                    $printer->feed(1);
//                    $printer->text($tableRow . "\n");
//                    $printer->text("------------------------------------------");
////                                for($i = 0; $i < $count; $i++){
//                    $printer->text($receipt);
////                                }
//                    $printer->feed(1);
//                    $printer->setJustification(0);
//                    $printer->text("PAID:N" . $totalPaid);
//                    $printer->setJustification(1);
//                    if ($credit == NULL || $credit == "") {
//                        $credit = 0;
//                    }
//                    $printer->text("  BAL:N" . $credit);
//                    $printer->setJustification(2);
//                    $printer->text("  TOTAL: NGN" . $totalSales);
//                    $printer->feed();
//                    $printer->text("==========================================");
//                    $printer->feed();
//                    $printer->setJustification(1);
//                    $printer->text("Thank You " . $customer . " for your Patronage\n");
//                    $printer->text("Sales made by: " . $salesRep . "\n DATE: " . $today . "\n");
//                    $printer->cut();
//                    $printer->close();
//                } catch (Exception $e) {
//                    echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
//                }
//                                echo '<script type="text/javascript">'
//                                . 'alert("SALES COMPLETED FOR ' . $customer
//                                . '\nTHANK YOU FOR YOUR PATRONAGE")'
//                                . '</script>';
//                                echo '<div class="my-modal my-card-8" id="receipt" style="display: block;"><div class="my-modal-content my-animate-bottom"><div class="my-container">'
//                                . '<header class="my-center text-uppercase"><small>'.$compNam.'<br>'.$compAdd.'<br>'.$compNum.'</small><h1><span class"my-closebtn" onclick="document.getElementById("receipt").style.display="none";"></span>Payment Receipt</h1></header><hr class="line-blue">'
//                                . '<h3 class="text-center text-capitalize"><span class="label label-primary">' . $customer . '</span> Thank you for your patronage</h3>'
//                                . '<div class="well text-justify">TRANSACTION ID: ' . $lastId . '<br>'
//                                . '<table><tr><th>CATEGORY</th><th>MODEL</th><th>QUANTITY</th><th>PRICE</th></tr>'
//                                . '<tr><td>' . $cat . '</td><td>' . $mod . '</td><td>' . $qty . '</td><td>' . $amt . '</td></tr></table><br>'
//                                . '<h1 class="my-center"><span class="my-padding-4"><span class="label label-success">TOTAL: &#8358;' . $totalSales . '</span></h1></div>'
//                                . '<div class="my-black text-center my-padding-8">Sales authenticated by: ' . $salesRep . ' ON: ' . $today . '</div>'
//                                . '<button class="my-btn pull-right my-black my-hover-blue" onclick="location.href("viewItems.php");">PROCEED</button><br>'
//                                . '</div></div></div>';
                //clearing cart
                $query = "DELETE FROM sellitems";
                $result = mysqli_query($conn, $query);
//                                    $refresh = header("Refresh:1; URL:viewItems.php");
        
        }
        ?>
<?php echo $errorMsg;?>
        <div class="my-card-8" id="receipt">
            <div>
                <div class="my-container">
                    <header class="w3-center w3-teal w3-padding-32 w3-xxlarge text-uppercase" style="letter-spacing: 5px">
                    <i class="fa fa-mobile"></i> gadgets people</header>
                        <h4 class="text-center text-uppercase"><b>Payment Receipt</b></h4>
                    
                    <div>TRANSACTION ID : GP/UK/ITM-<?=$lastId; ?><br>
                        <div class="row">
                            <div class="col-xs-12">
                                <table>
                                    <tr id="column" class="w3-teal w3-padding-4"><th>ITEM(S)</th><th>QTY</th><th>PRICE</th><th>TOTAL (&#8356;)</th></tr>
                                    <tr id="item" class="w3-grey w3-text-white w3-padding-4"><td><h5><?= $cat; ?></h5></td><td><h5><?php echo $qty; ?></h5></td><td><h5><?php echo $amt; ?></h5></td><td><h5><?php echo $itmPrice; ?></h5></td></tr>
                                </table>
                            </div><hr>
                            <div class="w3-center">
                            <h3 class="" style="margin: 5px 0">TOTAL: &#8356; </span><?php echo $totalSales; ?></h3>
                            <h5 class="" style="margin: 5px 0">AMOUNT PAID: &#8356; </span><?php echo $totalPaid; ?></h5>
                            <h5 class="" style="margin: 5px 0">BALANCE: &#8356; </span><?php
                                if ($credit == NULL || $credit == "") {
                                    $credit = 0;
                                }echo $credit;
                                ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="w3-center w3-teal">
                    <small class="text-center my-padding-4">SERVED BY: <?php echo $salesRep . ', <br>DATE: ' . date('D, d-M-Y') ?></small><br>
                    <small class="my-center"><?php echo "Thank you " . $customer . ", for your patronage"; ?></small>
                    
                    </div>
                    <div class="btn-group pull-right">
                        <button  title="Print" data-toggle="tooltip" class="btn w3-blue btn-sm w3-hover-white" onclick="window.print();"><span class="glyphicon glyphicon-print"></span></button>
                        <button title="Sales" data-toggle="tooltip" class="btn w3-black btn-sm w3-hover-green" onclick="location.href='item_list.php'">
                        <i class="fa fa-shopping-bag"></i>
                        </button>
                    </div>
                    
            <footer class="text-center w3-black w3-padding-8">
                <small>&copy; 2019 All rights Reserved, Gadgets People</small>
            </footer>
                </div>
            </div>
        </div>;
      
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
       
        <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        })
        </script>
    </body>
</html>
