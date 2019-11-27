<?php
session_start();
if (!isset($_SESSION["fname"]) AND !isset($_SESSION["lname"])) {
    header('Location: index.php');
}
require_once "qIconnection.php"; 
?> 

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/w3.css" /> 
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/gadgets.css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- DataTables CSS -->
        <link href="css/dataTables/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="css/dataTables/dataTables.responsive.css" rel="stylesheet">
        <title>Gadgets People | Sales</title>
    </head>
    <body>
    <?php
                    if (isset($_POST["addToCart"])) {
                        $id = $_POST["item-id"];
                        //collect item information from Inventory
                        $sql = "SELECT * FROM allitems WHERE Id=$id";
                        $itemResult = $conn->query($sql);
                        $storevalue = $itemResult->fetch_assoc();
                        $price = $storevalue["Price"];
                        $serial = $storevalue["serialCode"];
                        $Category = $storevalue["Category"];
                        $model = $storevalue["Model"];
                        $Specs = $storevalue["Spec"];
                        $itemQty = $storevalue["Quantity"];

                        if ($itemQty < 1) {
                           print("<script>alert('{$Category} {$model} is no longer in stock')</script>");
                        } else {
                            //Check if the cart is empty and insert the first item
                            $query = "SELECT * FROM sellitems";
                            $cartResult = $conn->query($query);
                            if (($cartResult->num_rows == 0) || ($cartResult->num_rows == NULL)) {
                                $orderQty = 1;
                                $total = $orderQty * $price;
                                $sql = "INSERT INTO sellitems (serialCode, Category, quantitySold, Model, sellingPrice, totalPrice)
                                 VALUES('$serial', '$Category', $orderQty, '$model', '$price', $total)";
                                $cartResult = $conn->query($sql);
                                echo "<script>alert('$Category" . " added to your cart');</script>";
                            } elseif ($cartResult->num_rows > 0) {
                                $query = "SELECT quantitySold, sellingPrice FROM sellitems WHERE serialCode ='$serial'";
                                $cartResult = $conn->query($query);
                                $dataTable = $cartResult->fetch_assoc();
                                $oldQty = $dataTable["quantitySold"];
                                $itemPrice = $dataTable["sellingPrice"];
                                //check if cart is not empty and item already exist in cart
                                if ($oldQty >= 1) {
                                    $newQty = $oldQty + 1;
                                    $total = $newQty * $itemPrice;
                                    $query = "UPDATE sellitems SET quantitySold =$newQty, totalPrice =$total WHERE serialCode ='$serial'";
                                    $cartResult = $conn->query($query);
                                    echo "<script>alert('$Category" . " updated on your sales cart');</script>";
                                }
                                //if cart is not empty and item doesn't exist in cart
                                else {
                                    $orderQty = 1;
                                    $total = $orderQty * $price;
                                    $sql = "INSERT INTO sellitems (serialCode, Category, quantitySold, Model, sellingPrice, totalPrice) 
                                    VALUES('$serial', '$Category', $orderQty, '$model', '$price', $total)";
                                    $cartResult = $conn->query($sql);
                                    echo "<script>alert('$Category" . " added to your cart');</script>";
                                }
                            }
                        }
                    }

                    // Delete Item
                    if (isset($_REQUEST["del-item"]))
                    {
                        $item_id = $_REQUEST["item-id"];
                            $query = "SELECT * FROM allitems WHERE Id = $item_id";
                            $result = mysqli_query($conn, $query);
        
                            if (mysqli_num_rows($result) > 0)
                            {
                                while($data = mysqli_fetch_assoc($result))
                                {
                                    $category = $data["Category"];
                                    $model = $data["Model"];
                                }

                            $del_query = "DELETE FROM allitems WHERE Id = {$item_id}";
                            $retval = mysqli_query($conn, $del_query);
                            printf("<script>alert('%s %s has been deleted')</script>", $category, $model);
                            }
                    }
//Update item

