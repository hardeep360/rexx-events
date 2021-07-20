<?php
define('CONFIG_INCLUDE', 1 );
require_once 'config.php';
 $strData = file_get_contents( './data-source/event-data.json' );

 $arrData = json_decode( $strData );
 $tableName = 'event_booking';
 $isError = false;
 $msg = '';

 function filter_html( $value )
 {
     return strip_tags( $value );
 }


 if( is_array($arrData) && count($arrData) > 0  )
 {
     $queryInsert = ' insert into '.$tableName.' ( participation_id, employee_name, employee_mail, event_id, event_name, participation_fee, event_date, version ) values ';
     $arrInsertValues = array();
     foreach ( $arrData as $key => $val )
     {
         $arrVals = (array) $val;
         $queryInsert .= ' ( ?, ?, ?, ?, ?, ?, ?, ? ),';
         $arrInsertValues[] = $arrVals['participation_id'];
         $arrInsertValues[] = filter_html($arrVals['employee_name']);
         $arrInsertValues[] = filter_html($arrVals['employee_mail']);
         $arrInsertValues[] = $arrVals['event_id'];
         $arrInsertValues[] = filter_html( $arrVals['event_name'] );
         $arrInsertValues[] = filter_html( $arrVals['participation_fee'] );
         $arrInsertValues[] = filter_html($arrVals['event_date']);
         $arrInsertValues[] = filter_html($arrVals['version']);
     }

       $queryInsert = rtrim($queryInsert,',');

     try{

         $stmt = $db->prepare( $queryInsert );
         $stmt->execute( $arrInsertValues );
         $msg = 'Data successfully inserted.';

     }
     catch (Exception $e )
     {
        $isError = true;
        $msg = $e->getMessage();
     }

 }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insert Data DB</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<?php include_once 'message.php'; ?>

</body>
</html>
