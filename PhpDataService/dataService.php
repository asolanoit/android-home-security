
<?php
	include 'settings.php';


//-----------------------------------------------------------------------
// Error managment
// functions must return
//		0:<message> for sucess
//		<n>:<message> for errors
//
//		    1: SQL error
//			2: Php error
//		10-12: User login errors
//		20-20: Data retrieve errors
//	   100- *: Silverlight application error
//
//-----------------------------------------------------------------------

function userLogin($id, $pwd)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	$query = "SELECT Id,Name,Last,Password,Description,Type,LastUpdBy,LastUpdTime,LastUpdDate FROM Users WHERE Id = '".$id."';";

	$result = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	$recordNum = 0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$tableArray[] = array(	"Id" => $line['Id'],
								"Name" => $line['Name'],
								"Last" => $line['Last'],
								"Password" => $line['Password'],
								"Description" => $line['Description'],
								"Type" => $line['Type'],
								"LastUpdBy" => $line['LastUpdBy'],
								"LastUpdTime" => $line['LastUpdTime'],
								"LastUpdDate" => $line['LastUpdDate']);
		$recordNum = $recordNum + 1;
	}

	mysql_close($connection);

	if($recordNum == 1)
	{
		$password = $tableArray[0]['Password'];
		if($password == $pwd)
		{
			$checksum = md5($id."@".$pwd);
			$err = isUserLogged($checksum);
			if($err != "0:Ok")
			{
				$err = setUserLogged($id, $pwd, 1);
				if($err != "0:Ok")
				{
					return $err;
				}
			}

			$returnItems = array( "ReturnType" => "DATA", "TableData" => $tableArray);
			$JSONResult = json_encode($returnItems);

			return "0:".$JSONResult;
		}
		else
		{
			return "10:Invalid Id or Password {Id: ".$id."} {Pwd: ".$pwd."}";
		}
	}
	else
	{
		return "11: Invalid Id or Password more than one user matched {Id: ".$id."} {Pwd: ".$pwd."} {RecNum: ".$recordNum."}";
	}
}

function userLogout($id, $pwd)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	$checksum = md5($id."@".$pwd);
	$err = isUserLogged($checksum);
	if($err != "0:Ok")
	{
		return $err;
	}

	$err = setUserLogged($id, $pwd, 0);
	if($err != "0:Ok")
	{
		return $err;
	}

	return "0:Ok";
}

function setUserLogged($id, $pwd, $login)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	if($login == 1)
	{
		$checksum = md5($id."@".$pwd);
		$query = "INSERT INTO UserLogged (`UserId`,`Checksum`) VALUES ('".$id."','".$checksum."');";
		mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");
	}
	else
	{
		$query = "DELETE FROM UserLogged WHERE UserId = '".$id."';";
		mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");
	}

	mysql_close($connection);

	return "0:Ok";
}

function isUserLogged($checksum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	$query = "SELECT UserId FROM UserLogged WHERE Checksum = '".$checksum."';";
	$result = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	$recordNum = 0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
    {
		$recordNum = $recordNum + 1;
	}

	mysql_close($connection);

	if($recordNum != 1)
	{
		return "12: User not logged Checksum {".$checksum."}";
	}

	return "0:Ok";
}

//-----------------------------------------------------------------------

function selectData($table, $fields, $whereFields, $whereValues, $whereCondition, $checksum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	//check if user is logged
	$err = isUserLogged($checksum);
	if($err != "0:Ok")
	{
		return $err;
	}

	// Connessione e selezione del database
	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	$query = "SELECT ".$fields." FROM ".$table;
   	if($whereFields != "" && $whereValues != "" )
   	{
   		$fieldArray = explode(",", $whereFields);
		$valueArray = explode("||", $whereValues);
		$condArray = explode(",", $whereCondition);

   		$query = $query." WHERE ";

   		$pos = 0;
		foreach ($fieldArray as $wfield)
		{
			//$query = $query.$wfield." = '";
			$query = $query.$wfield." ".$condArray[$pos]." '";
			$query = $query.$valueArray[$pos]."' AND ";
			$pos = $pos + 1;
		}
		$query = rtrim($query, "AND ");
   	}

   	$query = $query.";";

	$result = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	$fieldArray = explode(",", $fields);

	$recordNum = 0;
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
    {
    	unset($tmp);
    	foreach ($fieldArray as $field)
    	{
    		$tmp[$field] = $line[$field];
    	}

		$tableArray[$recordNum] = $tmp;

		$recordNum = $recordNum + 1;
	}

	mysql_close($connection);

	if($recordNum != 0)
	{
		$returnItems = array( "ReturnType" => "DATA", "TableData" => $tableArray);
		$JSONResult = json_encode($returnItems);

		return "0:".$JSONResult;
	}
	else
	{
		return "20:No data found for query {Query: ".$query."}";
	}
}

