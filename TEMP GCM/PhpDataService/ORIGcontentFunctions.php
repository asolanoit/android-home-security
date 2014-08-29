
<?php

//-----------------------------------------------------------------------
// UTILITY
//-----------------------------------------------------------------------

function getSiteTitle()
{
	global $site_title;
	return $site_title;
}

function loadFile($origFile, $newFileName, $newFilePath)
{
	if($_FILES[$origFile]["error"] > 0)
	{
		return $_FILES[$origFile]["error"];
	}

	$targetPath   = $newFilePath.$newFileName;

	if(move_uploaded_file($_FILES[$origFile]["tmp_name"], $targetPath))
	{
		return 0;
	}

	return "Caricamento del file non riuscito";
}

function getFileExt($file)
{
	$fileName = $_FILES[$file]["name"];
	$len = strlen($fileName);
	$ext = substr($fileName, $len - 4, 4);
	return $ext;
}

function getDateISO()
{
	$date = getdate();
	if((int)$date["year"] < 10) $y = "0".$date["year"]; else $y = $date["year"];
	if((int)$date["mon"]  < 10) $m = "0".$date["mon"];  else $m = $date["mon"];
	if((int)$date["mday"] < 10) $d = "0".$date["mday"]; else $d = $date["mday"];

	return $y.$m.$d;
}

function getTimeISO()
{
	$time = getdate();
	if((int)$time["hours"]   < 10) $h = "0".$time["hours"];   else $h = $time["hours"];
	if((int)$time["minutes"] < 10) $m = "0".$time["minutes"]; else $m = $time["minutes"];
	if((int)$time["seconds"] < 10) $s = "0".$time["seconds"]; else $s = $time["seconds"];

	return $h.$m.$s;
}

function dateTimeDiff($dt1, $dt2)
{
	//$dt1->format("Ymd");
	//$dt1->format("His");
	$Y1 = (int)$dt1->format("Y");
	$Y2 = (int)$dt2->format("Y");
	$M1 = (int)$dt1->format("m");
	$M2 = (int)$dt2->format("m");
	$D1 = (int)$dt1->format("d");
	$D2 = (int)$dt2->format("d");
	$h1 = (int)$dt1->format("H");
	$h2 = (int)$dt2->format("H");
	$m1 = (int)$dt1->format("i");
	$m2 = (int)$dt2->format("i");
	$s1 = (int)$dt1->format("s");
	$s2 = (int)$dt2->format("s");

	//$date1 = time();
	//$date2 = mktime(10,30,0,5,29,2010);
	//$dateDiff = $date1 - $date2;
	//$fullDays = floor($dateDiff/(60*60*24));

	$year  = $Y1 - $Y2;
	$month = $M1 - $M2;
	if($month <= 0)
	{
		$year--;
		if($month < 0)
			$month = 12 + $month;
	}

	$day   = $D1 - $D2;
	if($day <= 0)
	{
		if($month == 0)
			$month--;
		$dayNum = (int)$dt2->format("t");
		$day = $dayNum + $day;

		$monthInDays = 0;
	}

	$hour  = $h1 - $h2;
	if($hour <= 0)
	{
		$day--;
		$hour = 24 + $hour;
	}

	$min   = $m1 - $m2;
	if($min <= 0)
	{
		$hour--;
		$min = 60 + $min;
	}

	$sec   = $s1 - $s2;
	if($sec <= 0)
	{
		$min--;
		$sec = 60 + $sec;
	}

	$res = "";
	$res["year"]  = $year." anni ";
	$res["numyear"]  = $year;
	$res["month"] = $month." mese ";
	$res["nummonth"] = $month;
	$res["day"]   = $day." giorni ";
	$res["numday"]   = $day;
	$res["hour"]  = $hour." ore ";
	$res["numhour"]  = $hour;
	$res["min"]   = $min." min ";
	$res["nummin"]   = $min;
	$res["sec"]   = $sec." secondi";
	$res["numsec"]   = $sec;

	return $res;
}

function howManyYearsElapsed()
{
	global $weddingDateTime;
	$now = new DateTime();

	$wY = (int)$weddingDateTime->format("Y");
	$Y  = (int)$now->format("Y");
	$wM = (int)$weddingDateTime->format("m");
	$M  = (int)$now->format("m");
	$wD = (int)$weddingDateTime->format("d");
	$D  = (int)$now->format("d");

	if( ($Y > $wY) )
	{
		if(	($M > $wM) )
		{
			return ($Y - $wY);
		}
		else if( ($M = $wM) && ($D >= $wD) )
		{
			return ($Y - $wY);
		}
		else
		{
			return ($Y - $wY - 1);
		}
	}

	return 0;
}

// 1st anniversary
function printCountdown()
{
	$elapsedYear = howManyYearsElapsed();
	$yearStr = "anno";

	if( $elapsedYear > 0 )
	{
		if($elapsedYear > 1)
		{
			$yearStr + "anni";
		}

		print "<table width=100% align=\"right\">\n";
		print "\t<tr><td width=100% align=\"right\" class=h4>Antonio e Giusy sono marito e moglie da oltre ".$elapsedYear." ".$yearStr."</td></tr>\n";
		print "</table>\n";
	}
	else
	{
		print "<table width=100% align=\"right\">\n";
		print "\t<tr><td width=100% align=\"right\" class=h4>Antonio e Giusy sono ora marito e moglie </td></tr>\n";
		print "</table>\n";
	}
}

