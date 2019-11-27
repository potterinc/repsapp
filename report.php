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
        <link rel="stylesheet" type="text/css" href="css/report.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="icon" href="image/icon.png">
        <title>QuickInventory:Report</title>

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

        $noResult = "";
        $qty = "";

        $sql = "SELECT Quantity FROM allitems";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row 
            $count = 0;

            while ($row = $result->fetch_assoc()) {
                $count += 1;
                $qty = $row["Quantity"];
                echo "<p hidden id='totalQnty$count'>" . $qty . "</p>";
            } echo "<p hidden id='totalcount1'>$count</p>";
        } else {
            $noResult = '0 ';
        }

        $noResult1 = "";

        $sql = "SELECT Quantity, lowQuantity FROM allitems WHERE lowQuantity=Quantity OR lowQuantity>Quantity";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row 
            $count = 0;

            while ($row = $result->fetch_assoc()) {
                $count += 1;
                $qty = $row["Quantity"];
                $lqt = $row["lowQuantity"];
                echo "<p hidden id='totalQnty$count'>" . $qty . " " . $lqt . "</p>";
            } echo "<p hidden id='totalcountex'>$count</p>";
        } else {
            $noResult1 = '0 ';
        }

        $updAct = "";
        $noResult2 = "";
        $qtyR = "";
        $ttlP = "";
        if (isset($_POST['purcReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT quantityReceived, totalPrice FROM receiveditems WHERE purchaseDate='$startdate' OR purchaseDate='$enddate' OR (purchaseDate<'$enddate' AND purchaseDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $qtyR = $storevalue["quantityReceived"];
                    $ttlP = $storevalue["totalPrice"];
                    echo "<p hidden id='totalQR$count'>" . $qtyR . "</p><p hidden id='totalTP$count'>" . $ttlP . "</p>";
                } echo "<p hidden id='totalcount2'>$count</p>";
            } else {
                $noResult2 = '<i>NO ITEM PURCHASED IN THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $actv = ' Generated Report for Purchase between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct = ' User activity updated.';
            } else {
                $updAct = ' Error updating user activity. ' . $conn->error;
            }
        }

        $updAct1 = "";
        $noResult3b = "";
        $noResult3 = "";
        $qtyS = "";
        $ttP = "";
        $name = "";
        if (isset($_POST['saleReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT quantitySold, totalPrice FROM solditems WHERE salesDate='$startdate' OR salesDate='$enddate' OR (salesDate<'$enddate' AND salesDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $qtyS = $storevalue["quantitySold"];
                    $ttP = $storevalue["totalPrice"];
                    echo "<p hidden id='totalQS$count'>" . $qtyS . "</p><p hidden id='totalTT$count'>" . $ttP . "</p>";
                } echo "<p hidden id='totalcount3'>$count</p>";
            } else {
                $noResult3 = '<i>NO ITEM SOLD IN THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $sql = "SELECT Name FROM customers WHERE additionDate='$startdate' OR additionDate='$enddate' OR (additionDate<'$enddate' AND additionDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 

                while ($storevalue = $result->fetch_assoc()) {
                    $name = $storevalue["Name"];

                    $sql = "SELECT quantitySold, totalPrice FROM solditems WHERE Customer='$name' AND (salesDate='$startdate' OR salesDate='$enddate' OR (salesDate<'$enddate' AND salesDate>'$startdate'))";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row 
                        $count = 0;

                        while ($storevalue = $result->fetch_assoc()) {
                            $count += 1;
                            $qtyS = $storevalue["quantitySold"];
                            $ttP = $storevalue["totalPrice"];
                            echo "<p hidden id='totalQSb$count'>" . $qtyS . "</p><p hidden id='totalTTb$count'>" . $ttP . "</p>";
                        } echo "<p hidden id='totalcount3b'>$count</p>";
                    } else {
                        $noResult3b = '<i>[ 0 Results for New customer(s) added within the period ]</i> ';
                    }
                }
            }

            $actv = ' Generated Report for Sales between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct1 = ' User activity updated.';
            } else {
                $updAct1 = ' Error updating user activity. ' . $conn->error;
            }
        }

        $updAct2 = "";
        $noResult4c = "";
        $noResult4b = "";
        $noResult4 = "";
        $cP = "";
        $tP = "";
        $name = "";
        if (isset($_POST['incmReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT cashPaid, transferPaid FROM income WHERE salesDate='$startdate' OR salesDate='$enddate' OR (salesDate<'$enddate' AND salesDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $cP = $storevalue["cashPaid"];
                    $tP = $storevalue["transferPaid"];
                    echo "<p hidden id='totalCashP$count'>" . $cP . "</p><p hidden id='totalTransP$count'>" . $tP . "</p>";
                } echo "<p hidden id='totalcount4'>$count</p>";
            } else {
                $noResult4 = '<i>NO INCOME GENERATED DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $sql = "SELECT cashPaid, transferPaid FROM income WHERE lastDate='$startdate' OR lastDate='$enddate' OR (lastDate<'$enddate' AND lastDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $cP = $storevalue["cashPaid"];
                    $tP = $storevalue["transferPaid"];
                    echo "<p hidden id='totalCashPc$count'>" . $cP . "</p><p hidden id='totalTransPc$count'>" . $tP . "</p>";
                } echo "<p hidden id='totalcount4c'>$count</p>";
            } else {
                $noResult4c = '<i>NO INCOME GENERATED OR CREDIT RECOVERED DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $sql = "SELECT Name FROM customers WHERE additionDate='$startdate' OR additionDate='$enddate' OR (additionDate<'$enddate' AND additionDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 

                while ($storevalue = $result->fetch_assoc()) {
                    $name = $storevalue["Name"];

                    $sql = "SELECT cashPaid, transferPaid FROM income WHERE Customer='$name' AND (salesDate='$startdate' OR salesDate='$enddate' OR (salesDate<'$enddate' AND salesDate>'$startdate'))";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row 
                        $count = 0;

                        while ($storevalue = $result->fetch_assoc()) {
                            $count += 1;
                            $cP = $storevalue["cashPaid"];
                            $tP = $storevalue["transferPaid"];
                            echo "<p hidden id='totalCashPb$count'>" . $cP . "</p><p hidden id='totalTransPb$count'>" . $tP . "</p>";
                        } echo "<p hidden id='totalcount4b'>$count</p>";
                    } else {
                        $noResult4b = '<i>[ 0 Results for New customer(s) added within the period ]</i> ';
                    }
                }
            }

            $actv = ' Generated Report for Income between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct2 = ' User activity updated.';
            } else {
                $updAct2 = ' Error updating user activity. ' . $conn->error;
            }
        }

        $updAct3 = "";
        $noResult5 = "";
        $cPd = "";
        $tPd = "";
        if (isset($_POST['expsReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT cashPaid, transferPaid FROM expenses WHERE purchaseDate='$startdate' OR purchaseDate='$enddate' OR (purchaseDate<'$enddate' AND purchaseDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $cPd = $storevalue["cashPaid"];
                    $tPd = $storevalue["transferPaid"];
                    echo "<p hidden id='totalCPaid$count'>" . $cPd . "</p><p hidden id='totalTPaid$count'>" . $tPd . "</p>";
                } echo "<p hidden id='totalcount5'>$count</p>";
            } else {
                $noResult5 = '<i>NO EXPENSES INCURRED DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $actv = ' Generated Report for Expenses between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct3 = ' User activity updated.';
            } else {
                $updAct3 = ' Error updating user activity. ' . $conn->error;
            }
        }

        $updAct4 = "";
        $noResult6b = "";
        $noResult7 = "";
        $noResult6 = "";
        $cR = "";
        $cRm = "";
        $name = "";
        if (isset($_POST['credReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT creditRemaining FROM income WHERE salesDate='$startdate' OR salesDate='$enddate' OR (salesDate<'$enddate' AND salesDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $cR = $storevalue["creditRemaining"];
                    echo "<p hidden id='totalCredR$count'>" . $cR . "</p>";
                } echo "<p hidden id='totalcount6'>$count</p>";
            } else {
                $noResult6 = '<i>NO CREDIT GIVEN TO CUSTOMERS DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $sql = "SELECT Name FROM customers WHERE additionDate='$startdate' OR additionDate='$enddate' OR (additionDate<'$enddate' AND additionDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 

                while ($storevalue = $result->fetch_assoc()) {
                    $name = $storevalue["Name"];

                    $sql = "SELECT creditRemaining FROM income WHERE Customer='$name' AND (salesDate='$startdate' OR salesDate='$enddate' OR (salesDate<'$enddate' AND salesDate>'$startdate'))";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row 
                        $count = 0;

                        while ($storevalue = $result->fetch_assoc()) {
                            $count += 1;
                            $cR = $storevalue["creditRemaining"];
                            echo "<p hidden id='totalCredRb$count'>" . $cR . "</p>";
                        } echo "<p hidden id='totalcount6b'>$count</p>";
                    } else {
                        $noResult6b = '<i>[ 0 Results for New customer(s) added within the period ]</i> ';
                    }
                }
            }

            $sql = "SELECT creditRemaining FROM expenses WHERE purchaseDate='$startdate' OR purchaseDate='$enddate' OR (purchaseDate<'$enddate' AND purchaseDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $cRm = $storevalue["creditRemaining"];
                    echo "<p hidden id='totalCRem$count'>" . $cRm . "</p>";
                } echo "<p hidden id='totalcount7'>$count</p>";
            } else {
                $noResult7 = '<i>NO CREDIT OWED SUPPLIERS DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $actv = ' Generated Report for Credits between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct4 = ' User activity updated.';
            } else {
                $updAct4 = ' Error updating user activity. ' . $conn->error;
            }
        }

        $updAct5 = "";
        $noResult9 = "";
        $noResult8 = "";
        $noResult8b = "";
        $qR = "";
        $aR = "";
        $qtR = "";
        $atR = "";
        $name = "";
        if (isset($_POST['retsReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT quantityReturned, amountReturned FROM returneditemsc WHERE returnDate='$startdate' OR returnDate='$enddate' OR (returnDate<'$enddate' AND returnDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $qR = $storevalue["quantityReturned"];
                    $aR = $storevalue["amountReturned"];
                    echo "<p hidden id='totalQRt$count'>" . $qR . "</p><p hidden id='totalARt$count'>" . $aR . "</p>";
                } echo "<p hidden id='totalcount8'>$count</p>";
            } else {
                $noResult8 = '<i>NO ITEM RETURNED TO CUSTOMERS DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $sql = "SELECT Name FROM customers WHERE additionDate='$startdate' OR additionDate='$enddate' OR (additionDate<'$enddate' AND additionDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 

                while ($storevalue = $result->fetch_assoc()) {
                    $name = $storevalue["Name"];

                    $sql = "SELECT quantityReturned, amountReturned FROM returneditemsc WHERE Customer='$name' AND (returnDate='$startdate' OR returnDate='$enddate' OR (returnDate<'$enddate' AND returnDate>'$startdate'))";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row 
                        $count = 0;

                        while ($storevalue = $result->fetch_assoc()) {
                            $count += 1;
                            $qR = $storevalue["quantityReturned"];
                            $aR = $storevalue["amountReturned"];
                            echo "<p hidden id='totalQRtb$count'>" . $qR . "</p><p hidden id='totalARtb$count'>" . $aR . "</p>";
                        } echo "<p hidden id='totalcount8b'>$count</p>";
                    } else {
                        $noResult8b = '<i>[ 0 Results for New customer(s) added within the period ]</i> ';
                    }
                }
            }

            $sql = "SELECT quantityReturned, amountReturned FROM returneditemss WHERE returnDate='$startdate' OR returnDate='$enddate' OR (returnDate<'$enddate' AND returnDate>'$startdate')";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $qtR = $storevalue["quantityReturned"];
                    $atR = $storevalue["amountReturned"];
                    echo "<p hidden id='totalQR$count'>" . $qtR . "</p><p hidden id='totalAR$count'>" . $atR . "</p>";
                } echo "<p hidden id='totalcount9'>$count</p>";
            } else {
                $noResult9 = '<i>NO ITEM RETURNED TO SUPPLIERS DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $actv = ' Generated Report for Returns between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct5 = ' User activity updated.';
            } else {
                $updAct5 = ' Error updating user activity. ' . $conn->error;
            }
        }

        $updAct6 = "";
        $noResult11 = "";
        $noResult10 = "";
        $Nm = "";
        $Name = "";
        if (isset($_POST['custReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT Name FROM customers";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $Nm = $storevalue["Name"];
                    echo "<p hidden id='allCust$count'>" . $Nm . "</p>";
                } echo "<p hidden id='totalcount10'>$count</p>";
            } else {
                $noResult10 = '<i>NO CUSTOMER EXISTS.</i> ';
            }

            $sql = "SELECT Name FROM customers WHERE additionDate='$startdate' OR (additionDate<'$enddate' AND additionDate>'$startdate') OR additionDate='$enddate'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $Name = $storevalue["Name"];
                    echo "<p hidden id='addedCust$count'>" . $Name . "</p>";
                } echo "<p hidden id='totalcount11'>$count</p>";
            } else {
                $noResult9 = '<i>NO NEW CUSTOMER ADDED DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $actv = ' Generated Report for Customers between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct6 = ' User activity updated.';
            } else {
                $updAct6 = ' Error updating user activity. ' . $conn->error;
            }
        }

        $updAct7 = "";
        $noResult13 = "";
        $noResult12 = "";
        $Nme = "";
        $Nam = "";
        if (isset($_POST['suppReportGen'])) {
            $startdate = $_POST['startdate'];
            $enddate = $_POST['enddate'];
            $lastD = date("Y-m-d");
            echo "<p hidden id='sDate'>" . $startdate . "</p><p hidden id='eDate'>" . $enddate . "</p>";

            $sql = "SELECT Name FROM suppliers";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $Nme = $storevalue["Name"];
                    echo "<p hidden id='allSupp$count'>" . $Nme . "</p>";
                } echo "<p hidden id='totalcount12'>$count</p>";
            } else {
                $noResult10 = '<i>NO SUPPLIER EXISTS.</i> ';
            }

            $sql = "SELECT Name FROM suppliers WHERE additionDate='$startdate' OR (additionDate<'$enddate' AND additionDate>'$startdate') OR additionDate='$enddate'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row 
                $count = 0;

                while ($storevalue = $result->fetch_assoc()) {
                    $count += 1;
                    $Nam = $storevalue["Name"];
                    echo "<p hidden id='addedSupp$count'>" . $Nam . "</p>";
                } echo "<p hidden id='totalcount13'>$count</p>";
            } else {
                $noResult9 = '<i>NO NEW SUPPLIER ADDED DURING THIS TIMEFRAME; Search within another timeframe.</i> ';
            }

            $actv = ' Generated Report for Suppliers between ' . $startdate . ' and ' . $enddate;
            $sql = "INSERT INTO usersactivity(firstname, lastname, activity, dates)VALUES ('$fstN', '$lasN', '$actv', '$lastD')";

            if ($conn->query($sql) === TRUE) {
                $updAct7 = ' User activity updated.';
            } else {
                $updAct7 = ' Error updating user activity. ' . $conn->error;
            }
        }

        if (isset($_POST['refreshbtn'])) {
            header('Refresh:0; URL= report.php');
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

        <i class="alertbar"><?php echo $updAct . $updAct1 . $updAct2 . $updAct3 . $updAct4 . $updAct5 . $updAct6 . $updAct7; ?></i> 
        
        <div class="container-fluid">
            <header class="text-center"><h1><span class="label label-primary">REPORT <span class="glyphicon glyphicon-stats"></span></span></h1></header>
        </div><hr class="line-blue">
                        <h4 class="my-center">Today is <i id="dateholder" class="badge my-blue"><?php $today =date("D, d-M-Y"); echo "$today"; ?></i>
                        </h4><br>

        <div class="container-fluid"> 
            <form role="form" action="<?php $_PHP_SELF ?>" method="POST">
                <div class="row">
                    <div  class="col-md-12 col-sm-12 text-center">
                        <span>Generate Report By Date: From <span class="glyphicon glyphicon-calendar"></span> <input type="date" id="startday" name="startdate" autofocus></span> 
                        <span class=""> To <span class="glyphicon glyphicon-calendar "></span> <input type="date" id="endday" name="enddate" autofocus></span> 
                        <br/><br/> 
                        <button class="btn btn-primary" name="purcReportGen" style="vertical-align:middle"><span>For Purchases</span> <span class="sign pull-right">&#9981</span></button> 
                        <button class="btn btn-success" name="saleReportGen" style="vertical-align:middle"><span>For Sales</span> <span class="sign pull-right">&#9749</span></button> 
                        <button class="btn btn-warning" name="incmReportGen" style="vertical-align:middle"><span>For Income</span> <span class="sign pull-right">&#9748</span></button>
                        <button class="btn my-black" name="expsReportGen" style="vertical-align:middle"><span>For Expenses</span> <span class="sign pull-right">&#9875</span></button> 
                        <button class="btn btn-danger" name="credReportGen" style="vertical-align:middle"><span>For Credits</span> <span class="sign pull-right">&#9889</span></button> 
                        <button class="btn" name="retsReportGen" style="vertical-align:middle"><span>For Returns</span> <span class="sign pull-right">&#9971</span></button> 
                        <button class="btn btn-info" name="custReportGen" vertical-align:middle"><span>For Customers</span> <span class="sign pull-right">&#9880</span></button> 
                        <button class="btn btn-success" name="suppReportGen" style="vertical-align:middle"><span>For Suppliers</span> <span class="sign pull-right">&#9832</span></button> 
                    </div> 
                </div><br><br> 
                <div class="row">
                    <div class="col-md-1 col-sm-1">
                        <br/><br/><br/><br/>
                        <form role="form" action="<?php $_PHP_SELF ?>" method="POST">
                        <button class="btn btn-success" name="refreshbtn"><span class="glyphicon glyphicon-refresh"></span> <span class="btntext">&nbsp;&nbsp;REFRESH</span></button>
                        </form>
                    </div>
                    <div class="col-md-9 col-sm-9"> 
                        <div class="row bigbox pull-right"> 
                            <div class="col-md-7 col-sm-7 subBox1 text-center">  
                                <p id="SumQT"><?php echo $noResult2; ?></p> 
                                <p id="SumQTb"> 
<?php
if (isset($_POST['purcReportGen'])) {
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    $sql = "SELECT serialCode, Category, Brand, quantityReceived FROM receiveditems WHERE purchaseDate='$startdate' OR purchaseDate='$enddate' OR (purchaseDate<'$enddate' AND purchaseDate>'$startdate')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row 
        echo 'The Item(s) include: <br/> ';
        while ($storevalue = $result->fetch_assoc()) {
            $SC = $storevalue["serialCode"];
            $CT = $storevalue["Category"];
            $BR = $storevalue["Brand"];
            $QR = $storevalue["quantityReceived"];
            echo $QR . ' ' . $BR . ' ' . $CT . ' ' . $SC . ' <br/> ';
        }
    }
}
?> 
                                </p> 
                                <p id="SumQS"><?php echo $noResult3; ?></p> 
                                <p id="SumQSb"><?php echo $noResult3b; ?></p> 
                                <p id="SumQSc"> 
