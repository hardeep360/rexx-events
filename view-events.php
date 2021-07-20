<?php
define('CONFIG_INCLUDE', 1 );
require_once 'config.php';
require_once 'classes/VersionCompare.php';
$tableName = 'event_booking';
$isError = false;
$msg = '';
$dataRows = array();
$appQuery = '';
$bindParams = array();
if( isset($_GET['search']))
{
    if( '' != trim($_GET['empname']) )
    {
        $appQuery = ' employee_name = ? and';
        $bindParams[] = strip_tags(trim($_GET['empname']));
    }

    if( '' != trim($_GET['eventname']) )
    {
        $appQuery .= ' event_name = ? and';
        $bindParams[] = strip_tags(trim($_GET['eventname']));
    }

    if( '' != trim($_GET['eventdate']) )
    {
        $appQuery .= ' date(event_date) = ? ';
        $eventDtArr = explode( '/', strip_tags( trim($_GET['eventdate'] ) ) );
        $bindParams[] = ( $eventDtArr[2].'-'.$eventDtArr['1'].'-'.$eventDtArr[0] );
    }

    if( $appQuery != '' ) {
       $appQuery = ' where '. rtrim($appQuery, 'and');
    }
}

try {

    $stmt = $db->prepare(' select participation_id, employee_name, employee_mail, event_id, event_name, participation_fee, event_date, version from ' . $tableName.' '.$appQuery );
    $stmt->execute($bindParams);
    $dataRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e )
{
    $isError = true;
    $msg = $e->getMessage();
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>View Events</h1>
<?php include_once 'message.php'; ?>

<h2>Search Filters</h2>
<form method="get" action="">
<table>

    <tr>
        <td>
            Employee Name<br />
            <input type="text" name="empname" value="<?php echo isset($_GET['empname']) ? htmlentities(strip_tags($_GET['empname'])) : ''; ?>">
        </td>
        <td>
            Event Name<br />
            <input type="text" name="eventname" value="<?php echo isset($_GET['eventname']) ? htmlentities(strip_tags($_GET['eventname'])) : ''; ?>">
        </td>
        <td>
            Event Date (dd/mm/yyyy)<br />
            <input type="text" name="eventdate" value="<?php echo isset($_GET['eventdate']) ? htmlentities(strip_tags($_GET['eventdate'])) : ''; ?>">
        </td>

        <td>
            <br />
            <input type="submit" name="search" value="Search">
        </td>
    </tr>
</table>
</form>
<br /><br />

<table class="event-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
    <th>ID</th>
    <th>Employee Name</th>
    <th>Employee Email</th>
    <th>Event Id</th>
    <th>Event Name</th>
    <th>Participation Fee</th>
    <th>Event Date</th>
    <th>Version</th>
</tr>

</thead>

    <?php

    $totalFee = 0;
    if( is_array($dataRows) && count($dataRows) > 0 ) {


        foreach ( $dataRows as $row ) {

            $totalFee += $row['participation_fee'];
        ?>
<tr>
    <td><?php echo $row['participation_id']; ?></td>
    <td><?php echo htmlentities($row['employee_name']); ?></td>
    <td><?php echo htmlentities($row['employee_mail']); ?></td>
    <td class="text-center"><?php echo htmlentities($row['event_id']); ?></td>
    <td><?php echo htmlentities($row['event_name']); ?></td>
    <td class="td-number"><?php echo number_format( $row['participation_fee'],2, ',', '.'); ?></td>
    <td><?php

        $versionCompare = new VersionCompare( $row['version'] );

        echo date("d/m/Y h:i:s A", strtotime($row['event_date']) );

        if( $versionCompare->isUTCVersion() ) {
            echo ' UTC';
        }
        else
        {
            echo ' Europe/Berlin';
        }

        ?></td>
    <td><?php echo htmlentities($row['version']); ?></td>
</tr>
<?php }  ?>

    <?php } ?>
    <tr>
        <th colspan="5">Total</th>
        <td colspan="3"><?php echo number_format( $totalFee, 2, ',','.' ); ?></td>
    </tr>
</table>

</body>
</html>