if(isset($_REQUEST["update"]))
{
	$serial_no = $_REQUEST['serialno'];
	$category = $_REQUEST['cat'];
	$model = $_REQUEST['model'];
	$quantity= $_REQUEST['quantity'];
	$price = $_REQUEST['price'];
	$specs = $_REQUEST['specification'];
	$item_id = $_REQUEST['item-id'];
	
	$sql = "UPDATE allitems SET serialCode='{$serial_no}', Category='{$category}', Model='{$model}', Spec='{$specs}', 
	Quantity={$quantity}, Price={$price} WHERE Id={$item_id}";
	$result = mysqli_query($conn, $sql);
				
				if ($result == TRUE)
				{
					printf("<script>alert('%s %s has been updated')</script>",$category, $model);
				}
				else
				{
					printf("<script>alert('Input Error: Please check inputs %s')</script>",mysqli_error($conn));
				}
}

                    ?>
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
        <div class="container-fluid w3-border-bottom">
            <div class="row">
                <div class="col-lg-6 w3-margin-bottom">
                    <div class="w3-card w3-round">
					<div class="row">
						<div class="col-xs-2">
							<header class="menu-link w3-center"><i class="fa fa-5x fa-line-chart w3-margin"></i></header>
						</div>
                        <div class="col-xs-10">
                            <span class="w3-text-green fa fa-5x w3-margin">
							<?php
								$query = "SELECT * FROM solditems WHERE salesDate ='".date('d-M-Y')."'";
								$result = $conn->query($query);
								print(mysqli_num_rows($result));
							?>
							</span>
						</div>
					</div>
					</div>
                        
                        <a href="javascript:void(0)" onclick="toggleTab(2)">
                            <div class="w3-teal w3-center w3-large w3-padding-8">Todays Sales</div>
                        </a>
                    </div>
                <div class="col-lg-6 w3-margin-bottom">
                    <div class="w3-card w3-round">
                    <div class="row">
                        <div class="col-xs-1">
                            <header class="menu-link w3-center"><i class="fa fa-5x fa-gbp w3-margin"></i></header>
                        </div>
                        <div class="col-xs-11">
                            <span class="w3-text-green fa fa-5x w3-margin">
							<?php 
                    $query = "SELECT SUM(AmtPaid) AS amt_paid FROM solditems WHERE salesDate ='".date('d-M-Y')."'";
                    $result = $conn->query($query);
                    if (mysqli_num_rows($result) > 0){
                        while ($data = mysqli_fetch_assoc($result)) {
                            print("{$data['amt_paid']}");
                        } 
                      }
					  else{
						  print("0");
					  }
                            ?>.00</span></div>
                        </div>
                    </div>
                        
                        <a href="javascript:void(0)" >
                            <div class="w3-teal w3-center w3-large w3-padding-8" onclick="toggleTab(1)">View Items</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container">
<?php

if (isset($_REQUEST["specs"]))
{
    $item_id = $_REQUEST["item-id"];
    $query = "SELECT * FROM allitems WHERE Id ={$item_id}";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0)
    {
        while($data = mysqli_fetch_assoc($result))
        {
            print("
            <div class='w3-card-4 w3-modal-content w3-animate-bottom w3-center' id='specs'>
                <span class='w3-closebtn w3-xlarge w3-text-white' id='close'>&times;</span>
                <header class='w3-padding-16 w3-teal w3-center text-uppercase w3-large'>{$data['Category']} {$data['serialCode']}</header>
                <div class='row'>
                        <div class='col-xs-4'>
                            <div class='w3-center w3-padding-4 w3-border-bottom text-uppercase w3-large'>Model</div>
                            {$data['Model']}
                        </div>
                        <div class='col-xs-4'>
                            <div class='w3-center w3-padding-4 w3-border-bottom text-uppercase w3-large'><i class='fa fa-gbp'></i> Price</div>
                            {$data['Price']}
                        </div>
                        <div class='col-xs-4'>
                        <div class='w3-center w3-padding-4 w3-border-bottom text-uppercase w3-large'> Specification</div>
                            {$data['Spec']}
                        </div>
                </div><div class='clearfix'></div>
                <div class='text-center w3-black w3-padding-8'>
                    <small>&copy; 2019 All rights Reserved, Gadgets People</small>
                </div>
            </div>
            <script>
            document.getElementById('close').addEventListener('click',function(){
                document.getElementById('specs').style.display='none';
                location.href='item_list.php';
            });
            </script>
            ");
        }
        exit();
    
}
}

