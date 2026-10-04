<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="table1.css">
<link rel="stylesheet" type="text/css" href="form3.css">
<title>
Reports
</title>
<style>
body {font-family:Arial;}
</style>
</head>

<body>

	<div class="sidenav">
			<h2 style="font-family:Times new roman; color:white; text-align:center;"> RAHEEMS' PHARMACY</h2>
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
	<h2> TRANSACTION REPORTS</h2>
	</div>
	
	<br><br><br><br><br><br><br><br><br>
	
		<?php
		$start = isset($_POST['start']) ? $_POST['start'] : '';
		$end   = isset($_POST['end']) ? $_POST['end'] : '';
		?>
		<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
				<p>
					<label for="start">Start Date:</label>
					<input type="date" name="start" id="start" value="<?= htmlspecialchars($start) ?>" required>
				</p>
				<p>
					<label for="end">End Date:</label>
					<input type="date" name="end" id="end" value="<?= htmlspecialchars($end) ?>" required>
				</p>
			
		<input type="submit" name="submit" value="View Records">
		</form>	
	</center>
	
<?php
include "config.php";

if (isset($_POST['submit'])) {

	$validDate = function ($d) {
		$dt = DateTime::createFromFormat('Y-m-d', $d);
		return $dt && $dt->format('Y-m-d') === $d;
	};

	if (!$validDate($start) || !$validDate($end)) {
		echo "<p style='text-align:center; color:red;'>Please select a valid start and end date.</p>";
	} elseif ($start > $end) {
		echo "<p style='text-align:center; color:red;'>Start date cannot be after the end date.</p>";
	} else {

		// Purchases in range
		$stmt = $conn->prepare("SELECT p_id, sup_id, med_id, p_qty, p_cost, pur_date FROM purchase
				WHERE pur_date BETWEEN ? AND ? ORDER BY pur_date");
		$stmt->bind_param("ss", $start, $end);
		$stmt->execute();
		$result = $stmt->get_result();
		$pamt = 0;
?>
	<table align="right" id="table1" style="margin-right:100px;">
		<tr>
			<th>Purchase ID</th>
			<th>Supplier ID</th>
			<th>Medicine ID</th>
			<th>Quantity</th>
			<th>Date of Purchase</th>
			<th>Cost of Purchase(in Rs)</th>
		</tr>
<?php
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$pamt += $row["p_cost"];
				echo "<tr>";
				echo "<td>" . htmlspecialchars($row["p_id"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["sup_id"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["med_id"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["p_qty"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["pur_date"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["p_cost"]) . "</td>";
				echo "</tr>";
			}
		} else {
			echo "<tr><td colspan='6' style='text-align:center;'>No purchases in this period.</td></tr>";
		}
		$stmt->close();

		echo "<tr>";
		echo "<td colspan='5'>Total</td>";
		echo "<td>Rs." . number_format($pamt, 2) . "</td>";
		echo "</tr>";
		echo "</table>";

		// Sales in range
		$stmt = $conn->prepare("SELECT sale_id, c_id, s_date, total_amt, e_id FROM sales
				WHERE s_date BETWEEN ? AND ? ORDER BY s_date");
		$stmt->bind_param("ss", $start, $end);
		$stmt->execute();
		$result = $stmt->get_result();
		$samt = 0;
?>
	<table align="right" id="table1" style="margin-right:100px;">
		<tr>
			<th>Sale ID</th>
			<th>Customer ID</th>
			<th>Employee ID</th>
			<th>Date</th>
			<th>Sale Amount(in Rs)</th>
		</tr>
<?php
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$samt += $row["total_amt"];
				echo "<tr>";
				echo "<td>" . htmlspecialchars($row["sale_id"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["c_id"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["e_id"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["s_date"]) . "</td>";
				echo "<td>" . htmlspecialchars($row["total_amt"]) . "</td>";
				echo "</tr>";
			}
		} else {
			echo "<tr><td colspan='5' style='text-align:center;'>No sales in this period.</td></tr>";
		}
		$stmt->close();

		echo "<tr>";
		echo "<td colspan='4'>Total</td>";
		echo "<td>Rs." . number_format($samt, 2) . "</td>";
		echo "</tr>";
		echo "</table>";
?>
	<table align="right" id="table1" style="margin-bottom:100px;margin-right:100px;">
	<tr style="background-color: #f2f2f2;" >
		<td>Transaction Amount </td>
		<td>Rs.<?php echo number_format($samt - $pamt, 2); ?></td>
	</tr>
	</table>
<?php
	}
}
mysqli_close($conn);
?>
					
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