<?php
if (isset($_POST['saleReportGen'])) {
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    $sql = "SELECT serialCode, Category, Brand, quantitySold FROM solditems WHERE salesDate='$startdate' OR salesDate='$enddate' OR (salesDate<'$enddate' AND salesDate>'$startdate')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row 
        echo 'The Item(s) include: <br/> ';
        while ($storevalue = $result->fetch_assoc()) {
            $SC = $storevalue["serialCode"];
            $CT = $storevalue["Category"];
            $BR = $storevalue["Brand"];
            $QS = $storevalue["quantitySold"];
            echo $QS . ' ' . $BR . ' ' . $CT . ' ' . $SC . ' <br/> ';
        }
    }
}
?> 
                                </p> 
                                <p id="SumInc"><?php echo $noResult4; ?></p> 
                                <p id="SumIncb"><?php echo $noResult4b; ?></p>
                                <p id="SumIncc"><?php echo $noResult4c; ?></p>
                                <p id="SumExp"><?php echo $noResult5; ?></p> 
                                <p id="SumCredCus"><?php echo $noResult6; ?></p>
                                <p id="SumCredCusb"><?php echo $noResult6b; ?></p> 
                                <p id="SumCredSup"><?php echo $noResult7; ?></p> 
                                <p id="SumRtCus"><?php echo $noResult8; ?></p>
                                <p id="SumRtCusb"><?php echo $noResult8b; ?></p> 
                                <p id="SumRtSup"><?php echo $noResult9; ?></p>
                                <p id="allCus"><?php echo $noResult10; ?></p>
                                <p id="addedCus"><?php echo $noResult11; ?></p> 
                                <p id="allSup"><?php echo $noResult12; ?></p> 
                                <p id="addedSup"><?php echo $noResult13; ?></p>
                            </div> 
                            <div class="col-md-5 col-sm-5 subBox2 text-center"> 
                                <div class="bwhite"> 
                                    We currently have <br/><span id="SumQuantity"><?php echo $noResult; ?></span><br/> Total Quantities in Stock.<br/> 
                                    <i class="smaller">(<i class="smaller" id="SumLowQ"></i> items in low quantity)&nbsp;</i>
                                    <span class="glyphicon glyphicon-th-list pull-right"></span> 
                                </div> 
                            </div>
                        </div> 
                    </div> 
                    <div  class="col-md-2 col-sm-2"><br/><br/><br/><br/>
                        <button class="btn btn-danger" name="printbtn" style="vertical-align:middle"><span>PRINT</span><br/><span class="glyphicon glyphicon-print"></span></button>
                    </div>
                </div> 
            </form> 
        </div><br>

        <div class="clearfix"></div>
        <div class="container-fluid copy">
                <footer class="text-center">
                    <small>&copy; 2018 All rights Reserved, Intelogiic Global Resources</small>
                </footer>
        </div>

        <script src="js/myscript.js"></script> 
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <script>

              var totalcount1 = document.getElementById('totalcount1').innerHTML;
              var totalQ = 0;
              var indQ;
              for (b = 1; b <= totalcount1; b++) {
                  indQ = document.getElementById('totalQnty' + b).innerHTML;
                  indQ = parseInt(indQ);
                  totalQ += indQ;

                  if (b == totalcount1) {
                      document.getElementById("SumQuantity").innerHTML = totalQ;
                  }
              }
        </script> 

        <script>

            var totalcountex = document.getElementById('totalcountex').innerHTML;

            document.getElementById("SumLowQ").innerHTML = totalcountex;
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount2 = document.getElementById('totalcount2').innerHTML;
            var totalQRd = 0;
            var indQR;
            var totalTPr = 0;
            var indTP;
            for (c = 1; c <= totalcount2; c++) {
                indQR = document.getElementById('totalQR' + c).innerHTML;
                indTP = document.getElementById('totalTP' + c).innerHTML;
                indQR = parseInt(indQR);
                indTP = parseInt(indTP);
                totalQRd += indQR;
                totalTPr += indTP;

                if (c == totalcount2) {
                    document.getElementById("SumQT").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we Received ' + totalQRd + ' Item(s) at a Total Price of &#8358;' + totalTPr + ' from our supplier(s).';
                }
            }
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount3 = document.getElementById('totalcount3').innerHTML;
            var totalQSd = 0;
            var indQS;
            var totalTTr = 0;
            var indTT;
            for (d = 1; d <= totalcount3; d++) {
                indQS = document.getElementById('totalQS' + d).innerHTML;
                indTT = document.getElementById('totalTT' + d).innerHTML;
                indQS = parseInt(indQS);
                indTT = parseInt(indTT);
                totalQSd += indQS;
                totalTTr += indTT;

                if (d == totalcount3) {
                    document.getElementById("SumQS").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we Sold ' + totalQSd + ' Item(s) at a Total Price of &#8358;' + totalTTr + ' to our customer(s).';
                }
            }

            var totalcount3b = document.getElementById('totalcount3b').innerHTML;
            var totalQSdb = 0;
            var indQSb;
            var totalTTrb = 0;
            var indTTb;
            var percentQSdb;
            var percentTTrb;
            for (db = 1; db <= totalcount3b; db++) {
                indQSb = document.getElementById('totalQSb' + db).innerHTML;
                indTTb = document.getElementById('totalTTb' + db).innerHTML;
                indQSb = parseInt(indQSb);
                indTTb = parseInt(indTTb);
                totalQSdb += indQSb;
                totalTTrb += indTTb;
                percentQSdb = (totalQSdb / totalQSd) * 100;
                percentTTrb = (totalTTrb / totalTTr) * 100;
                percentQSdb = parseInt(percentQSdb);
                percentTTrb = parseInt(percentTTrb);

                if (db == totalcount3b) {
                    document.getElementById("SumQSb").innerHTML = '[ ' + totalQSdb + ' (app. ' + percentQSdb + ' %) of which are to our New customer(s) added within the period, giving a sum of &#8358;' + totalTTrb + ' (app. ' + percentTTrb + ' %)]';
                }
            }
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount4 = document.getElementById('totalcount4').innerHTML;
            var totalC = 0;
            var indC;
            var totalT = 0;
            var indT;
            for (e = 1; e <= totalcount4; e++) {
                indC = document.getElementById('totalCashP' + e).innerHTML;
                indT = document.getElementById('totalTransP' + e).innerHTML;
                indC = parseInt(indC);
                indT = parseInt(indT);
                totalC += indC;
                totalT += indT;
                totalIncome = totalC + totalT;

                if (e == totalcount4) {
                    document.getElementById("SumInc").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we Received as Payment for items and services a sum of &#8358;' + totalIncome + ' from our customer(s).';
                }
            }

            var totalcount4c = document.getElementById('totalcount4c').innerHTML;
            var totalCc = 0;
            var indCc;
            var totalTc = 0;
            var indTc;
            for (ec = 1; ec <= totalcount4c; ec++) {
                indCc = document.getElementById('totalCashPc' + ec).innerHTML;
                indTc = document.getElementById('totalTransPc' + ec).innerHTML;
                indCc = parseInt(indCc);
                indTc = parseInt(indTc);
                totalCc += indCc;
                totalTc += indTc;
                totalIncomec = totalCc + totalTc;
                CredPaid = totalIncomec;

                if (ec == totalcount4c) {
                    document.getElementById("SumIncc").innerHTML = 'And from both Payment and Credit Recovered, we Received a total sum of &#8358;' + CredPaid + ' from our customer(s).';
                }
            }

            var totalcount4b = document.getElementById('totalcount4b').innerHTML;
            var totalCb = 0;
            var indCb;
            var totalTb = 0;
            var indTb;
            var percentIncomeb;
            for (eb = 1; eb <= totalcount4b; eb++) {
                indCb = document.getElementById('totalCashPb' + eb).innerHTML;
                indTb = document.getElementById('totalTransPb' + eb).innerHTML;
                indCb = parseInt(indCb);
                indTb = parseInt(indTb);
                totalCb += indCb;
                totalTb += indTb;
                totalIncomeb = totalCb + totalTb;
                percentIncomeb = (totalIncomeb / totalIncome) * 100;
                percentIncomeb = parseInt(percentIncomeb);

                if (eb == totalcount4b) {
                    document.getElementById("SumIncb").innerHTML = ' [ &#8358;' + totalIncomeb + ' (app. ' + percentIncomeb + ' %) of which are from our New customer(s) added within the period ]';
                }
            }
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount5 = document.getElementById('totalcount5').innerHTML;
            var totalCh = 0;
            var indCh;
            var totalTf = 0;
            var indTf;
            for (f = 1; f <= totalcount5; f++) {
                indCh = document.getElementById('totalCPaid' + f).innerHTML;
                indTf = document.getElementById('totalTPaid' + f).innerHTML;
                indCh = parseInt(indCh);
                indTf = parseInt(indTf);
                totalCh += indCh;
                totalTf += indTf;
                totalExpense = totalCh + totalTf;

                if (f == totalcount5) {
                    document.getElementById("SumExp").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we made as Payment for items and services a sum of &#8358;' + totalExpense + ' to our supplier(s).';
                }
            }
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount6 = document.getElementById('totalcount6').innerHTML;
            var totalCd = 0;
            var indCd;
            for (g = 1; g <= totalcount6; g++) {
                indCd = document.getElementById('totalCredR' + g).innerHTML;
                indCd = parseInt(indCd);
                totalCd += indCd;

                if (g == totalcount6) {
                    document.getElementById("SumCredCus").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we have given out as Credit a sum of &#8358;' + totalCd + ' To our Customer(s).';
                }
            }

            var totalcount6b = document.getElementById('totalcount6b').innerHTML;
            var totalCdb = 0;
            var indCdb;
            var percentCdb;
            for (gb = 1; gb <= totalcount6b; gb++) {
                indCdb = document.getElementById('totalCredRb' + gb).innerHTML;
                indCdb = parseInt(indCdb);
                totalCdb += indCdb;
                percentCdb = (totalCdb / totalCd) * 100;
                percentCdb = parseInt(percentCdb);

                if (gb == totalcount6b) {
                    document.getElementById("SumCredCusb").innerHTML = '[ &#8358;' + totalCdb + ' (app. ' + percentCdb + ' %) of which were to our New Customer(s) added within the period ]';
                }
            }
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount7 = document.getElementById('totalcount7').innerHTML;
            var totalCr = 0;
            var indCr;
            for (h = 1; h <= totalcount7; h++) {
                indCr = document.getElementById('totalCRem' + h).innerHTML;
                indCr = parseInt(indCr);
                totalCr += indCr;

                if (h == totalcount7) {
                    document.getElementById("SumCredSup").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we have owed as Credit a sum of &#8358;' + totalCr + ' To our Supplier(s).';
                }
            }
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount8 = document.getElementById('totalcount8').innerHTML;
            var allQRet = 0;
            var indQR;
            var allARet = 0;
            var indAR;
            for (i = 1; i <= totalcount8; i++) {
                indQR = document.getElementById('totalQRt' + i).innerHTML;
                indAR = document.getElementById('totalARt' + i).innerHTML;
                indQR = parseInt(indQR);
                indAR = parseInt(indAR);
                allQRet += indQR;
                allARet += indAR;

                if (i == totalcount8) {
                    document.getElementById("SumRtCus").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we paid &#8358;' + allARet + ' for ' + allQRet + ' Items Returned by our Customer(s).';
                }
            }

            var totalcount8b = document.getElementById('totalcount8b').innerHTML;
            var allQRetb = 0;
            var indQRb;
            var allARetb = 0;
            var indARb;
            var percentQRetb;
            var percentARetb;
            for (ib = 1; ib <= totalcount8b; ib++) {
                indQRb = document.getElementById('totalQRtb' + ib).innerHTML;
                indARb = document.getElementById('totalARtb' + ib).innerHTML;
                indQRb = parseInt(indQRb);
                indARb = parseInt(indARb);
                allQRetb += indQRb;
                allARetb += indARb;
                percentQRetb = (allQRetb / allQRet) * 100;
                percentARetb = (allARetb / allARet) * 100;
                percentQRetb = parseInt(percentQRetb);
                percentARetb = parseInt(percentARetb);

                if (ib == totalcount8b) {
                    document.getElementById("SumRtCusb").innerHTML = '[ ' + allQRetb + ' (app. ' + percentQRetb + ' %) of which were by our New Customer(s) added within the period, giving a sum of &#8358;' + allARetb + ' (app. ' + percentARetb + ' %)]';
                }
            }
        </script> 


        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount9 = document.getElementById('totalcount9').innerHTML;
            var QRt = 0;
            var Qind;
            var ARet = 0;
            var Aind;
            for (j = 1; j <= totalcount9; j++) {
                Qind = document.getElementById('totalQR' + j).innerHTML;
                Aind = document.getElementById('totalAR' + j).innerHTML;
                Qind = parseInt(Qind);
                Aind = parseInt(Aind);
                QRt += Qind;
                ARet += Aind;

                if (j == totalcount9) {
                    document.getElementById("SumRtSup").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we received &#8358;' + ARet + ' for ' + QRt + ' Items Returned to our Supplier(s).';
                }
            }
        </script> 

        <script>

            var totalcount10 = document.getElementById('totalcount10').innerHTML;

            document.getElementById("allCus").innerHTML = 'We have a total number of ' + totalcount10 + ' Customer(s).';
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount11 = document.getElementById('totalcount11').innerHTML;

            document.getElementById("addedCus").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we have added ' + totalcount11 + ' New Customer(s).';
        </script> 


        <script>

            var totalcount12 = document.getElementById('totalcount12').innerHTML;

            document.getElementById("allSup").innerHTML = 'We have a total number of ' + totalcount12 + ' Supplier(s).';
        </script> 

        <script>

            var sDate = document.getElementById("sDate").innerHTML;
            var eDate = document.getElementById("eDate").innerHTML;

            var totalcount13 = document.getElementById('totalcount13').innerHTML;

            document.getElementById("addedSup").innerHTML = 'Between ' + sDate + ' and ' + eDate + ', we have added ' + totalcount13 + ' New Supplier(s).';
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