/* just after wedding
function printCountdown()
{
	print "<table width=100% align=\"right\">\n";
	print "\t<tr><td width=100% align=\"right\" class=h4>Antonio e Giusy sono ora marito e moglie </td></tr>\n";
	print "</table>\n";
}
*/

/* original
function printCountdown()
{
	global $weddingDateTime;
	$now = new DateTime();
	$res = dateTimeDiff($weddingDateTime, $now);

	print "<table width=100% align=\"right\">\n";

	if( $res["numyear"]  < 0 ||
		$res["nummonth"] < 0 ||
		$res["numday"]   < 0 ||
		$res["numhour"]  < 0 ||
		$res["nummin"]   < 0   )
	{
		print "\t<tr><td width=100% align=\"right\" class=h4>Antonio e Giusy sono ora marito e moglie </td></tr>\n";
	}
	else
	{
		print "\t<tr><td width=100% align=\"right\" class=h4>Mancano ";
		if($res["nummonth"] > 0)
			print "\t".$res["month"];
		if($res["numday"] > 0)
			print "\t".$res["day"];
		if($res["numhour"] > 0)
			print "\t".$res["hour"];
		if($res["nummin"] > 0)
			print "\t".$res["min"];
		print " alle nozze</td></tr>\n";
	}

	print "</table>\n";
}
*/

function formatDate($date)
{
	$arr = str_split($date, 2);
	$result = $arr[3]."-".$arr[2]."-".$arr[0].$arr[1];
	return $result;
}

function formatTime($time)
{
	$arr = str_split($time, 2);
	if($time < 10000)
	{
		$arr = str_split($time, 2);
		$result = "00:".$arr[0].":".$arr[1];
	}
	else if(strlen($time) < 6)
	{
		$h = substr($time, 0, 1);
		$time = substr($time, 1);
		$arr = str_split($time, 2);
		$result = $h.":".$arr[0].":".$arr[1];
	}
	else
	{
		$arr = str_split($time, 2);
		$result = $arr[0].":".$arr[1].":".$arr[2];
	}
	return $result;
}

function getTimeISOmsec()
{
    list($usec, $sec) = explode(" ",microtime());
    $msec = (int)($usec * 1000);
 	$time = getdate();
	if((int)$time["hours"]   < 10) $h = "0".$time["hours"];   else $h = $time["hours"];
	if((int)$time["minutes"] < 10) $m = "0".$time["minutes"]; else $m = $time["minutes"];
	if((int)$time["seconds"] < 10) $s = "0".$time["seconds"]; else $s = $time["seconds"];

	return $h.$m.$s.$msec;
}

function createSoldQuoteId()
{
	$today = getDateISO();
	$now   = getTimeISOmsec();

	return "QUOTE_".$today."_".$now;
}

function createMessageId()
{
	$today = getDateISO();
	$now   = getTimeISOmsec();
	return "MESS_".$today."_".$now;
}

function createNewsId()
{
	$today = getDateISO();
	$now   = getTimeISOmsec();
	return "NEWS_".$today."_".$now;
}

function createArticleId()
{
	$today = getDateISO();
	$now   = getTimeISOmsec();
	return "ARTCL_".$today."_".$now;
}

function createItemId()
{
	$today = getDateISO();
	$now   = getTimeISOmsec();
	return "ITEM_".$today."_".$now;
}

function createUserId($name, $last)
{
	$today = getDateISO();
	$now   = getTimeISOmsec();
	return $name."_".$last."_".$today."_".$now;
}

function createWedcodeId($name, $last)
{
	//return $name;
	$code = md5(createUserId($name, $last));
	$code = substr($code, 0, 8);
	return $code;
}

//-----------------------------------------------------------------------
// ITEM
//-----------------------------------------------------------------------

function getItem($itemId, &$itemName, &$itemDescr, &$itemPrice, &$itemQuote, &$itemNumQuote, &$itemQuoteAvailable,
				 &$itemImage, &$itemList)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    $query = "SELECT Name, Description, Price, QuoteNum, Quote, QuoteAvailable, Image, List FROM item WHERE Id = '".$itemId."';";
	$risultato = mysql_query($query) or die("Query failed: ".$query);

    $linea = mysql_fetch_array($risultato, MYSQL_ASSOC);

	$itemName		= $linea['Name'];
	$itemDescr		= $linea['Description'];
	$itemPrice		= $linea['Price'];
	$itemNumQuote	= $linea['QuoteNum'];
	$itemQuoteAvailable = $linea['QuoteAvailable'];
	$itemQuote		= $linea['Quote'];
	$itemImage		= $linea['Image'];
	$itemList		= $linea['List'];

    mysql_free_result($risultato);
    mysql_close($connessione);

    return 0;
}

