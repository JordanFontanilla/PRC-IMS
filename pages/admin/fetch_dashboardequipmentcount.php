<?php
include("../../function_connection.php");

header('Content-Type: application/json');

// Initialize counts
$allequip = $allavailequip = $allmissequip = $allpendequip = $allborrequip = 0;

// Fetch all equipment count
$queryAll = "SELECT COUNT(*) AS count1 FROM tbl_inv";
$queryAvail = "SELECT COUNT(*) AS count2 FROM tbl_inv WHERE inv_status = 1";
$queryMiss = "SELECT COUNT(*) AS count3 FROM tbl_inv WHERE inv_status = 6";
$queryPend = "SELECT COUNT(*) AS count4 FROM tbl_borrow_request WHERE breq_status = 3";
$queryBorr = "SELECT COUNT(*) AS count5 FROM tbl_inv WHERE inv_status = 4";
$queryBorr2 = "SELECT COUNT(*) AS count6 FROM tbl_inv WHERE inv_status = 3";
$queryNonCon = "SELECT COUNT(*) AS count7 FROM tbl_inv left join tbl_type ON tbl_inv.type_id = tbl_type.type_id WHERE tbl_type.type_origin = 'Non-Consumable'";
// FIX: Use tbl_inv_consumables for consumables count
$queryCon = "SELECT COUNT(*) AS count8 FROM tbl_inv_consumables";

$resultAll = mysqli_query($conn, $queryAll);
$resultAvail = mysqli_query($conn, $queryAvail);
$resultMiss = mysqli_query($conn, $queryMiss);
$resultPend = mysqli_query($conn, $queryPend);
$resultBorr = mysqli_query($conn, $queryBorr);
$resultBorr2 = mysqli_query($conn, $queryBorr2);
$resultNonCon = mysqli_query($conn, $queryNonCon);
$resultCon = mysqli_query($conn, $queryCon);

if ($row = mysqli_fetch_assoc($resultAll)) {
    $allequip = $row['count1'];
}
if ($row = mysqli_fetch_assoc($resultAvail)) {
    $allavailequip = $row['count2'];
}
if ($row = mysqli_fetch_assoc($resultMiss)) {
    $allmissequip = $row['count3'];
}
if ($row = mysqli_fetch_assoc($resultPend)) {
    $allpendequip = $row['count4'];
}
if ($row = mysqli_fetch_assoc($resultBorr)) {
    $allborrequip = $row['count5'];
}
if ($row = mysqli_fetch_assoc($resultBorr2)) {
    $allborrequip2 = $row['count6'];
}
if ($row = mysqli_fetch_assoc($resultNonCon)) {
    $allnoncon = $row['count7'];
}
if ($row = mysqli_fetch_assoc($resultCon)) {
    $allcon = $row['count8'];
}


echo json_encode([
    "allequip" => $allequip,
    "allavailequip" => $allavailequip,
    "allmissequip" => $allmissequip,
    "allpendequip" => $allpendequip,
    "allborrequip" => $allborrequip,
    "allborrequip2" => $allborrequip2,
    "allnoncon" => $allnoncon,
    "allcon" => $allcon
]);

mysqli_close($conn);
?>
