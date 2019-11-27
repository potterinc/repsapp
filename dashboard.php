<?php
session_start();
if (!isset($_SESSION["fname"]) && !isset($_SESSION["lname"])) {
    header('Location: index.php');
}
require_once "qIconnection.php"; 
?> 
<!DOCTYPE html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/w3.css" /> 
        <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"> 

        <link rel="stylesheet" href="css/gadgets.css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- DataTables CSS -->
        <link href="css/dataTables/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="css/dataTables/dataTables.responsive.css" rel="stylesheet">
        <title>Gadgets People | Dashbaord</title> 

    </head>
    <body>
    <!-- navigation bar -->
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                <a class="navbar-brand w3-text-white" href="dashboard.php">Gadgets People</a>
            </div>
           <ul class="nav navbar-nav navbar-right">
               <li><a href="javascript:void(0)"><i class="fa fa-user"></i> <?= $_SESSION["fname"]." ". $_SESSION["lname"] ?></a></li>
               <li><a href="src/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
           </ul>
        </nav>
        <!-- //Navigation bar -->
        
        <div class="container-fluid w3-border-bottom">
            <div class="row">
                <div class="col-lg-3 col-md-6 w3-margin-bottom">
                    <div class="w3-card w3-round">
                        <header class="menu-link w3-center"><i class="fa fa-5x fa-gbp w3-margin"></i></header>
						<div class="w3-black w3-padding-6"><span class="w3-text-green">Profit:
						<?php 
                    $query = "SELECT SUM(AmtPaid) AS amt_paid FROM solditems WHERE salesDate ='".date('d-M-Y')."'";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0){
                        while ($data = mysqli_fetch_assoc($result)) {
                            print("{$data['amt_paid']}");
                        } 
                      }
					  else{
						  print("0");
					  }
                            ?>.00</span>
							<span class="w3-text-red w3-right">Debts:
						<?php 
                    $query1 = "SELECT SUM(Balance) AS amt_balance FROM solditems";
                    $result = $conn->query($query1);
                    if (mysqli_num_rows($result) > 0){
                        while ($data1 = mysqli_fetch_assoc($result)) {
                            print("{$data1['amt_balance']}");
                        } 
                      }
					  else{
						  print("0");
					  }
                            ?>.00</span></div>
                        <a href="item_list.php">
                            <div  title="Make a Sale" data-toggle="tooltip" class="w3-teal w3-center w3-large w3-padding-8">Sales</div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 w3-margin-bottom">
                    <div class="w3-card w3-round">
                        <header class="menu-link w3-center"><i class="fa fa-5x fa-cog w3-margin"></i></header>
						<div class="w3-black w3-padding-6"><span class="w3-text-green">Profit:
						<?php 
                    $query = "SELECT SUM(AmtPaid) AS amt_paid FROM repairs WHERE OrderDate ='".date('d-M-Y')."'";
                    $result = $conn->query($query);
                    if (mysqli_num_rows($result) > 0){
                        while ($data = mysqli_fetch_assoc($result)) {
                            print("{$data['amt_paid']}");
                        } 
                      }
					  else{
						  print("0");
					  }
                            ?>.00</span>
							<span class="w3-text-red w3-right">Debts:
						<?php 
                    $query1 = "SELECT SUM(Balance) AS amt_balance FROM repairs";
                    $result = $conn->query($query1);
                    if (mysqli_num_rows($result) > 0){
                        while ($data1 = mysqli_fetch_assoc($result)) {
                            print("{$data1['amt_balance']}");
                        } 
                      }
					  else{
						  print("0");
					  }
                            ?>.00</span></div>
                        <a href="javascript:void(0)" onclick="toggleTab(1)" >
                            <div  title="Maintenance & Repairs" data-toggle="tooltip" class="w3-teal w3-center w3-large w3-padding-8">Maintenance & Repairs</div>
                        </a>
                    </div>
                    
                </div>
                <div class="col-lg-3 col-md-6 w3-margin-bottom">
                    <div class="w3-card w3-round">
                        <header class="menu-link w3-center"><i class="fa fa-5x fa-users w3-margin"></i></header>
                        <a href="javascript:void(0)" onclick="toggleTab(2)">
                            <div title="Manage Users" data-toggle="tooltip"  class="w3-teal w3-center w3-large w3-padding-8">Administrator</div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 w3-margin-bottom">
                    <div class="w3-card w3-round">
                        <header class="menu-link w3-center"><i class="fa fa-5x fa-cart-plus w3-margin"></i></header>
                        <a href="javascript:void(0)" onclick="document.getElementById('addNewItem').style.display='block'">
                            <div title="Add Item" data-toggle="tooltip" class="w3-teal w3-center w3-large w3-padding-8">Inventory</div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
		<?php
			if (isset($_REQUEST["add-new"]))
			{
				$serial_no = $_REQUEST['serialno'];
				$category = $_REQUEST['cat'];
				$model = $_REQUEST['model'];
				$quantity= $_REQUEST['quantity'];
				$price = $_REQUEST['price'];
				$specs = $_REQUEST['specs'];
			
				$query = "INSERT INTO allitems (serialCode, Category, Model, Spec, Quantity, Price) 
				VALUES ('{$serial_no}', '{$category}','{$model}','{$specs}',{$quantity},{$price})";
				$result = mysqli_query($conn,$query);
				
				if ($result == TRUE)
				{
					printf("<script>alert('%s %s has been added to Inventory');</script>",$category,$model);
				}
				else
				{
					printf("<script>alert('Serial Number (%s) already exist. \\n Update from Item list');</script>",$serial_no);
				}
			}
			$name_err = $tel_err = $gadget_err = $gadget ="";
			//Repairs
			if (isset($_REQUEST["place-order"]))
			{
				// Validate Customer Name
				$input_name = trim($_POST["name"]);
				if(empty($input_name)){
					$name_err = "Please enter a name.";
				} elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
					$name_err = "Please enter a valid name.";
				} else{
					$name = $input_name;
				}
				
				// Validate telephone
				$input_tel = trim($_POST["tel"]);
				if(empty($input_tel)){
					$tel_err = "Please enter your phone number.";     
				} elseif(!ctype_digit($input_tel)){
					$tel_err = "Please enter a valid Telephone number.";
				} else{
					$tel = $input_tel;
				}
				
				// Validate Gadget
				$input_gadget = trim($_POST["gadget"]);
				if(empty($input_name)){
					$gadget_err = "Please enter a name.";
				} elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9\s]+$/")))){
					$gadget_err = "Please enter a valid name";
				} else{
					$gadget = $input_gadget;
				}
				
				$today = date("d-M-Y");
				$quantity = $_REQUEST["quantity"];
				$cost = $_REQUEST["cost"];
				$deposit = $_REQUEST["deposit"];
				$balance = $_REQUEST["balance"];
				$status = "Processing";
				$fault = $_REQUEST["fault"];
				
				$query = "INSERT INTO repairs (Gadget, Customer, Telephone, Qty, Fault, Price, Status, AmtPaid, Balance, OrderDate) 
				VALUES ('{$gadget}','{$name}','{$tel}',{$quantity},'{$fault}',{$cost},'{$status}',{$deposit},{$balance},'{$today}')";
				$result = $conn->query($query);
				
				if ($result == TRUE)
				{
					printf("<script>alert('%s Device has  been registerd')</script>",$gadget);
				}
				else
				{
					printf("<script>alert('Input Error: Please check inputs %s')</script>",mysqli_error($conn));
				}
			}
			
			//View Order