function getItemByQuote($quoteId, &$itemName, &$itemPrice, &$itemQuote, &$itemImage)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	$itemId = "";
	$dummy = "";
	getQuote($quoteId, $dummy, $itemId, $dummy, $dummy, $dummy);

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    $query = "SELECT Name, Price, Quote, Image FROM item WHERE Id = '".$itemId."';";
	$risultato = mysql_query($query) or die("Query failed: ".$query);
    $linea = mysql_fetch_array($risultato, MYSQL_ASSOC);

	$itemName	= $linea['Name'];
	$itemPrice	= $linea['Price'];
	$itemQuote	= $linea['Quote'];
	$itemImage	= $linea['Image'];

    mysql_free_result($risultato);
    mysql_close($connessione);

    return 0;
}

//-----------------------------------------------------------------------

function getItemList(&$itemNum, $list, $all)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
    if($all == "0")
    {
		if($list != "")
		{
			$query = "SELECT * FROM item WHERE Approved = '1' AND List = '".$list."'";
		}
		else
		{
			$query = "SELECT * FROM item WHERE Approved = '1'";
		}
    }
    else
    {
		if($list != "")
		{
			$query = "SELECT * FROM item WHERE List = '".$list."'";
		}
		else
		{
			$query = "SELECT * FROM item";
		}
    }

	//$query = $query." ORDER BY Order ASC;";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

    $itemNum = 0;
    while ($linea = mysql_fetch_array($risultato, MYSQL_ASSOC))
    {
		$itelList[$itemNum]['Id'] 				= $linea['Id'];
		$itelList[$itemNum]['Name'] 			= $linea['Name'];
		$itelList[$itemNum]['Description'] 		= $linea['Description'];
		$itelList[$itemNum]['Price'] 			= $linea['Price'];
		$itelList[$itemNum]['QuoteNum'] 		= $linea['QuoteNum'];
		$itelList[$itemNum]['Quote'] 			= $linea['Quote'];
		$itelList[$itemNum]['QuoteAvailable'] 	= $linea['QuoteAvailable'];
		$itelList[$itemNum]['Image'] 			= $linea['Image'];
		$itelList[$itemNum]['Approved'] 		= $linea['Approved'];

		$itemNum = $itemNum + 1;
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	if($itemNum > 0)
	{
		return $itelList;
	}
	return 0;
}

function addItem($name, $image, $description, $price, $quoteNum, $approved, $list)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $ITEM_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    $itemId = createItemId();

	if($_FILES[$image]["name"] != "")
	{
		$ext = getFileExt($image);
		if($ext != ".jpg")
		{
			return "File non supportato, File supportati: '.jpg'";
		}

		$result = loadFile($image, $itemId.$ext, $ITEM_IMAGE_PATH);
		if($result != "0")
		{
			return $result;
		}

		$imagePath = $ITEM_IMAGE_PATH.$itemId.$ext;
	}
	else
	{
		$imagePath = "";
	}

    if($price != 0)    $quote = $price / $quoteNum;
    else			   $quote = 0;
	$today = getDateISO();
	$now   = getTimeISO();

	// Esecuzione query
	$query = "INSERT INTO item (`Id`,`Name`,`Description`,`Price`,`QuoteNum`,`Quote`,`QuoteAvailable`,`Approved`,`List`,`Image`) VALUES ('".
	$itemId."','".
	washSQLString($name)."','".
	washSQLString($description)."','".
	$price."','".
	$quoteNum."','".
	$quote."','".
	$quoteNum."','".
	$approved."','".
	$list."','".
	$imagePath."');";

	mysql_query($query) or die("Query failed: ".$query);
    mysql_close($connessione);

	return 0;
}

function updateItem($itemId, $name, $image, $description, $price, $quoteNum, $list, $deleteImage)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $ITEM_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	//isItemEditable()
	$query = "SELECT QuoteAvailable, QuoteNum, Price FROM item WHERE Id = '".$itemId."';";
 	$risultato = mysql_query($query) or die("Query failed: ".$query);
	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);

   	if( ($linea['QuoteAvailable'] != $linea['QuoteNum'])&&
   	    (($linea['QuoteNum'] != $quoteNum) || ($linea['Price'] != $price)) )
   	{
   		return "Prezzo e numero quote non modificabile, una o piu' quote sono gia' state acquistate";
   	}

	if($price != 0)    $quote = $price / $quoteNum;
    else			   $quote = 0;

	$imagePathChanged = 0;
	if($_FILES[$image]["name"] != "")
	{
		$imagePathChanged = 1;
		//update image
		$ext = getFileExt($image);
		if($ext != ".jpg")
		{
			return "File non supportato, File supportati: '.jpg'";
		}

		if(file_exists($ITEM_IMAGE_PATH.$itemId.".jpg"))
		{
			unlink($ITEM_IMAGE_PATH.$itemId.".jpg");
		}

		$result = loadFile($image, $itemId.$ext, $ITEM_IMAGE_PATH);
		if($result != "0")
		{
			return $result;
		}
		$imagePath = $ITEM_IMAGE_PATH.$itemId.$ext;
	}
	else if($deleteImage == "1")
	{
		$imagePathChanged = 1;
		unlink($ITEM_IMAGE_PATH.$itemId.".jpg");
		$imagePath = "";
	}

	// Esecuzione query
	if($imagePathChanged == 1)
	{
		$query = "UPDATE item SET Name = '".washSQLString($name).
			"', Description = '".washSQLString($description).
			"', Price = '".$price.
			"', QuoteNum = '".$quoteNum.
			"', QuoteAvailable = '".$quoteNum.
			"', Quote = '".$quote.
			"', List = '".$list.
			"', Image = '".$imagePath.
			"' WHERE Id = '".$itemId."';";
	}
	else
	{
		$query = "UPDATE item SET Name = '".washSQLString($name).
			"', Description = '".washSQLString($description).
			"', Price = '".$price.
			"', QuoteNum = '".$quoteNum.
			"', QuoteAvailable = '".$quoteNum.
			"', Quote = '".$quote.
			"', List = '".$list.
			"' WHERE Id = '".$itemId."';";
	}

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function approveItem($itemId, $approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE item SET Approved = ".$approved." WHERE Id = '".$itemId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function updateItemQuote($itemId, $size, $add)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	//UPDATE item
	if($add == 0)
	{
		$size = -$size;
	}
	else
	{
		$size = $size;
	}
	$query = "UPDATE item SET QuoteAvailable = QuoteAvailable + ".$size." WHERE Id = '".$itemId."' AND QuoteAvailable >= 0;";

    mysql_query($query) or die("Query failed: ".$query);
    mysql_close($connessione);

    return 0;
}

