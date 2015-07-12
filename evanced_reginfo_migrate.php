<?php

echo '<html>
    <head>
        <title>evanced registration migrate</title>
         <script type="text/javascript">function openWin() {window.open("download_instructions.html","mywindow","width=600,height=400");}</script>
    </head>
    <body><p  style="text-align:center;">Files containing registration information must be downloaded from evanced. To learn how:<a href="#" onclick="openWin()"><button>Download Instructions</button></a></p>';


//view post variables
$nid = $_POST['nid'];
$filename = $_POST['reg_info'];

echo '<strong>nid:</strong> ' . $nid;
echo '<br />';
echo '<strong>file name:</strong> ' . $filename;
echo '<hr />';
echo 'Field data<br />';

/*
 *parse csv file to get variables
 *
 */

$reg_data = fopen($filename, 'r');
while (($reg_info = fgetcsv($reg_data/*, 5000,',', '"'*/)) !== FALSE) {
   echo 'List status: ' . $reg_info[0] . '<br />';
   echo 'First name: ' . $reg_info[1] . '<br />';
   echo 'Last name: ' . $reg_info[2] . '<br />';
   echo 'Phone: ' . $reg_info[3] . '<br />';
   echo 'Alt Phone: ' . $reg_info[4] . '<br />';
   echo 'Email: ' . $reg_info[5] . '<br />';
   echo 'Notes: ' . $reg_info[6] . '<br />';
   echo 'Reg. date: ' . $reg_info[7] . '<br />';
   echo 'Confirmation Number: ' . $reg_info[8] . '<br />';
   
   
   
   $firstname = $reg_info[1];
   $lastname = $reg_info[2];
   $phone = $reg_info[3];
   $email = $reg_info[5];
   $regdate = $reg_info[7];
   /*
    *changing registration date --$regdate -- to unix timestamp for database
    *
    *
    */
   $date = date_create_from_format('m/d/Y; h:i:s A', $regdate);
    $timestamp = $date->getTimestamp();
 /*  
   echo 'new variables: <br />';
   echo  $firstname . '<br />';
   echo  $lastname . '<br />';
   echo  $phone . '<br />';
   echo  $email . '<br />';*/
   echo  'Timestamp: ' . $timestamp . '<br />';
/*   echo '<hr />';*/
if(!$email){
    $email = null;
}


/*
 *different registration ids: $regid_regtable: query database to get latest registration_id if field is not autoincrement
 *
 */
//$regid_regtable = ;


$name = $lastname . ', ' . $firstname;
echo '<hr />';
echo 'email: ' . $email;
echo '<hr />';
/*
 *Insert values into new database
 *sql statements
 *check for auto-increment in table definitions
 */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rooms122_test";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//get latest entity_id for field_data_field_registrant_name, and field_data_field_registrant_phone fields

//$result = mysqli_query($conn, "SELECT MAX(entity_id) FROM field_data_field_registrant_name ORDER BY entity_id DESC LIMIT 1");  <--this didn't work. see below.
//YES!!! by calculating and setting the registration_id for the registration table, and assigning it as the entity_id in the fields, everything matches and name/phone fields are populated!!!
$result = mysqli_query($conn, "SELECT MAX(registration_id) FROM registration ORDER BY registration_id DESC LIMIT 1");
    //echo 'Entity id: ' . print_r($result) . '<br />';
    $entity_id_array = mysqli_fetch_all($result);
    
    foreach ($entity_id_array as $row) {
        $entity_id = $row[0];
        echo 'Old entity id: ' . $entity_id . '<br />';
         $entity_id = $entity_id+1;
   echo 'New entity id: ' . $entity_id;
    }
   
echo '<hr />';
$sql  = "insert into registration (registration_id,type,entity_id,entity_type,anon_mail,count,user_uid,author_uid,state,created,updated) VALUES ($entity_id,'meeting_signup',$nid,'node','$email',1,NULL,0,'complete','$timestamp','$timestamp');";
$sql .= "insert into field_data_field_registrant_name (entity_type,bundle,deleted,entity_id,revision_id,language,delta,field_registrant_name_value,field_registrant_name_format) VALUES ('registration','meeting_signup',0,$entity_id,$entity_id,'und',0,'$name',NULL);";
$sql .= "insert into field_data_field_registrant_phone (entity_type,bundle,deleted,entity_id,revision_id,language,delta,field_registrant_phone_value,field_registrant_phone_format) VALUES ('registration','meeting_signup',0,$entity_id,$entity_id,'und',0,'$phone',NULL);";

if (mysqli_multi_query($conn, $sql)) {
    echo "New records created successfully";
} else {
    echo "<strong>Error: " . $sql . "<br>" . mysqli_error($conn) . '</strong><hr />';
}

mysqli_close($conn);

}

echo 'registration entered.<br />';



?>