if (isset($_REQUEST["view-order"]))
{
    $item_id = $_REQUEST["gadget-id"];
    $query = "SELECT * FROM repairs WHERE GadgetId = $item_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0)
    {
        while($data = mysqli_fetch_assoc($result))
        {
            print("
            <div class='w3-card-4 w3-modal-content w3-animate-bottom w3-center' id='specs'>
                <span class='w3-closebtn w3-xlarge w3-text-white' id='close'>&times;</span>
                <header class='w3-padding-16 w3-teal w3-center text-uppercase w3-large'>{$data['Gadget']} </header>
                <div class='row'>
                        <div class='col-xs-4'>
                            <div class='w3-center w3-padding-4 w3-border-bottom text-uppercase w3-large'>Cash Deposit</div>
                            {$data['AmtPaid']}
                        </div>
                        <div class='col-xs-4'>
                            <div class='w3-center w3-padding-4 w3-border-bottom text-uppercase w3-large'><i class='fa fa-gbp'></i> Price</div>
                            {$data['Price']}
                        </div>
                        <div class='col-xs-4'>
                        <div class='w3-center w3-padding-4 w3-border-bottom text-uppercase w3-large'> Specification</div>
                            {$data['Fault']}
                        </div>
                </div><div class='clearfix'></div>
                <div class='text-center w3-black w3-padding-8'>
                    <small>&copy; 2019 All rights Reserved, Gadgets People</small>
                </div>
            </div>
		<script>
			{
				document.getElementById('specs').addEventListener('click',function(){
					document.getElementById('close').style.display='none';
					location.href='dashboard.php';
				});
			}
            </script>
            ");
        }
        exit();
    
}
}

//delete User
                if (isset($_REQUEST["del-user"]))
                {
                    $id = $_REQUEST["usr-id"];
                    $query = "SELECT * FROM RegUsers WHERE Id =".$id;
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0)
                    {
                        while($data = mysqli_fetch_assoc($result))
                        {
                            $firstname = $data["FirstName"];
                            $lastname = $data["LastName"];
                        }
                    $sql = "DELETE FROM RegUsers WHERE Id =".$id;
                    $retval = mysqli_query($conn, $sql);
                    printf("<script>alert('%s %s has been deleted')</script>", $firstname, $lastname);
                        if ($firstname == $_SESSION["fname"] && $lastname == $_SESSION["lname"])
                        {
                            printf("<script>location.href = '../src/logout.php';</script>");
                        }
                    }
                }     
				
	$completed = $processing = $collected = "";
	
			//Edit Order