function deleteItem($itemId)
{
	//delete item and related quote
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $ITEM_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "DELETE FROM item WHERE Id = '".$itemId."';";
	mysql_query($query) or die("Query 1 failed");

	$query = "DELETE FROM quote WHERE ItemId = '".$itemId."';";
	mysql_query($query) or die("Query 2 failed");
	mysql_close($connessione);

	//delete image file
	if(file_exists($ITEM_IMAGE_PATH.$itemId.".jpg"))
	{
		unlink($ITEM_IMAGE_PATH.$itemId.".jpg");
	}

	return 0;
}

function setItemSold($pwd, $itemId, $size, $total, $mode, $message, &$userName, &$quoteId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	//CHECK user
	$userId   = "";
	$name = "";
	$last = "";
	$type = "";
	$result = checkAndGetUser($pwd, $userId, $name, $last, $type);
	if($result != "0")
	{
		return $result;
	}

	$userName = $name." ".$last;

	//create quote
	$quoteId = "";
	setQuote($userId, $itemId, $size, $total, $mode, $quoteId);

	//update item
	updateItemQuote($itemId, $size, 0);

	//Save message
	if($message != "")
	{
		$title = "";
		addMessage($title, $message, $userId, $quoteId);
	}

    return 0;
}

function getQuoteList($itemId, &$quoteNum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
    if($itemId == "")
    {
    	$query = "SELECT * FROM quote ORDER BY ItemId DESC, Date DESC, Time DESC ;";
    }
    else
    {
    	$query = "SELECT * FROM quote WHERE ItemId = '".$itemId."' ORDER BY Date DESC, Time DESC ;";
    }
    $risultato = mysql_query($query) or die("Query failed: ".$query);

    $quoteNum = 0;
    while ($linea = mysql_fetch_array($risultato, MYSQL_ASSOC))
    {
		$quoteList[$quoteNum]['Id'] 		 = $linea['Id'];
		$quoteList[$quoteNum]['UserId'] 	 = $linea['UserId'];
		$quoteList[$quoteNum]['ItemId'] 	 = $linea['ItemId'];
		$quoteList[$quoteNum]['QuoteNum'] 	 = $linea['QuoteNum'];
		$quoteList[$quoteNum]['TotalPrice']  = $linea['TotalPrice'];
		$quoteList[$quoteNum]['PaymentMode'] = $linea['PaymentMode'];
		$quoteList[$quoteNum]['Paid'] 		 = $linea['Paid'];
		$quoteList[$quoteNum]['Date'] 		 = $linea['Date'];
		$quoteList[$quoteNum]['Time'] 		 = $linea['Time'];

		$quoteNum = $quoteNum + 1;
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	if($quoteNum > 0)
	{
		return $quoteList;
	}

	return 0;
}


function getQuote($quoteId, &$userId, &$itemId, &$quoteNum, &$totalPrice, &$paymentMode)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
    $query = "SELECT * FROM quote WHERE Id = '".$quoteId."';";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);
	$userId = $linea['UserId'];
	$itemId = $linea['ItemId'];
	$quoteNum = $linea['QuoteNum'];
	$totalPrice = $linea['TotalPrice'];
	$paymentMode = $linea['PaymentMode'];

    mysql_close($connessione);
	return 0;
}

function setQuote($userId, $itemId, $size, $total, $mode, &$quoteId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	$today = getDateISO();
	$now   = getTimeISO();
	$quoteId = createSoldQuoteId();
	$paid = 0;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    //INSERT into quote
    $query = "INSERT INTO quote (`Id`,`UserId`,`ItemId`,`QuoteNum`,`TotalPrice`,`PaymentMode`,`Date`,`Time`,`Paid`) VALUES ('".
	$quoteId."','".
	$userId."','".
	$itemId."','".
	$size."','".
	$total."','".
	$mode."','".
	$today."','".
	$now."','".
	$paid."');";

	mysql_query($query) or die("Query failed: ".$query);
    mysql_close($connessione);

    return 0;
}

