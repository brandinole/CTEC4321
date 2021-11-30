<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection
include("shareda.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables

// make database connection
$conn = dbConnect();

?>
<?php
	print $HTMLHeader;
	print $course;
?>
<header>
	<nav>

		<ul>
			<li><a href="rc.html" title="Home">Home</a></li>
			<li><a href="menu.php" title="About">Menu</a></li>
			<li><a href="order.html" title="Projects">Online Order</a></li>
			<li><a href="location.html" title="About">Locations</a></li>
		</ul>

	</nav>
	<h1><?= $SubTitle_Admin ?></h1>
</header>

<script>
function confirmDel(title, LinkID) {
// javascript function to ask for deletion confirmation

	url = "admin_delete.php?ProductID="+ProductID;
	var agree = confirm("Delete this item: <" + title + "> ? ");
	if (agree) {
		// redirect to the deletion script
		location.href = url;
	}
	else {
		// do nothing
		return;
	}
}
</script>

<main>
<?php
// Retrieve the product & category info
	$sql = "SELECT Prices.ProductName, Prices.Price, Prices.ProductID FROM Prices, ProductCategory where Prices.CID = ProductCategory.CID order by Prices.CID";

	$stmt = $conn->stmt_init();

	if ($stmt->prepare($sql)){

		$stmt->execute();
		$stmt->bind_result($ProductName, $Price, $ProductID);

		$tblRows = "";
		while($stmt->fetch()){
			$Title_js = htmlspecialchars($ProductName, ENT_QUOTES); // convert quotation marks in the product title to html entity code.  This way, the quotation marks won't cause trouble in the javascript function call ( href='javascript:confirmDel ...' ) below.

			$tblRows = $tblRows."<tr><td>$ProductName</td>
								 <td>$Price</td>
							     <td><a href='admin_form.php'>Add to Order</a> | <a href='admin_form.php?LinkID=$ProductID'>Edit</a> | <a href='javascript:confirmDel(\"$Title_js\",$ProductID)'>Delete</a> </td></tr>";
		}

		$output = "
        <table class='itemList'>\n
		<tr><th>Item</th><th>Price</th><th>Options</th></tr>\n".$tblRows.
		"</table>\n";

		$stmt->close();
	} else {

		$output = "Query to retrieve product information failed.";

	}

	$conn->close();
?>



<div class='flexboxContainer'>
    <div>


        <?php echo $output ?>
    </div>
</div>
</main>

<?php print $PageFooter; ?>

</body>
<body style="background-color:#F7F6F2;">
</html>
