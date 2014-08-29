<?php

	$DBMS_HOST = "62.149.150.86";
	$DBMS_USER = "Sql324536";
	$DBMS_PWD  = "471379c7";
	$DBMS_BD_NAME = "Sql324536_5";

	$GOOGLE_API_KEY = "AIzaSyCk3UaxRyZykUZbFWIY7Owwp5VlsyOJcVE";


//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php
//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?insert&table=Users&fields=Id,Name,Last,Password,Type,LastUpdBy,LastUpdTime,LastUpdDate&values=AAA_BBB,aaa,bbb,123,1,null,0,0
//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?select&table=Users&fields=Id,Name
//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?select&table=Users&fields=Id,Name&whereFields=Id&whereValues=Antonio
//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?delete&table=Users&fields=Id,Name&values=AAA_CCC,aaa

//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?login&id=ANTONIOSOLANO&pwd=4a181673429f0b6abbfd452f0f3b5950
//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?logout&id=ANTONIOSOLANO&pwd=4a181673429f0b6abbfd452f0f3b5950
//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?downloadProduct&id=123&productType=1&productId=123456&loadType=1&warehouse=Warehouse&qty=2&qtyType=1&priceTotal=5&lastUpdBy=AAA&lastUpdTime=132&lastUpdDate=132&checksum=9620fe059e95c3d31d88c22ba66470c9
//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?update&table=Products&fields=Qty&values=13&whereFields=Id&whereValues=123456&whereCondition==&checksum=9620fe059e95c3d31d88c22ba66470c9

//	http://www.antonioegiusy.com/GCMApplicationTest/dataService.php?sendPushNotification&redId=aaa&message=bbb

//	--
//	-- Struttura della tabella `gcm_users`
//	--
//
//	CREATE TABLE IF NOT EXISTS `gcm_users` (
//	`gcmRegId` text NOT NULL,
//	  `email` text NOT NULL,
//	`context` text NOT NULL,
//	  `name` text NOT NULL,
//	`id` varchar(256) NOT NULL,
//	  PRIMARY KEY  (`id`)
//	) ENGINE=MyISAM DEFAULT CHARSET=latin1;
//

?>