function setPayment($quoteId, $value)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE quote SET Paid = ".$value." WHERE Id = '".$quoteId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function deleteQuote($quoteId)
{
	//delete quote and update related item
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "SELECT ItemId, QuoteNum FROM quote WHERE Id = '".$quoteId."';";
    $risultato = mysql_query($query) or die("Query 1 sfailed");
	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);
	$itemId = $linea['ItemId'];
	$size = $linea['QuoteNum'];
	mysql_free_result($risultato);

	// Esecuzione query
	$query = "DELETE FROM quote WHERE Id = '".$quoteId."';";
	mysql_query($query) or die("Query 2 failed");
	mysql_close($connessione);

	//update item
	updateItemQuote($itemId, $size, 1);

	return 0;
}

//-----------------------------------------------------------------------
// USER
//-----------------------------------------------------------------------

function addUser($name, $last, $mail, $type, $approved, &$wedcode)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	if(washSQLString($name) == "" || washSQLString($last) == "")
	{
		return "Devi specificare almeno Nome e Cognome";
	}

	$userId = createUserId($name, $last);
	$wedcode = createWedcodeId($name, $last);
	$today = getDateISO();
	$now   = getTimeISO();

	// Esecuzione query
	$query = "INSERT INTO users (`Id`,`Name`,`Last`,`Mail`,`Password`,`Type`,`Approved`,`Date`,`Time`) VALUES ('".
	$userId."','".
	washSQLString($name)."','".
	washSQLString($last)."','".
	washSQLString($mail)."','".
	$wedcode."','".
	$type."','".
	$approved."','".
	$today."','".
	$now."');";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function approveUser($userId, $approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE users SET Approved = ".$approved." WHERE Id = '".$userId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function deleteUser($userId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "DELETE FROM users WHERE Id = '".$userId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function getUserList(&$userNum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
    $query = "SELECT * FROM users ORDER BY Last ASC, Name ASC";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

    $userNum = 0;
    while ($linea = mysql_fetch_array($risultato, MYSQL_ASSOC))
    {
		$userList[$userNum]['Id'] 				= $linea['Id'];
		$userList[$userNum]['Name'] 			= $linea['Name'];
		$userList[$userNum]['Last'] 		= $linea['Last'];
		$userList[$userNum]['Mail'] 			= $linea['Mail'];
		$userList[$userNum]['Password'] 		= $linea['Password'];
		$userList[$userNum]['Type'] 			= $linea['Type'];
		$userList[$userNum]['Approved'] 	= $linea['Approved'];

		$userNum = $userNum + 1;
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	if($userNum > 0)
	{
		return $userList;
	}
	return 0;
}

//-----------------------------------------------------------------------
// MESSAGES
//-----------------------------------------------------------------------

function deleteMessage($messageId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "DELETE FROM message WHERE Id = '".$messageId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function getMessageList(&$messageNum, $withReason, $all)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
    if($withReason == 0) //non legati
    {
    	$query = "SELECT * FROM message WHERE Reason = ''";
    	if($all == "0") $query = $query." AND Approved = '1'";
    	$query = $query." ORDER BY Date DESC, Time DESC;";
    }
    else if($withReason == 1) //legati an un regalo
    {
    	$query = "SELECT * FROM message WHERE Reason <> ''";
    	if($all == "0") $query = $query." AND Approved = '1'";
    	$query = $query." ORDER BY Reason DESC, Date DESC, Time DESC;";
    }
    else if($withReason == 2) //tutti
    {
    	$query = "SELECT * FROM message";
    	if($all == "0") $query = $query." WHERE Approved = '1'";
    	$query = $query." ORDER BY Date DESC, Time DESC;";
	}

	$risultato = mysql_query($query) or die("Query failed: ".$query);

    $messageNum = 0;
    while ($linea = mysql_fetch_array($risultato, MYSQL_ASSOC))
    {
    	$messageList[$messageNum]['Id'] 		= $linea['Id'];
		$messageList[$messageNum]['Title'] 		= $linea['Title'];
		$messageList[$messageNum]['Message'] 	= $linea['Message'];
		$messageList[$messageNum]['UserId'] 	= $linea['UserId'];
		$messageList[$messageNum]['Reason'] 	= $linea['Reason'];
		$messageList[$messageNum]['Approved'] 	= $linea['Approved'];
		$messageList[$messageNum]['Date'] 		= $linea['Date'];
		$messageList[$messageNum]['Time'] 		= $linea['Time'];

		$messageNum = $messageNum + 1;
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	if($messageNum > 0)
	{
		return $messageList;
	}
	return 0;
}

function addMessage($title, $message, $userId, $reason)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	$messageId = createMessageId();
	$today = getDateISO();
	$now   = getTimeISO();
	$approved = 1;

	// Esecuzione query
	$query = "INSERT INTO message (`Id`,`Title`,`Message`,`UserId`,`Date`,`Time`,`Reason`,`Approved`) VALUES ('".
	$messageId."','".
	washSQLString($title)."','".
	washSQLString($message)."','".
	$userId."','".
	$today."','".
	$now."','".
	$reason."','".
	$approved."');";

	mysql_query($query) or die("Query failed: ".$query);
    mysql_close($connessione);

	return 0;
}

function updateMessage($messageId, $title, $message, $reason)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE message SET Title = '".washSQLString($title).
		"', Message = '".washSQLString($message).
		"', Reason = '".$reason.
		"' WHERE Id = '".$messageId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function approveMessage($messageId, $approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE message SET Approved = ".$approved." WHERE Id = '".$messageId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function getMessage($messageId, &$title, &$message, &$userId, &$reason)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "SELECT Id, Title, Message, UserId, Reason FROM message WHERE Id = '".$messageId."';";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);
	$title	 = $linea['Title'];
	$message = $linea['Message'];
	$userId	 = $linea['UserId'];
	$reason	 = $linea['Reason'];

    mysql_free_result($risultato);
    mysql_close($connessione);

	return 0;
}

//-----------------------------------------------------------------------
// NEWS
//-----------------------------------------------------------------------

function deleteNews($newsId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $NEWS_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "DELETE FROM news WHERE Id = '".$newsId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	//delete image file
	if(file_exists($NEWS_IMAGE_PATH.$newsId.".jpg"))
	{
		unlink($NEWS_IMAGE_PATH.$newsId.".jpg");
	}

	return 0;
}

function getNewsList(&$newsNum, $all)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
    if($all == "0")
    {
    	$query = "SELECT * FROM news WHERE Approved = '1' ORDER BY Date DESC, Time DESC;";
    }
	else
	{
    	$query = "SELECT * FROM news ORDER BY Date DESC, Time DESC;";
    }

	$risultato = mysql_query($query) or die("Query failed: ".$query);

    $newsNum = 0;
    while ($linea = mysql_fetch_array($risultato, MYSQL_ASSOC))
    {
    	$newList[$newsNum]['Id'] 		= $linea['Id'];
		$newList[$newsNum]['Title'] 	= $linea['Title'];
		$newList[$newsNum]['Text'] 		= $linea['Text'];
		$newList[$newsNum]['Image'] 	= $linea['Image'];
		$newList[$newsNum]['UserId'] 	= $linea['UserId'];
		$newList[$newsNum]['Approved'] 	= $linea['Approved'];
		$newList[$newsNum]['Date'] 		= $linea['Date'];
		$newList[$newsNum]['Time'] 		= $linea['Time'];

		$newsNum = $newsNum + 1;
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	if($newsNum > 0)
	{
		return $newList;
	}
	return 0;
}

function addNews($title, $text, $image, $userId, $approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $NEWS_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	$newsId = createNewsId();

	if($_FILES[$image]["name"] != "")
	{
		$ext = getFileExt($image);
		if($ext != ".jpg")
		{
			return "File non supportato, File supportati: '.jpg'";
		}

		$result = loadFile($image, $newsId.$ext, $NEWS_IMAGE_PATH);
		if($result != "0")
		{
			return $result;
		}
		$imagePath = $NEWS_IMAGE_PATH.$newsId.$ext;
	}
	else
	{
		$imagePath = "";
	}

	$today = getDateISO();
	$now   = getTimeISO();

	// Esecuzione query
	$query = "INSERT INTO news (`Id`,`Title`,`Text`,`Image`,`UserId`,`Approved`,`Date`,`Time`) VALUES ('".
	$newsId."','".
	washSQLString($title)."','".
	washSQLString($text)."','".
	$imagePath."','".
	$userId."','".
	$approved."','".
	$today."','".
	$now."');";

	mysql_query($query) or die("Query failed: ".$query);
    mysql_close($connessione);

	return 0;
}

function getNews($newsId, &$title, &$text, &$image, &$userId, &$approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "SELECT * FROM news WHERE Id = '".$newsId."';";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);
	$title	  = $linea['Title'];
	$text 	  = $linea['Text'];
	$image 	  = $linea['Image'];
	$userId	  = $linea['UserId'];
	$approved = $linea['Approved'];

    mysql_free_result($risultato);
    mysql_close($connessione);

	return 0;
}

