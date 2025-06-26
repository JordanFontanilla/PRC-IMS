<?php
require '../../function_connection.php';

if (isset($_POST['inv_id'])) {
    $inv_id = $_POST['inv_id'];
    $equipSerial = $_POST['equipSerial'];
    $equipPropNo = $_POST['equipPropNo'];
    $equipPropName = $_POST['equipPropName'];


    
    if (isset($_POST['checkDuplicates'])) {
        $equipType = $_POST['equipType'];
    
        $checkQuery = "SELECT inv_serialno, inv_propno, inv_propname, type_id FROM tbl_inv 
                       WHERE (inv_serialno = ? OR inv_propno = ? OR inv_propname = ? OR type_id = ?) 
                       AND inv_id != ?";
        
        if ($stmt = $conn->prepare($checkQuery)) {
            $stmt->bind_param("sssii", $equipSerial, $equipPropNo, $equipPropName, $equipType, $inv_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $duplicateSerial = false;
            $duplicatePropNo = false;
            $duplicatePropName = false;
            $duplicateType = false;
            $blockUpdate = false;
    
            while ($row = $result->fetch_assoc()) {
                $matchSerial = $row['inv_serialno'] === $equipSerial;
                $matchPropNo = $row['inv_propno'] === $equipPropNo;
                $matchPropName = $row['inv_propname'] === $equipPropName;
                $matchType = $row['type_id'] == $equipType;
    
                // If all four match a single record — block the update
                if ($matchSerial && $matchPropNo && $matchPropName && $matchType) {
                    $blockUpdate = true;
                    break;
                }
    
                // Track partial duplicates
                if ($matchSerial) $duplicateSerial = true;
                if ($matchPropNo) $duplicatePropNo = true;
                if ($matchPropName) $duplicatePropName = true;
                if ($matchType) $duplicateType = true;
            }
    
            $stmt->close();
    
            echo json_encode([
                'duplicate' => ($duplicateSerial || $duplicatePropNo || $duplicatePropName || $duplicateType),
                'duplicateSerial' => $duplicateSerial,
                'duplicatePropNo' => $duplicatePropNo,
                'duplicatePropName' => $duplicatePropName,
                'duplicateType' => $duplicateType,
                'blockUpdate' => $blockUpdate
            ]);
            exit();
        }
    }
    

    if (isset($_POST['updateEquipment'])) {
        $equipBrand = $_POST['equipBrand'];
        $equipStatus = $_POST['equipStatus'];
        $equipType = $_POST['equipType']; // Optional unless needed in the update
        $editequipenduser = $_POST['editequipenduser'];
        $editequipaccountedto = $_POST['editequipaccountedto'];
    
$updateQuery = "UPDATE tbl_inv SET 
                    inv_bnm = ?, 
                    inv_serialno = ?, 
                    inv_propno = ?, 
                    inv_status = ?, 
                    inv_propname = ?, 
                    type_id = ?, 
                    end_user = ?, 
                    accounted_to = ? 
                WHERE inv_id = ?";

$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("sssissssi", $equipBrand, $equipSerial, $equipPropNo, $equipStatus, $equipPropName, $equipType, $editequipenduser, $editequipaccountedto, $inv_id);
    
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Equipment updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed.']);
        }
        $stmt->close();
        exit();
    }
    
}
?>