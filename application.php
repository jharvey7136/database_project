
<?php
$hostname = "localhost"; 
$username = "root";
$password = "";


//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password);

if (! $dbhandle){
	die("Unable to connect to MySQL: " . mysql_error());
}

//select a database to work with
$selected = mysql_select_db("lab2",$dbhandle) 
  or die("Could not select database");


//**************************************************MAIN MENU LOOP**************************************************//
	while (true){
	echo "---MAIN MENU---\n";
	echo "1. Add a Customer\n";
	echo "2. Add an Order\n";
	echo "3. Remove an Order\n";
	echo "4. Ship an Order\n";
	echo "5. Print Pending Order List With Customer Information\n";
	echo "6. Restock Parts\n";
	echo "7. Exit\n";
	$menuSelection = readline("Enter Number 1-7: ");

	if ($menuSelection > 7){
		echo "Invalid Input\n\n";
	}
	if ($menuSelection < 1){
		echo "Invalid Input\n\n";
	}

	switch($menuSelection){

//**************************************************ADD NEW CUSTOMER**************************************************//		
		case 1:

	$cnoCheck = mysql_query("SELECT cno FROM customers");		//SQL query to count number of customers in order to set CNO
	$cnoCount = 0;

	while ($row = mysql_fetch_array($cnoCheck))			//the customer cno is determined by counting the rows of customers and adding 1 to the row count
	{
		$cnoCount = $cnoCount + 1;
	}	

	$custNum = $cnoCount + 1;
	$custName = readline("Customer Name: ");
	$custStreet = readline("Customer Street: ");	
	$custZip = readline("Customer Zip: ");
	$custPhone = readline("Customer Phone: ");

	$zipCheck = mysql_query("SELECT zip FROM zipcodes WHERE zip = $custZip");   //test if zipcode already exists
	
	$rowCount = 0;
	while ($row = mysql_fetch_array($zipCheck))			
	{
		$rowCount = $rowCount + 1;
	}
	

	if($rowCount < 1){	//if the zip code does not already exist in zipcodes table, add the zip and city to zipcodes table
			echo "---ADD NEW ZIPCODE/CITY---\n";
		    $custCity = readline("Customer City: ");
			$sqlZip = "INSERT INTO zipcodes (zip, city) VALUES ('$custZip', '$custCity')";

			$addNewZip = mysql_query($sqlZip);
				if(! $addNewZip){
					die('Could not enter data: ' . mysql_error());
				}
			echo "\nZip added successfully\n";			
	}

	//insert all the customer data into the customers table
	$sqlCust = "INSERT INTO customers (cno, cname, street, zip, phone) VALUES ('$custNum', '$custName', '$custStreet', '$custZip', '$custPhone')";

	$addCust = mysql_query($sqlCust);

	if(! $addCust){
		die('Could not enter data: ' . mysql_error());
	}

	echo "\nCustomer added successfully\n\n";		
		break;
//**************************************************END ADD NEW CUSTOMER**************************************************//















//**************************************************ADD NEW ORDER**************************************************//
		case 2:
	$onoCheck = mysql_query("SELECT ono FROM orders");		//SQL query to count number of orders in order to set ONO
	$onoCount = 0;

	while ($row = mysql_fetch_array($onoCheck))			//the order ono is determined by counting the rows of orders and adding 1 to the row count
	{
		$onoCount = $onoCount + 1;
	}

	$onoNum = $onoCount + 1;
	$cnoNum = readline("Customer Number (CNO): ");
	$enoNum = readline("Employee Number (ENO): ");

	$cnoCheck = mysql_query("SELECT cno FROM customers WHERE cno = $cnoNum");   //test if cno already exists
	
	$orderRowCount = 0;
	while ($orderRow = mysql_fetch_array($cnoCheck))			
	{
		$orderRowCount = $orderRowCount + 1;
	}

	if($orderRowCount < 1){					//if the zip code does not already exist in zipcodes table, add the zip and city to zipcodes table
		echo "Customer does not exist. CNO must already exist in customers table. Enter existing CNO or add a new customer.\n\n";
		break;			
	}


	$enoCheck = mysql_query("SELECT eno FROM employees WHERE eno = $enoNum");   //test if eno already exists
	
	$orderRowCount = 0;
	while ($orderRow = mysql_fetch_array($enoCheck))			
	{
		$orderRowCount = $orderRowCount + 1;
	}

	if($orderRowCount < 1){					//if the zip code does not already exist in zipcodes table, add the zip and city to zipcodes table
		
		//$enoEmployee = readline("Enter Employee Number (ENO): ");
		echo "---ADD NEW EMPLOYEE---\n";
		$enameEmployee = readline("Enter Employee Name: ");
		$zipEmployee = readline("Enter Employee Zip: ");
			$zipCheck = mysql_query("SELECT zip FROM zipcodes WHERE zip = $zipEmployee");   //test if zipcode already exists
	
			$rowCount = 0;
				while ($row = mysql_fetch_array($zipCheck)){
						$rowCount = $rowCount + 1;
					}
	

					if($rowCount < 1){		//if the zip code does not already exist in zipcodes table, add the zip and city 					to zipcodes table
		    			$empCity = readline("Employee City: ");
						$sqlZip = "INSERT INTO zipcodes (zip, city) VALUES ('$zipEmployee', '$empCity')";

						$addNewZip = mysql_query($sqlZip);
							if(! $addNewZip){
									die('Could not enter data: ' . mysql_error());
							}
								echo "\nZip added successfully\n";			
					}
				$sqlEmployee = "INSERT INTO employees (eno, ename, zip, hdate) VALUES ('$enoNum', '$enameEmployee', '$zipEmployee', NOW())";

				$addEmployee = mysql_query($sqlEmployee);
					if(! $addEmployee){
						die('Could not enter data: ' . mysql_error());
					}

					echo "Employee added successfully\n\n";	
	}		

	$pnoNum = readline("Enter Part Number (PNO): ");
	$qtyNum = readline("Enter Quantity: ");

	$pnoCheck = mysql_query("SELECT pno FROM parts WHERE pno = $pnoNum");   //test if pno already exists
	$pnoRowCount = 0;
	while ($pnoRow = mysql_fetch_array($pnoCheck))	//fetch pno rows		
	{
		$pnoRowCount = $pnoRowCount + 1;
	}

	if($pnoRowCount < 1){	//if part does not exist, add it to parts table	
	echo "---ADD NEW PART---\n";
	$pnameParts = readline("Enter Part Name: ");
	$qohParts = readline("Enter Part Quantity On Hand: ");
	$upParts = readline("Enter Part Unit Price: ");
	$levelParts = readline("Enter Part Level: ");

	$sqlAddPart = "INSERT INTO parts (pno, pname, qoh, price, level) VALUES ('$pnoNum', '$pnameParts', '$qohParts', '$upParts', '$levelParts')";
	$addPart = mysql_query($sqlAddPart);
	if($addPart){
		echo "Part Added successfully\n\n";
	if(! $addPart){
		echo "Part Not Added, PNO already exists";
			}
		}
	}





	//add to order table
	$sqlOrder = "INSERT INTO orders (ono, cno, eno, received) VALUES ('$onoNum', '$cnoNum', '$enoNum', NOW())";
	$addOrder = mysql_query($sqlOrder);

	

	if(! $addOrder){
		die('Could not enter data: ' . mysql_error());
	}

	echo "\nOrder added successfully\n\n";		
		
	//add to order_line table




	$sqlOrderLine = "INSERT INTO order_line (ono, pno, qty) VALUES ('$onoNum', '$pnoNum', '$qtyNum')";
	$addOrderLine = mysql_query($sqlOrderLine);
	if($addOrderLine){
		echo "Order Line updated successfully\n\n";
	}
	break;
		
//**************************************************END ADD NEW ORDER**************************************************//











//**************************************************REMOVE ORDER**************************************************//
		case 3:
		$onoToRemove = readline("Enter Order Number (ONO) to Remove: ");
		$onoRemoveCheck = mysql_query("SELECT ono FROM orders WHERE ono = $onoToRemove");

		$removeRowCount = 0;
		while ($onoRemoveRow = mysql_fetch_array($onoRemoveCheck))	//fetch ono rows		
		{
			$removeRowCount = $removeRowCount + 1;
		}


		if($removeRowCount < 1){
			echo "Order Number (ONO) $onoToRemove Does Not Exist\n\n";
			break;
		}

		$pnoToUpdate = mysql_query("SELECT pno FROM order_line WHERE ono=$onoToRemove");


		$sqlUpdateQOH = "UPDATE parts SET qoh=qoh+1 WHERE pno=$pnoToUpdate";
		mysql_query($sqlUpdateQOH);


		mysql_query("DELETE FROM order_line WHERE ono = $onoToRemove");     //remove order from order_line table

		$sqlRemoveOrder = "DELETE FROM orders WHERE ono = $onoToRemove";	//remove order from order table
		$removeOrder = mysql_query($sqlRemoveOrder);
		if($removeOrder){
			echo "Order Removed Successfully\n";
		} else {
			echo "Error Removing Order: " . mysql_error();
		}
		break;
//**************************************************END REMOVE ORDER**************************************************//





//**************************************************SHIP ORDER**************************************************//
		case 4:
		$onoToShip = readline("\nEnter Order Number (ONO) To Ship: ");

		echo "\n";

		$shipOrderSQL = mysql_query("SELECT shipped FROM orders WHERE ono=$onoToShip");

		$shippedDate = date('m/d/Y h:i:s a', time());    //get date and time that order is being shipped

		echo "Order Number (ONO): $onoToShip has been shipped at $shippedDate.";

		echo "\n";

		$removeFromOrders    = mysql_query("DELETE FROM orders WHERE ono=$onoToShip");
		$removeFromOrderLine = mysql_query("DELETE FROM order_line WHERE ono=$onoToShip");
		echo "\n";
		break;
//**************************************************END SHIP ORDER**************************************************//










//**************************************************PRINT PENDING ORDER LIST**************************************************//
		case 5:
		//echo "\n";
	$pendingOrders = mysql_query("SELECT * FROM orders");			

	$amountOfcurrentOrders = 0;

	while ($amountOrders = mysql_fetch_array($pendingOrders)){
		$amountOfcurrentOrders = $amountOfcurrentOrders + 1;
	}	

	if($amountOfcurrentOrders == 0){
		echo "\nCurrently there are no pending orders.\n";
		}
		
	
	echo "\n";
	while ($row = mysql_fetch_array($pendingOrders))			
		{			
		   echo "Order Number: ".$row{'ono'}." Customer Number: ".$row{'cno'}." Employee Number: ".$row{'eno'}." Date Recieved: ".$row{'received'}." Date Shipped: ".$row{'shipped'};
		   echo "\n";		   
		}
		break;
//**************************************************END PRINT PENDING ORDER LIST**************************************************//










//**************************************************RESTOCK PARTS**************************************************//
		case 6:																				//if 6 is entered at the menu, this block case will execute
		$partToRestock = readline("\nEnter Part Number (PNO) To Restock: ");				//declare variable to hold part number

		$doesPartExistSQL = mysql_query("SELECT pno FROM parts WHERE pno=$partToRestock");	//check if the part number actually exists

		if(mysql_num_rows($doesPartExistSQL)<1){											//display error message if part number does not exist
			echo "Part (PNO): $partToRestock does not exist.\n\n";
			break;																			//break out if no part number exists
		}

		$qtyToRestock  = readline("\nEnter Quantity: ");									//declare variable to hold quantity to update

		$qohToUpdate = mysql_query("SELECT qoh FROM parts WHERE pno=$partToRestock");		//sql statement to fetch current part quantity

		$pnameToRestock = mysql_query("SELECT pno FROM parts WHERE pno=$partToRestock");	//sql statement to fetch current part number		 

		$qohRow = mysql_result($qohToUpdate, 0);											//fetch the quantity to update integer

		$pnameRow = mysql_result($pnameToRestock, 0);										//fetch the part name

		$newPartAmount = $qohRow + $qtyToRestock;											//add the current part quantity to the amount to be restocked


		$pnameToRestock2 = mysql_query("SELECT pname FROM parts WHERE pno=$partToRestock");	//sql statement to fetch part name

		$pnamePart = mysql_result($pnameToRestock2, 0);										//store part name as a variable

		if($newPartAmount < 0){																//if the new quantity is less than zero, throw an error
			echo "\nPart: '$pnamePart' Quantity Cannot Be Less Than 0. The Current Quantity is: $qohRow.\n";
			echo "\n";
				break;
			}

		echo "\nQuantity Before Restock: $qohRow. Quantity After Restock: $newPartAmount";	//display old quantity and then new quantity
		echo "\n";



		$newPartsQOH = "UPDATE parts SET qoh=$newPartAmount WHERE pno=$partToRestock";		//declare sql quantity on hand update statement			

		$qohUpdateCheck = mysql_query($newPartsQOH); 										//update the mysql database with the new quantity on hand

		if($qohUpdateCheck && $newPartAmount > 0){																			//attempt to update part quantity
			echo "\nPart (PNO):$pnameRow (Name):$pnamePart - Quantity Successfully Updated From $qohRow To $newPartAmount.\n";
		}
		else{
			echo "Part Quantity Update Unsuccessful: ". mysql_error();						//throw error message if transaction fails
		}

		echo "\n";
		break;
//**************************************************END RESTOCK PARTS**************************************************//








//**************************************************EXIT**************************************************//
		case 7:
		exit;
		break;
//**************************************************END EXIT**************************************************//

						}	

}
			
//***********************END MAIN MENU LOOP**********************//










/*
//execute the SQL query and return records
$result = mysql_query("SELECT eid, ename, age FROM emp");

//fetch the data from the database
while ($row = mysql_fetch_array($result))
{
   echo "ID:".$row{'eid'}." Name:".$row{'ename'}." 
   ".$row{'age'}."<br>";
}
*/










?>