function updateNews($newsId, $title, $image, $text, $approved, $deleteImage)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $NEWS_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	$imagePathChanged = 0;
	if($_FILES[$image]["name"] != "")
	{
		$imagePathChanged = 1;
		$ext = getFileExt($image);
		if($ext != ".jpg")
		{
			return "File non supportato, File supportati: '.jpg'";
		}

		if(file_exists($NEWS_IMAGE_PATH.$newsId.".jpg"))
		{
			unlink($NEWS_IMAGE_PATH.$newsId.".jpg");
		}

		$result = loadFile($image, $newsId.$ext, $NEWS_IMAGE_PATH);
		if($result != "0")
		{
			return $result;
		}

		$imagePath = $NEWS_IMAGE_PATH.$newsId.$ext;
	}
	else if($deleteImage == "1")
	{
		$imagePathChanged = 1;
		unlink($NEWS_IMAGE_PATH.$newsId.".jpg");
		$imagePath = "";
	}

	// Esecuzione query
	if($imagePathChanged == 1)
	{
		$query = "UPDATE news SET Title = '".washSQLString($title).
		"', Text = '".washSQLString($text).
		"', Approved = '".$approved.
		"', Image = '".$imagePath.
		"' WHERE Id = '".$newsId."';";
	}
	else
	{
		$query = "UPDATE news SET Title = '".washSQLString($title).
			"', Text = '".washSQLString($text).
			"', Approved = '".$approved.
			"' WHERE Id = '".$newsId."';";
	}

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function approveNews($newsId, $approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE news SET Approved = ".$approved." WHERE Id = '".$newsId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

//-----------------------------------------------------------------------
// ARTICLE
//-----------------------------------------------------------------------

function getArticleList(&$articleNum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
   	$query = "SELECT * FROM article ORDER BY Date DESC, Time DESC;";

	$risultato = mysql_query($query) or die("Query failed: ".$query);

    $articleNum = 0;
    while ($linea = mysql_fetch_array($risultato, MYSQL_ASSOC))
    {
    	$articleList[$articleNum]['Id'] 		= $linea['Id'];
		$articleList[$articleNum]['Title'] 	= $linea['Title'];
		$articleList[$articleNum]['Text'] 		= $linea['Text'];
		$articleList[$articleNum]['Image'] 	= $linea['Image'];
		$articleList[$articleNum]['UserId'] 	= $linea['UserId'];
		$articleList[$articleNum]['Approved'] 	= $linea['Approved'];
		$articleList[$articleNum]['Date'] 		= $linea['Date'];
		$articleList[$articleNum]['Time'] 		= $linea['Time'];

		$articleNum = $articleNum + 1;
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	if($articleNum > 0)
	{
		return $articleList;
	}
	return 0;
}

function addArticle($id, $title, $text, $image, $userId, $approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $ARTICLE_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	if($_FILES[$image]["name"] != "")
	{
		$ext = getFileExt($image);
		if($ext != ".jpg")
		{
			return "File non supportato, File supportati: '.jpg'";
		}

		$result = loadFile($image, $id.$ext, $ARTICLE_IMAGE_PATH);
		if($result != "0")
		{
			return $result;
		}
		$imagePath = $ARTICLE_IMAGE_PATH.$id.$ext;
	}
	else
	{
		$imagePath = "";
	}

	$today = getDateISO();
	$now   = getTimeISO();
	if($title == "")
	{
		$title = $id;
	}

	// Esecuzione query
	$query = "INSERT INTO article (`Id`,`Title`,`Text`,`Image`,`UserId`,`Approved`,`Date`,`Time`) VALUES ('".
	$id."','".
	washSQLString($title)."','".
	washSQLString($text)."','".
	$imagePath."','".
	$userId."','".
	$approved."','".
	$today."','".
	$now."');";

	mysql_query($query) or die("Query failed: ".$query);
    mysql_close($connessione);

	return 0;
}

function getArticle($articleId, &$title, &$text, &$image, &$userId, &$approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "SELECT * FROM article WHERE Id = '".$articleId."';";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);
	$title	  = $linea['Title'];
	$text 	  = $linea['Text'];
	$image 	  = $linea['Image'];
	$userId	  = $linea['UserId'];
	$approved = $linea['Approved'];

    mysql_free_result($risultato);
    mysql_close($connessione);

	return 0;
}

function updateArticle($articleId, $title, $image, $text, $approved, $deleteImage)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $ARTICLE_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	$imagePathChanged = 0;
	if($_FILES[$image]["name"] != "")
	{
		$imagePathChanged = 1;
		$ext = getFileExt($image);
		if($ext != ".jpg")
		{
			return "File non supportato, File supportati: '.jpg'";
		}

		if(file_exists($ARTICLE_IMAGE_PATH.$articleId.".jpg"))
		{
			unlink($ARTICLE_IMAGE_PATH.$articleId.".jpg");
		}

		$result = loadFile($image, $articleId.$ext, $ARTICLE_IMAGE_PATH);
		if($result != "0")
		{
			return $result;
		}

		$imagePath = $ARTICLE_IMAGE_PATH.$articleId.$ext;
	}
	else if($deleteImage == "1")
	{
		$imagePathChanged = 1;
		unlink($ARTICLE_IMAGE_PATH.$articleId.".jpg");
		$imagePath = "";
	}

	// Esecuzione query
	if($imagePathChanged == 1)
	{
		$query = "UPDATE article SET Title = '".washSQLString($title).
			"', Text = '".washSQLString($text).
			"', Approved = '".$approved.
			"', Image = '".$imagePath.
			"' WHERE Id = '".$articleId."';";
	}
	else
	{
		$query = "UPDATE article SET Title = '".washSQLString($title).
			"', Text = '".washSQLString($text).
			"', Approved = '".$approved.
			"' WHERE Id = '".$articleId."';";
	}

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function approveArticle($articleId, $approved)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE article SET Approved = ".$approved." WHERE Id = '".$articleId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function deleteArticle($articleId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;
	global $ARTICLE_IMAGE_PATH;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "DELETE FROM article WHERE Id = '".$articleId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	//delete image file
	if(file_exists($ARTICLE_IMAGE_PATH.$articleId.".jpg"))
	{
		unlink($ARTICLE_IMAGE_PATH.$articleId.".jpg");
	}

	return 0;
}

