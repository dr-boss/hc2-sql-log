<?php
$dbuser = "user"; 	//user for access database for store logs of your's HC2, 
						//need privilage to CREATE TABLE in databases defined in $dbname and created manualy
$dbpass = "password";		//password for user
$dbname = "fibaro";		//databases name, need created manualy for security resons, the table for all HC2 
						//will be created on first HC2 connection

$con=mysqli_connect("localhost",$dbuser,$dbpass,$dbname);
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if(isset($_GET['id']) && !empty($_GET['id'])){
$hcid = $_GET['id'];
//echo "Export: ".$hcid;
// --------------------
// https://gist.github.com/apocratus/936404

/* vars for export */
// database record to be exported
$db_record = $hcid;
// optional where query
$where = 'WHERE 1 ORDER BY 1';
// filename for export
$csv_filename = 'db_export_'.$db_record.'_'.date('Y-m-d').'.csv';
// database variables
$hostname = "localhost";
$user = $dbuser;
$password = $dbpass;
$database = $dbname;
// Database connecten voor alle services
mysql_connect($hostname, $user, $password)
or die('Could not connect: ' . mysql_error());
		    
mysql_select_db($database)
or die ('Could not select database ' . mysql_error());
// create empty variable to be filled with export data
$csv_export = '';
// query to get data from database
$query = mysql_query("SELECT * FROM ".$db_record." ".$where);
$field = mysql_num_fields($query);
// create line with field names
for($i = 0; $i < $field; $i++) {
  $csv_export.= mysql_field_name($query,$i).';';
}
// newline (seems to work both on Linux & Windows servers)
$csv_export.= '
';
// loop through database query and fill export variable
while($row = mysql_fetch_array($query)) {
  // create line with field values
  for($i = 0; $i < $field; $i++) {
    $csv_export.= '"'.$row[mysql_field_name($query,$i)].'";';
  }	
  $csv_export.= '
';	
}
// Export the data and prompt a csv file for download
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=".$csv_filename."");
//header("Content-Disposition: attachment; filename=filename.csv");
echo($csv_export);

// --------------------
} else {
    echo "Only called by fibaro_log.php";

}


mysqli_close($con);
?>