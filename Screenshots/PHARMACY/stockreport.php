<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="table1.css">
<title>
Reports
</title>
</head>

<body>

		<div class="sidenav">
			<h2 style="font-family:Times new roman; color:white; text-align:center;">RAHEEMS' PHARMACY </h2>
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
	<h2> MEDICINES LOW ON STOCK(LESS THAN 50)</h2>
	</div>
	</center>
	
	<table align="right" id="table1" style="margin-right:100px;">
		<tr>
			<th>Medicine ID</th>
			<th>Medicine Name</th>
			<th>Quantity Available</th>
			<th>Category</th>
			<th>Price</th>
		</tr>
		
<?php

include "config.php";

$sql = "SELECT med_id, med_name, med_qty, category, med_price
        FROM medicines
        WHERE CAST(med_qty AS UNSIGNED) < 50
        ORDER BY CAST(med_qty AS UNSIGNED) ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("SQL ERROR: " . mysqli_error($conn));
}

?>

<table align="right" id="table1" style="margin-right:100px;">

<tr>
    <th>Medicine ID</th>
    <th>Medicine Name</th>
    <th>Quantity Available</th>
    <th>Category</th>
    <th>Price</th>
</tr>

<?php

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

?>

<tr>
    <td><?php echo htmlspecialchars($row['med_id']); ?></td>

    <td><?php echo htmlspecialchars($row['med_name']); ?></td>

    <td style="color:red; font-weight:bold;">
        <?php echo htmlspecialchars($row['med_qty']); ?>
    </td>

    <td><?php echo htmlspecialchars($row['category']); ?></td>

    <td><?php echo htmlspecialchars($row['med_price']); ?></td>
</tr>

<?php

    }

} else {

    echo "<tr>";
    echo "<td colspan='5' style='text-align:center; color:red;'>";
    echo "No medicines with quantity less than 50.";
    echo "</td>";
    echo "</tr>";
}

mysqli_close($conn);

?>

</table>
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