function printArticle($articleId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "SELECT * FROM article WHERE Id = '".$articleId."';";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);
	$title	  = $linea['Title'];
	$text 	  = $linea['Text'];
	$image 	  = $linea['Image'];
	$userId	  = $linea['UserId'];
	$approved = $linea['Approved'];

	if($text == "")
	{
		print "<p>";
		print "<table width=800px>\n";
		print "\t<tr><td width=100%>Articolo ".$articleId." non trovato!!!</td></tr>\n";
		print "</table>\n";
		print "<p>";
	}
	else
	{
/*		if($linea['Title'] != "" && $linea['Title'] != $articleId)
		{
			print "<table>\n";
			print "\t<tr><td width=100% class=h2>".$linea['Title']."</td></tr>\n";
			if($linea['Image'] != "")
				print "\t<tr><td width=100%><img src=".$linea['Image']."></td></tr>\n";
			if($linea['Text'] != "")
				print "\t<tr><td width=100%>".$linea['Text']."</td></tr>\n";
			print "\t<tr>";
			print "\t</td></tr>\n";
			print "</table>\n";
		}
		else
		{
			if($linea['Text'] != "")
				print "\n<br>\n".$linea['Text'];
		}
*/
		print "<div id=\"article\">\n";
		if($linea['Title'] != "" && $linea['Title'] != $articleId)
			print "\t<p class=h2>".$linea['Title']."\n";
		if($linea['Image'] != "")
			print "\t<p><img src=".$linea['Image'].">\n";
		if($linea['Text'] != "")
			print "\t<p align=\"justify\">".$linea['Text']."\n";
		print "</div>\n";
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	return 0;
}

