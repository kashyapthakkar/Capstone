<?php

$DB['server'] = 'localhost';                                                    //Server
$DB['user'] = '';                                                      //username
$DB['password'] = '';                                                   //password
$DB['db'] = '';                                                        //database name

try
{

  // connect to database
  $conn = new PDO("mysql:host=".$DB['server'].";dbname=".$DB['db'],
	              $DB['user'],
				  $DB['password']);

  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // have my fetch data returned as an associative array
  $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

}
catch(PDOException $e)                                                          //thows an exception if anything goes wrong with database
{
  echo "Connection failed: " . $e->getMessage();
  exit();
}
