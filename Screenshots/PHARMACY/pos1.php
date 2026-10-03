<?php

include "config.php";

$row4 = array("", "", "", "", "", "");

/* Search selected medicine */
if (isset($_POST['search']) && isset($_POST['med']) && $_POST['med'] != "0") {

    $med = $_POST['med'];

    $qry3 = "SELECT * FROM meds WHERE med_name = ?";
    $stmt = $conn->prepare($qry3);
    $stmt->bind_param("s", $med);
    $stmt->execute();

    $result3 = $stmt->get_result();

    if ($result3->num_rows > 0) {
        $row4 = $result3->fetch_row();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form3.css">
<link rel="stylesheet" type="text/css" href="table2.css">
<title>
New Sales
</title>
</head>

<body>

		<div class="sidenav">
			<h2 style="font-family:Times new roman; color:white; text-align:center;"> RAHEEMS' PHARMACY </h2>
			<a href="adminmainpage.php">Dashboard</a>
			<button class="dropdown-btn">Inventory
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="inventory-add.php">Add New Medicine</a>
				<a href="inventory-view.php">Manage Inventory</a>
			</div>
			<button class="dropdown-btn">Suppliers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="supplier-add.php">Add New Supplier</a>
				<a href="supplier-view.php">Manage Suppliers</a>
			</div>
			<button class="dropdown-btn">Stock Purchase
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="purchase-add.php">Add New Purchase</a>
				<a href="purchase-view.php">Manage Purchases</a>
			</div>
			<button class="dropdown-btn">Employees
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="employee-add.php">Add New Employee</a>
				<a href="employee-view.php">Manage Employees</a>
			</div>
			<button class="dropdown-btn">Customers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="customer-add.php">Add New Customer</a>
				<a href="customer-view.php">Manage Customers</a>
			</div>
			<a href="sales-view.php">View Sales Invoice Details</a>
			<a href="salesitems-view.php">View Sold Products Details</a>
			<a href="pos1.php">Add New Sale</a>
			<button class="dropdown-btn">Reports
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="stockreport.php">Medicines - Low Stock</a>
				<a href="expiryreport.php">Medicines - Soon to Expire</a>
				<a href="salesreport.php">Transactions Reports</a>
			</div>
	</div>

	<div class="topnav">
		<a href="logout.php">Logout</a>
	</div>
	
	<center>
	<div class="head">
	<h2> POINT OF SALE</h2>
	</div>
	</center>
	

	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    <center>

    <select id="cid" name="cid">

        <option value="0" selected="selected">
            *Select Customer ID
        </option>

        <?php
        $qry = "SELECT c_id FROM customer ORDER BY c_id ASC";
        $result = $conn->query($qry);

        if ($result && $result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $selected = "";

                if (isset($_POST['cid']) &&
                    $_POST['cid'] == $row['c_id']) {

                    $selected = "selected";
                }

                echo "<option value=\"" .
                     htmlspecialchars($row['c_id']) .
                     "\" $selected>" .
                     htmlspecialchars($row['c_id']) .
                     "</option>";
            }
        }
        ?>

    </select>

    &nbsp;&nbsp;

    <input type="submit"
           name="custadd"
           value="Add to Proceed.">

    </center>
</form>
	
	<form method="post">
<center>
    <select id="med" name="med">

        <option value="0">Select Medicine</option>

        <?php

        $qry3 = "SELECT med_name FROM meds ORDER BY med_name ASC";
        $result3 = $conn->query($qry3);

        if ($result3 && $result3->num_rows > 0) {

            while ($medrow = $result3->fetch_assoc()) {

                $selected = "";

                if (isset($_POST['med']) &&
                    $_POST['med'] == $medrow['med_name']) {

                    $selected = "selected";
                }

                echo "<option value=\"" .
                     htmlspecialchars($medrow['med_name']) .
                     "\" $selected>" .
                     htmlspecialchars($medrow['med_name']) .
                     "</option>";
            }
        }

        ?>

    </select>

    &nbsp;&nbsp;

    <input type="submit" name="search" value="Search">

</form>
	
	<br><br><br>
	</center>
	
<div class="one row" style="margin-right:160px;">
<center>
<form method="post">
    <div class="column">

        <label for="medid">Medicine ID:</label>
        <input type="number" name="medid"
               value="<?php echo $row4[0] ?? ''; ?>"
               readonly>
        <br><br>

        <label for="mdname">Medicine Name:</label>
        <input type="text" name="mdname"
               value="<?php echo $row4[1] ?? ''; ?>"
               readonly>
        <br><br>

    </div>
</form>

    <div class="column">

        <label for="mcat">Category:</label>
        <input type="text" name="mcat"
               value="<?php echo $row4[3] ?? ''; ?>"
               readonly>
        <br><br>

        <label for="mloc">Location:</label>
        <input type="text" name="mloc"
               value="<?php echo $row4[5] ?? ''; ?>"
               readonly>
        <br><br>

    </div>


    <div class="column">

        <label for="mqty">Quantity Available:</label>
        <input type="number" name="mqty"
               value="<?php echo $row4[2] ?? ''; ?>"
               readonly>
        <br><br>

        <label for="mprice">Price of One Unit:</label>
        <input type="number" name="mprice"
               value="<?php echo $row4[4] ?? ''; ?>"
               readonly>
        <br><br>

    </div>


    <label for="mcqty">Quantity Required:</label>

    <input type="number" name="mcqty">

    &nbsp;&nbsp;&nbsp;

    <input type="submit" name="add" value="Add Medicine">

    &nbsp;&nbsp;&nbsp;

</center>
		<?php

if (isset($_POST['add'])) {

    $qry5 = "SELECT sale_id FROM sales ORDER BY sale_id DESC LIMIT 1";
    $result5 = $conn->query($qry5);
    $row5 = $result5->fetch_row();

    $sid = (int)$row5[0];

    $mid  = (int)$_POST['medid'];
    $aqty = (int)$_POST['mqty'];
    $qty  = (int)$_POST['mcqty'];

    // Convert price to a number
    $mprice = (float)$_POST['mprice'];

    if ($qty > $aqty || $qty == 0) {

        echo "QUANTITY INVALID!";

    } else {

        $price = $mprice * $qty;

        $qry6 = "INSERT INTO sales_items
        (`sale_id`, `med_id`, `sale_qty`, `tot_price`)
        VALUES
        ($sid, $mid, $qty, $price)";

        $result6 = mysqli_query($conn, $qry6);

        if (!$result6) {
            echo mysqli_error($conn);
        }

        echo "<br><br><center>";
        echo "<a class='button1 view-btn' href='pos2.php?sid=".$sid."'>View Order</a>";
        echo "</center>";
    }
}
?>

		
		</form>
	</div>
</body>

<script>
		var dropdown = document.getElementsByClassName("dropdown-btn");
		var i;

			for (i = 0; i < dropdown.length; i++) {
			  dropdown[i].addEventListener("click", function() {
			  this.classList.toggle("active");
			  var dropdownContent = this.nextElementSibling;
			  if (dropdownContent.style.display === "block") {
			  dropdownContent.style.display = "none";
			  } else {
			  dropdownContent.style.display = "block";
			  }
			  });
			}	
</script>

</html>