//Edit Items

if(isset($_REQUEST["edit-item"]))
{
	$item_id = $_REQUEST["item-id"];
	$query = "SELECT * FROM allitems WHERE Id = $item_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0)
    {
        while($data = mysqli_fetch_assoc($result))
        {
			
	print('
            <div class=" w3-card-4 w3-modal-content w3-animate-bottom" style="width:60%; margin:auto" id="update">
                        <span class="w3-closebtn" id="update-item">&times;</span>
                    <header class="w3-teal w3-padding-16 w3-center w3-xlarge text-uppercase" style="letter-spacing: 5px">
                        <i class="fa fa-mobile"></i> Gadgets People
                    </header><h4 class="w3-center"><b>Update Item</b></h4>
                <div class="w3-container">
                    <form action="item_list.php" method="POST" class="w3-margin-top">
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-barcode"></i></span>
								<input type="text" name="serialno" placeholder="Serial Number" value="'.$data['serialCode'].'" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-sitemap"></i></span>
								<input type="text" name="cat" value="'.$data['Category'].'" placeholder="Category" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-cogs"></i></span>
								<input type="text" name="model" placeholder="Model" value="'.$data['Model'].'" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-6 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-shopping-bag"></i></span>
								<input type="number" name="quantity" value="'.$data['Quantity'].'" placeholder="Quantity" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-6 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-gbp"></i></span>
								<input type="number" name="price" value="'.$data['Price'].'" placeholder="Price" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-12">
                            <label>Specification:</label>
                            <textarea name="specification" class="form-control">'.$data['Spec'].'</textarea>
                        </div><div class="clearfix"></div>
                            <div class="w3-center w3-margin">
								<input class="w3-btn w3-round w3-green text-uppercase" type="submit" name="update" value="UPDATE" />
								<input hidden name="item-id" value="'.$item_id.'" />
							</div>
                    </form>
                </div>
            <footer class="text-center w3-black w3-padding-8">
                <small>&copy; 2019 All rights Reserved, Gadgets People</small>
            </footer>
            </div>
        </div>
		<script>
			{
				document.getElementById("update-item").addEventListener("click",function(){
					document.getElementById("update").style.display="none";
					location.href="item_list.php";
				});
				
			}
            </script>');exit();
		}
}
}

?>
        <div class="tab-controls">
            <h2>Item List
            </h2>
            <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h4>Item List
								   <span class="w3-right badge w3-teal" title="Items in cart" data-toggle="tooltip">
            <i class="fa fa-shopping-cart"></i> 
            <?php 
            $sql = "SELECT * FROM sellitems";
            $cart_result = mysqli_query($conn, $sql);
            print(mysqli_num_rows($cart_result));
            ?> Items
            </span></h4>
                                </div>
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th><span class="fa fa-barcode"></span> Serial Number</th>
                                                <th><span class="fa fa-sitemap"></span> Category</th>
                                                <th><span class="fa fa-envelope"></span> Model</th>
                                                <th><span class='fa fa-shopping-bag'></span> Quantity</th>
                                                <th><span class='fa fa-gbp'></span> Price</th>
                                                <th><span class='fa fa-battery-full'></span> </th>
                                                <th><span class='fa fa-cog'></span> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $low_stock = "";
                                            $query = "SELECT * FROM allitems";
                                            $result = mysqli_query($conn, $query);
                                            while($data = mysqli_fetch_assoc($result))
                                            {
                                                if ($data["Quantity"] < 4)
                                                    $low_stock = "<i class='fa fa-battery-4 w3-text-red'></i>";
                                                else
                                                    $low_stock = "<i class='fa fa-battery-4 w3-text-green'></i>";

                                                echo '<tr class="odd gradeX">
                                                    <td>'.$data["serialCode"].'</td>
                                                    <td class="center">'.$data["Category"].'</td>
                                                    <td class="center">'.$data["Model"].'</td>
                                                    <td>'.$data["Quantity"].'</td>
                                                    <td>'.$data["Price"].'</td>
                                                    <td class="w3-center">'.$low_stock.'</td>
                                                    <td>
                                                        <form method="post" action="item_list.php">
                                                        <button title="Edit" data-toggle="tooltip" name="edit-item" class="w3-btn w3-blue" type="submit"><i class="fa fa-edit"></i></button>
                                                        <button title="Add To Cart" data-toggle="tooltip" name="addToCart" class="w3-btn w3-green" type="submit"><i class="fa fa-cart-plus"></i></button>
                                                        <button title="Specification" data-toggle="tooltip" name="specs" class="w3-btn w3-teal" type="submit"><i class="fa fa-eye"></i></button>
                                                        <button title="Delete" data-toggle="tooltip" name="del-item" class="w3-btn w3-red" type="submit"><i class="fa fa-times"></i></button>
                                                        <input hidden name="item-id" value="'.$data["Id"].'">
                                                        </form>
                                                    </td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                   <a href="cart.php" class="w3-btn btn-round w3-green" title="Checkout" data-toggle="tooltip">
                                    <i class="fa fa-send "></i> Checkout</a>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- Total Sales -->
                        <div class="tab-controls">
                            <h2>View Sales</h2>
                            <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h4>View Sales</h4>
                                </div>
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th><span class="fa fa-barcode"></span> Serial Number</th>
                                                <th><span class="fa fa-sitemap"></span> Category</th>
                                                <th><span class='fa fa-shopping-bag'></span> Quantity</th>
                                                <th><span class='fa fa-gbp'></span> Amount Paid</th>
                                                <th><span class='fa fa-gbp'></span> Balance</th>
                                                <th><span class='fa fa-gbp'></span> Total</th>
                                                <th><span class='fa fa-user'></span> Customer</th>
                                                <th><span class='fa fa-calendar'></span> Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $low_stock = "";
                                            $query = "SELECT * FROM solditems WHERE salesDate ='".date('d-M-Y')."'";
                                            $result = mysqli_query($conn, $query);
                                            while($data = mysqli_fetch_assoc($result))
                                            {
                                                echo '<tr class="odd gradeX">
                                                    <td>'.$data["serialCode"].'</td>
                                                    <td>'.$data["ItemCategory"].'</td>
                                                    <td>'.$data["Qty"].'</td>
                                                    <td>'.$data["AmtPaid"].'</td>
                                                    <td class="w3-center">'.$data["Balance"].'</td>
                                                    <td class="w3-center">'.$data["totalCost"].'</td>
                                                    <td class="w3-center">'.$data["salesDate"].'</td>
                                                    <td class="w3-center">'.$data["ClientName"].'</td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </div><br>
                        <!-- //Total Sales -->
        <footer class="text-center copy w3-black w3-padding-8">
            <small>&copy; 2019 All rights Reserved, Gadgets People</small>
        </footer>


            <script src="js/myscript.js"></script>           
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
         <!-- DataTables JavaScript -->
        <script src="js/dataTables/jquery.dataTables.min.js"></script>
        <script src="js/dataTables/dataTables.bootstrap.min.js"></script>
        
        <script>
            $(document).ready(function() {
                $('.order-list').DataTable({
                        responsive: true
                });
                $('[data-toggle="tooltip"]').tooltip();
                
            });
            
                        
                        
        </script>

            

        <!-- tab controler -->
        <script>
            var tabIndex = 1;
            toggleTab(1);

            function toggleTab(tabIndex){
            var tab = document.getElementsByClassName("tab-controls");
                for(var i = 0; i < tab.length; i++)
                    tab[i].style.display = 'none';
                tab[tabIndex - 1].style.display = 'block';
            }
        </script>
    </body>
</html>