//-----------------------------------------------------------------------
// PAGES
//-----------------------------------------------------------------------

function getPageList(&$pageNum)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

    mysql_select_db($DBMS_BD_NAME) or die("DB not found");

    // Esecuzione query
   	$query = "SELECT * FROM pages;";

	$risultato = mysql_query($query) or die("Query failed: ".$query);

    $pageNum = 0;
    while ($linea = mysql_fetch_array($risultato, MYSQL_ASSOC))
    {
    	$pageList[$pageNum]['Id'] 				= $linea['Id'];
		$pageList[$pageNum]['Permission'] 		= $linea['Permission'];
		$pageList[$pageNum]['DefaultArticle'] 	= $linea['DefaultArticle'];
		$pageList[$pageNum]['Visit'] 			= $linea['Visit'];

		$pageNum = $pageNum + 1;
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	if($pageNum > 0)
	{
		return $pageList;
	}
	return 0;
}

function resetPageNum($pageId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE pages SET Visit = 0 WHERE Id = '".$pageId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function deletePage($pageId)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "DELETE FROM pages WHERE Id = '".$pageId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function addPage($pageId, $visitNum, $article, $permission)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "INSERT INTO pages (`Id`,`Permission`,`DefaultArticle`,`Visit`) VALUES ('".
	washSQLString($pageId)."','".
	$permission."','".
	washSQLString($article)."','".
	$visitNum."');";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function updatePage($pageId, $permission, $article)
{
	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");

	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "UPDATE pages SET Permission = '".$permission."', DefaultArticle = '".$article."' WHERE Id = '".$pageId."';";

	mysql_query($query) or die("Query failed: ".$query);
	mysql_close($connessione);

	return 0;
}

function getPageSetting($pageId, $increaseVisit)
{
	$result = "";
	$result["visibility"] = 1;	//1: admin - 0: all
	$result["article"] = "";


	global $DBMS_HOST;
	global $DBMS_USER;
	global $DBMS_PWD;
	global $DBMS_BD_NAME;

	// Connessione e selezione del database
	$connessione = mysql_connect($DBMS_HOST, $DBMS_USER, $DBMS_PWD) or die("DB Connection failed");
	mysql_select_db($DBMS_BD_NAME) or die("DB not found");

	// Esecuzione query
	$query = "SELECT * FROM pages WHERE Id = '".$pageId."';";
    $risultato = mysql_query($query) or die("Query failed: ".$query);

	//Permissio 0: all - 1: admin only - 2 noone
	$result = "";
	$linea = mysql_fetch_array($risultato, MYSQL_ASSOC);
	$result["Id"] = $linea['Id'];
	$result["Permission"] = $linea['Permission'];
	$result["Article"] = $linea['DefaultArticle'];
	$result["Visit"] = $linea['Visit'];

	if($increaseVisit == "1")
	{
		$query = "UPDATE pages SET Visit = Visit + 1 WHERE Id = '".$pageId."';";
		mysql_query($query) or die("Query failed: ".$query);
	}

    mysql_free_result($risultato);
    mysql_close($connessione);

	return $result;
}

//-----------------------------------------------------------------------

?>