function insertData($table, $fields, $values, $checksum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	//check if user is logged
	$err = isUserLogged($checksum);
	if($err != "0:Ok")
	{
		return $err;
	}

	// Connessione e selezione del database
	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	$fieldArray = explode(",", $fields);
	$valueArray = explode("||", $values);

	$query = "INSERT INTO ".$table." (";
	foreach ($fieldArray as $field)
	{
		$query = $query."`".$field."`,";
	}
	$query = rtrim($query, ",");
	$query = $query .") VALUES (";

	foreach ($valueArray as $value)
	{
		$query = $query."'".$value."',";
	}
	$query = rtrim($query, ",");
	$query = $query .");";

	$result = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	mysql_close($connection);

	return "0:Ok: ".$result;
}

function deleteData($table, $whereFields, $whereValues, $checksum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	//check if user is logged
	$err = isUserLogged($checksum);
	if($err != "0:Ok")
	{
		return $err;
	}

	// Connessione e selezione del database
	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	$fieldArray = explode(",", $whereFields);
	$valueArray = explode("||", $whereValues);

	$query = "DELETE FROM ".$table." WHERE ";
	$pos = 0;
	foreach ($fieldArray as $field)
	{
		$query = $query.$field." = '";
		$query = $query.$valueArray[$pos]."' AND ";
		$pos = $pos + 1;
	}
	$query = rtrim($query, "AND ");
	$query = $query.";";

	$result = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	mysql_close($connection);

	return "0:Ok:".$result;
}

//-----------------------------------------------------------------------

function updateData($table, $fields, $values, $whereFields, $whereValues, $whereCondition, $valuesRelative, $checksum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	//check if user is logged
	$err = isUserLogged($checksum);
	if($err != "0:Ok")
	{
		return $err;
	}

	// Connessione e selezione del database
	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	$fieldArray = explode(",", $fields);
	$valueArray = explode("||", $values);

	$whereFieldArray = explode(",", $whereFields);
	$whereValueArray = explode("||", $whereValues);
	$condArray = explode(",", $whereCondition);

	$query = "UPDATE ".$table." SET ";
	$pos = 0;
	foreach ($fieldArray as $field)
	{
		if($valuesRelative == "+" || $valuesRelative == "-")
		{
			$query = $query.$field." = ".$field." ".$valuesRelative." '";
			$query = $query.$valueArray[$pos]."', ";
		}
		else
		{
			$query = $query.$field." = '";
			$query = $query.$valueArray[$pos]."', ";
		}
		$pos = $pos + 1;
	}
	$query = rtrim($query, ", ");

	$query = $query." WHERE ";

	$pos = 0;
	foreach ($whereFieldArray as $wfield)
	{
		$query = $query.$wfield." ".$condArray[$pos]." '";
		$query = $query.$whereValueArray[$pos]."' AND ";
		$pos = $pos + 1;
	}
	$query = rtrim($query, "AND ");
	$query = $query.";";

	$result = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	mysql_close($connection);

	return "0:Ok:".$result;
}

/*
function downloadProduct($id, $productType, $productId, $loadType, $warehouse, $qty, $qtyType, $priceTotal, $lastUpdBy, $lastUpdTime, $lastUpdDate, $checksum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	//check if user is logged
	$err = isUserLogged($checksum);
	if($err != "0:Ok")
	{
		return $err;
	}

	// Connessione e selezione del database
	$connection = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("1:SQL Error: DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("1:SQL Error: DB not found");

	$query = "INSERT INTO DownloadRegister (`Id`,`ProductType`,`ProductId`,`LoadType`,`Warehouse`,`Qty`,`QtyType`,`PriceTotal`,`LastUpdBy`,`LastUpdTime`,`LastUpdDate`) VALUES ('"
		.$id."','"
		.$productType."','"
		.$productId."','"
		.$loadType."','"
		.$warehouse."','"
		.$qty."','"
		.$qtyType."','"
		.$priceTotal."','"
		.$lastUpdBy."','"
		.$lastUpdTime."','"
		.$lastUpdDate."');";

	$result1 = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	$query = "UPDATE Products SET Qty = Qty - '".$qty."' WHERE Id = '".$productId."';";
	$result2 = mysql_query($query) or die("1:SQL Error: Query failed {Query: ".$query."}");

	mysql_close($connection);
	return "0:Ok: ".$result1." ".$result2;
}
*/