if (isset($_REQUEST["edit-order"]))
{
    $item_id = $_REQUEST["gadget-id"];
    $query = "SELECT * FROM repairs WHERE GadgetId = $item_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0)
    {
        while($data = mysqli_fetch_assoc($result))
        {
			if ($data['Status'] === 'Processing')
				$processing = 'selected';
			if ($data['Status'] === 'Completed')
				$completed = 'selected';
			if ($data['Status'] === 'Collected')
				$collected = 'selected';
			
            print("
            <div class='w3-card-4 w3-animate-bottom' id='edit' style='width:60%; margin:auto'>
                        <span class='w3-closebtn' id='repairs'>&times;</span>
                    <header class='w3-teal w3-padding-16 w3-center w3-xlarge text-uppercase' style='letter-spacing: 5px'>
                        <i class='fa fa-mobile'></i> Gadgets People
                    </header><h4 class='w3-center'><b>Edit Order</b></h4>
                <div class='w3-container'>
                    <form action='dashboard.php' method='POST' class='w3-margin-top'>
                        <div class='col-lg-4'>
                            <div class='input-group'>
								<span class='input-group-addon w3-black'><i class='fa fa-mobile'></i></span>
								<input type='text' name='gadget' placeholder='Gadget' class='form-control' value='{$data['Gadget']}' required>
								<span class='w3-text-red'><?=$gadget_err?></span> 
							</div>
                        </div>
                        <div class='col-lg-4'>
                            <div class='input-group'>
								<span class='input-group-addon w3-black'><i class='fa fa-shopping-bag'></i></span>
								<input type='number' name='quantity' placeholder='Quantity' value='{$data['Qty']}' class='form-control' required> 
							</div>
                        </div>
                        <div class='col-lg-4'>
                            <div class='input-group'>
								<span class='input-group-addon w3-black'><i class='fa fa-gbp'></i></span>
								<input type='number' name='cost' id='serviceCost' value='{$data['Price']}' placeholder='Cost' class='form-control' required> 
							</div>
                        </div><div class='clearfix'></div>
                        <div class='col-lg-4 w3-margin-top'>
                            <div class='input-group'>
								<span class='input-group-addon w3-black'><i class='fa fa-comment'></i></span>
								<select name='status' class='form-control'>
									<option {$processing}>Processing</option>
									<option {$completed}>Completed</option>
									<option {$collected}>Collected</option>
								</select>
								<span class='w3-text-red'><?=$name_err?></span> 
							</div>
                        </div>
                        <div class='col-lg-4 w3-margin-top'>
                            <div class='input-group'>
								<span class='input-group-addon w3-black'><i class='fa fa-gbp'></i></span>
								<input type='number' name='deposit' id='deposit' placeholder='Deposit' value='{$data['AmtPaid']}' class='form-control' required> 
							</div>
                        </div>
                        <div class='col-lg-4 w3-margin-top'>
                            <div class='input-group'>
								<span class='input-group-addon w3-black'><i class='fa fa-gbp'></i></span>
								<input type='number' name='balance' id='balance' value='{$data['Balance']}' placeholder='Balance' class='form-control' required> 
							</div>
                        </div>
                        <div class='col-lg-12'>
                            <label>Device Fault:</label>
                            <textarea name='fault' class='form-control'>{$data['Fault']}</textarea>
                        </div>
						
						<div class='clearfix'></div>
                            <div class='w3-center w3-margin'>
								<input class='w3-btn w3-round w3-green text-uppercase' type='submit' name='update' value='UPDATE' />
								<input hidden name='gadget-id' value='$item_id' />
							</div>
                    </form>
                </div>
            <footer class='text-center w3-black w3-padding-8'>
                <small>&copy; 2019 All rights Reserved, Gadgets People</small>
            </footer>
            <script>
			{
				document.getElementById('repairs').addEventListener('click',function(){
					document.getElementById('edit').style.display='none';
					location.href='dashboard.php';
				});
				
			}
			{
				var service = document.getElementById('serviceCost');
				var balance = document.getElementById('balance');
				var deposit = document.getElementById('deposit');
				
				deposit.addEventListener('change', function(){
					if (service.value == null || service.value == ''){
						alert('Please specify a service charge');
						return false;
					}
					else
						balance.value = service.value - deposit.value;
				});
				
				balance.addEventListener('change', function(){
					balance.val(service.value - deposit.value);
				});
			}
            </script>
            </div>
            ");exit();
        }
        
    
}
}
			//update
			if (isset($_REQUEST['update']))
			{
				// Validate Gadget
				$gadget = $_POST["gadget"];
				$quantity = $_REQUEST["quantity"];
				$cost = $_REQUEST["cost"];
				$deposit = $_REQUEST["deposit"];
				$balance = $_REQUEST["balance"];
				$status = $_REQUEST["status"];
				$fault = $_REQUEST["fault"];
				$gadget_id =  $_REQUEST["gadget-id"];
				
				$query = "UPDATE repairs SET Gadget='{$gadget}', Qty={$quantity}, Fault ='{$fault}', Price ={$cost}, Status ='{$status}', 
				AmtPaid= {$deposit}, Balance={$balance} WHERE GadgetId = {$gadget_id}";
				
				$result = mysqli_query($conn, $query);
				if ($result == TRUE)
				{
					printf("<script>alert('%s status has been updated')</script>",$gadget);
				}
				else
				{
					printf("<script>alert('Input Error: Please check inputs %s')</script>",mysqli_error($conn));
				}
			}
		?>
        <div class="container">
        <!-- Maintenance and reparis -->
        <div class="row tab-controls">
            <h2>Maintenance &amp; Repairs</h2>
			
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h4>Repairs <a href="javascript:void(0)" class="w3-right w3-green w3-btn" onclick="document.getElementById('repairs').style.display='block'">
								   <i class="fa fa-cart-plus"></i> Place Order</a>
								   </h4>
                                </div>
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th><span class="fa fa-mobile"></span> Device</th>
                                                <th><span class="fa fa-user"></span> Customer</th>
                                                <th><span class="fa fa-phone"></span> Telephone</th>
                                                <th><span class='fa fa-shopping-bag'></span> Deposit</th>
                                                <th><span class='fa fa-refresh'></span> Balance</th>
                                                <th><span class='fa fa-refresh'></span> Status</th>
                                                <th><span class='fa fa-calendar'></span> Date</th>
                                                <th><span class='fa fa-cog'></span> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM repairs";
                                            $result = mysqli_query($conn, $query);
                                            while($data = mysqli_fetch_assoc($result))
                                            {
                                                echo '<tr class="odd gradeX">
                                                    <td>'.$data["Gadget"].'</td>
                                                    <td>'.$data["Customer"].'</td>
                                                    <td class="center">'.$data["Telephone"].'</td>
                                                    <td>'.$data["AmtPaid"].'</td>
                                                    <td>'.$data["Balance"].'</td>
                                                    <td>'.$data["Status"].'</td>
                                                    <td>'.$data["OrderDate"].'</td>
                                                    <td>
														<form method="post" action="dashboard.php">
															<button title="EDIT" data-toggle="tooltip" name="edit-order" class="w3-btn btn-primary" type="submit">
															<i class="fa fa-edit"></i>
															</button>
															<button title="View" data-toggle="tooltip" name="view-order" class="w3-btn w3-teal" type="submit">
															<i class="fa fa-eye"></i>
															</button>
															<input hidden name="gadget-id" value="'.$data["GadgetId"].'">
														</form>
													</td>
                                                </tr>';
                                            }

                                            ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                        <!-- /.col-lg-4 -->
        </div>
        <!-- //Maintenance and repairs -->

        <!-- Administrator -->
        <div class="row tab-controls">
            <h2>Administrator</h2>
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h4>Administrator</h4>
                                </div>
                            <div class="panel-body">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th><span class="fa fa-user"></span> First Name</th>
                                                <th><span class="fa fa-user"></span> Last Name</th>
                                                <th><span class="fa fa-envelope"></span> User Name</th>
                                                <th><span class='fa fa-cog'></span> Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $query = "SELECT * FROM RegUsers";
                                            $result = mysqli_query($conn, $query);
                                            while($data = mysqli_fetch_assoc($result))
                                            {
                                                echo '<tr class="odd gradeX">
                                                    <td>'.$data["FirstName"].'</td>
                                                    <td class="center">'.$data["LastName"].'</td>
                                                    <td class="center">'.$data["Usrname"].'</td>
                                                    <td>
													<form method="post" action="dashboard.php">
													<button title="DELETE" data-toggle="tooltip" name="del-user" class="w3-btn btn-danger" type="submit">
													<i class="fa fa-times"></i>
													</button>
													<input hidden name="usr-id" value="'.$data["Id"].'"></form></td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                        <!-- /.col-lg-4 -->
        </div>
        <!-- //Administrator -->
		
		<!-- Add New Item -->
        <div id="addNewItem" class="w3-modal">
            <div class="w3-modal-content w3-animate-bottom">
                        <span class="w3-closebtn" onclick="document.getElementById('addNewItem').style.display='none';">&times;</span>
                    <header class="w3-teal w3-padding-16 w3-center w3-xlarge text-uppercase" style="letter-spacing: 5px">
                        <i class="fa fa-mobile"></i> Gadgets People
                    </header><h4 class="w3-center"><b>Add New Item</b></h4>
                <div class="w3-container">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="w3-margin-top">
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-barcode"></i></span>
								<input type="text" name="serialno" placeholder="Serial Number" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-sitemap"></i></span>
								<input type="text" name="cat" placeholder="Category" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-cogs"></i></span>
								<input type="text" name="model" placeholder="Model" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-6 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-shopping-bag"></i></span>
								<input type="number" name="quantity" placeholder="Quantity" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-6 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-gbp"></i></span>
								<input type="number" name="price" placeholder="Price" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-12">
                            <label>Specification:</label>
                            <textarea name="specs" class="form-control"></textarea>
                        </div><div class="clearfix"></div>
                            <div class="w3-center w3-margin">
								<input class="w3-btn w3-round w3-green text-uppercase" type="submit" name="add-new" value="add item" />
							</div>
                    </form>
                </div>
            <footer class="text-center w3-black w3-padding-8">
                <small>&copy; 2019 All rights Reserved, Gadgets People</small>
            </footer>
            </div>
        </div>
		<!-- //Add New Item -->
		
		
		<!-- Place Order -->
        <div id="repairs" class="w3-modal">
            <div class="w3-modal-content w3-animate-bottom">
                        <span class="w3-closebtn" onclick="document.getElementById('repairs').style.display='none';">&times;</span>
                    <header class="w3-teal w3-padding-16 w3-center w3-xlarge text-uppercase" style="letter-spacing: 5px">
                        <i class="fa fa-mobile"></i> Gadgets People
                    </header><h4 class="w3-center"><b>Place Order</b></h4>
                <div class="w3-container">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="w3-margin-top">
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-mobile"></i></span>
								<input type="text" name="gadget" placeholder="Gadget" class="form-control" required>
								<span class="w3-text-red"><?=$gadget_err?></span> 
							</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-shopping-bag"></i></span>
								<input type="number" name="quantity" id="qty" placeholder="Quantity" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-gbp"></i></span>
								<input type="number" name="cost" id="serviceCost" placeholder="Service Cost" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-6 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-user"></i></span>
								<input type="text" name="name" placeholder="Customer" class="form-control" required>
								<span class="w3-text-red"><?=$name_err?></span> 
							</div>
                        </div>
                        <div class="col-lg-6 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-phone"></i></span>
								<input type="text" name="tel" placeholder="Telephone" class="form-control" required> 
								<span class="w3-text-red"><?=$tel_err?></span> 
							</div>
                        </div>
                        <div class="col-lg-4">
                            <label>Device Fault:</label>
                            <textarea name="fault" class="form-control"></textarea>
                        </div>
						
                        <div class="col-lg-4 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-gbp"></i></span>
								<input type="number" name="deposit" id="deposit" placeholder="Deposit" class="form-control" required> 
							</div>
                        </div>
                        <div class="col-lg-4 w3-margin-top">
                            <div class="input-group">
								<span class="input-group-addon w3-black"><i class="fa fa-gbp"></i></span>
								<input type="number" name="balance" id="balance" placeholder="Balance" class="form-control" required> 
							</div>
                        </div>
						<div class="clearfix"></div>
                            <div class="w3-center w3-margin">
								<input class="w3-btn w3-round w3-green text-uppercase" type="submit" name="place-order" value="Place Order" />
							</div>
                    </form>
                </div>
            <footer class="text-center w3-black w3-padding-8">
                <small>&copy; 2019 All rights Reserved, Gadgets People</small>
            </footer>
            </div>
        </div>
		<!-- //Place Order -->
        </div>
        <footer class="text-center copy w3-black w3-padding-8">
            <small>&copy; 2019 All rights Reserved, Gadgets People</small>
        </footer>
        

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
		
		<!-- Monetary Algorithm-->
		<script>
			$(document).ready(function(){
				var service = $('#serviceCost');
				var balance = $('#balance');
				var deposit = $('#deposit');
				
				$('#deposit').change(function(){
					if (service.val() == null || service.val() == ''){
						alert('Please specify a service charge');
						return false;
					}
					else
						balance.val(service.val() - deposit.val());
				});
				
				$('#balance').change(function(){
					balance.val(service.val() - deposit.val());
				});
			});
		</script>
		<!-- Monetary Algorithm-->
    </body>
</html>