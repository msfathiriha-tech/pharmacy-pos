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
	<h2> EXPIRED STOCK &amp; STOCK EXPIRING WITHIN 6 MONTHS</h2>
	</div>
	</center>
	
	<?php

include "config.php";

/*
   Expiry dates are stored per purchase (batch), not per medicine.
   Show every batch that has:
   - already expired, or
   - expires within the next 6 months
   Expired batches come first (oldest expiry at the top).
*/

$sql = "SELECT p.p_id,
               p.sup_id,
               p.med_id,
               m.med_name,
               p.p_qty,
               p.pur_date,
               p.mfg_date,
               p.exp_date,
               DATEDIFF(p.exp_date, CURDATE()) AS days_left
        FROM purchase p
        LEFT JOIN meds m ON m.med_id = p.med_id
        WHERE p.exp_date <= DATE_ADD(CURDATE(), INTERVAL 6 MONTH)
        ORDER BY p.exp_date ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

?>

<table align="right" id="table1" style="margin-right:100px;">

    <tr>
        <th>Purchase ID</th>
        <th>Medicine ID</th>
        <th>Medicine Name</th>
        <th>Supplier ID</th>
        <th>Quantity Purchased</th>
        <th>Date of Purchase</th>
        <th>Manufacturing Date</th>
        <th>Expiry Date</th>
        <th>Status</th>
    </tr>

<?php

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        $daysLeft = (int) $row["days_left"];

        if ($daysLeft < 0) {
            $status = "Expired " . abs($daysLeft) . " day(s) ago";
            $color  = "red";
        } elseif ($daysLeft == 0) {
            $status = "Expires today";
            $color  = "red";
        } else {
            $status = "Expires in " . $daysLeft . " day(s)";
            $color  = "darkorange";
        }

        $medName = $row["med_name"] !== null ? $row["med_name"] : "(medicine removed)";

        echo "<tr>";

        echo "<td>" . htmlspecialchars($row["p_id"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["med_id"]) . "</td>";

        echo "<td>" . htmlspecialchars($medName) . "</td>";

        echo "<td>" . htmlspecialchars($row["sup_id"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["p_qty"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["pur_date"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["mfg_date"]) . "</td>";

        echo "<td style='color:$color; font-weight:bold;'>"
             . htmlspecialchars($row["exp_date"])
             . "</td>";

        echo "<td style='color:$color; font-weight:bold;'>" . $status . "</td>";

        echo "</tr>";
    }

} else {

    echo "<tr>";
    echo "<td colspan='9' style='text-align:center; color:green;'>";
    echo "No expired stock and nothing expiring within the next 6 months.";
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