//-----------------------------------------------------------------------
function sendPushNotification2($ids, $message)
{
	global $GOOGLE_API_KEY;

	$url = 'https://android.googleapis.com/gcm/send';

	$fields = array('registration_ids'  => $ids,
					'data'=> $message
				);

	$headers = array('Authorization: key=' . $GOOGLE_API_KEY,'Content-Type: application/json');

	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'=> $headers ,
			'method'  => 'POST',
			'content' => json_encode($fields),
		),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	print "Result: ".$result;
}

function sendPushNotification($registatoin_ids, $message)
{
	global $GOOGLE_API_KEY;

	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';

	$fields = array(
		'registration_ids' => $registatoin_ids,
		'data' => $message,
	);

	$headers = array(
		'Authorization: key=' . GOOGLE_API_KEY,
		'Content-Type: application/json'
	);

	print $headers ."\\n";
	// Open connection
	$ch = curl_init();

	// Set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Disabling SSL Certificate support temporarly
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

	// Execute post
	print $ch."\\n";
	$result = curl_exec($ch);
	print $result."\\n";
	if ($result == FALSE) {
		die('Curl failed: ' . curl_error($ch));
	}

	// Close connection
	curl_close($ch);
}

//-----------------------------------------------------------------------
//
//-General data managment functions--------------------------------------
//

if(isset($_GET["phpinfo"]))
{
	phpinfo();
}

if(isset($_GET["select"]))
{
	if(	isset($_GET["table"]) 		&&
		isset($_GET["fields"])		&&
		isset($_GET["checksum"])	)
	{
		if(	isset($_GET["whereFields"]) 	&&
			isset($_GET["whereValues"])	)

		{
			if(	isset($_GET["whereConditions"]))
			{
				print selectData($table, $fields, $whereFields, $whereValues, $whereConditions, $checksum);
			}
		}
		else
		{
			print selectData($table, $fields, "", "", "", $checksum);
		}
	}
}

if(isset($_GET["insert"]))
{
	if(	isset($_GET["table"]) 		&&
		isset($_GET["fields"])		&&
		isset($_GET["values"])		&&
		isset($_GET["checksum"]) 	)
	{
		print insertData($table, $fields, $values, $checksum);
	}
}

if(isset($_GET["delete"]))
{
	if(	isset($_GET["table"]) 		&&
		isset($_GET["fields"])		&&
		isset($_GET["values"])		&&
		isset($_GET["checksum"]) 	)
	{
		print deleteData($table, $fields, $values, $checksum);
	}
}

if(isset($_GET["update"]))
{
	if(	isset($_GET["table"]) 			&&
		isset($_GET["fields"])			&&
		isset($_GET["values"])			&&
		isset($_GET["whereFields"])		&&
		isset($_GET["whereValues"])		&&
		isset($_GET["whereConditions"])	&&
		isset($_GET["valuesRelative"])	&&
		isset($_GET["checksum"]) 	)
	{
		print updateData($table, $fields, $values, $whereFields, $whereValues, $whereConditions, $valuesRelative, $checksum);
	}
}

//
//-Login functions--------------------------------------
//

if(isset($_GET["login"]))
{
	if(	isset($_GET["id"])	&&
		isset($_GET["pwd"])		)
	{
		print userLogin($id, $pwd);
	}
}

if(isset($_GET["logout"]))
{
	if(	isset($_GET["id"])	&&
		isset($_GET["pwd"])		)
	{
		print userLogout($id, $pwd);
	}
}

//
//-Specific application functions--------------------------------------
//

if(isset($_GET["sendPushNotification"]))
{
    //require_once './purl/Purl.php';

    if(	isset($_GET["regId"]) &&
    	isset($_GET["message"])	)
   	{
   		$regId = $_GET["regId"];
   		$message = $_GET["message"];
        $registationIdsA = array($regId);
        $messageA = array("data" => $message);

	    print "sendPushNotification RegId: ".$regId." Msg: ".$message;
        $result = sendPushNotification2($registationIdsA, $messageA);
        print " Result ".$result;
    }
}

if(isset($_GET["registerPeer"]))
{

}

if(isset($_GET["sendLogMessage"]))
{

}

if(isset($_GET["sendLogMessage"]))
{

}

//-----------------------------------------------------------------------

?>
