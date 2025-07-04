<?php
include("../../function_connection.php");

header('Content-Type: application/json');

// Initialize counts
$allequip = $allavailequip = $allmissequip = $allpendequip = $allborrequip = 0;

// Re-calculate counts considering both tables
$queryAll = "SELECT (SELECT COUNT(*) FROM tbl_inv) + (SELECT COUNT(*) FROM tbl_inv_consumables) AS count1";
$queryAvail = "SELECT (SELECT COUNT(*) FROM tbl_inv WHERE inv_status = 1) + (SELECT COUNT(*) FROM tbl_inv_consumables WHERE inv_status = 1) AS count2";
$queryMiss = "SELECT (SELECT COUNT(*) FROM tbl_inv WHERE inv_status = 6) + (SELECT COUNT(*) FROM tbl_inv_consumables WHERE inv_status = 6) AS count3";
$queryPend = "SELECT COUNT(*) AS count4 FROM tbl_borrow_request WHERE breq_status = 3";
$queryBorr = "SELECT (SELECT COUNT(*) FROM tbl_inv WHERE inv_status = 4) + (SELECT COUNT(*) FROM tbl_inv_consumables WHERE inv_status = 4) AS count5";
$queryBorr2 = "SELECT (SELECT COUNT(*) FROM tbl_inv WHERE inv_status = 3) + (SELECT COUNT(*) FROM tbl_inv_consumables WHERE inv_status = 3) AS count6";
$queryNonCon = "SELECT COUNT(*) AS count7 FROM tbl_inv";
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
