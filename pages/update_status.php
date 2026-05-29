<?php
require_once('../classes/database.php');

$con = new Database();
$conn = $con->opencon();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // 1. UPDATE STATUS
    $stmt = $conn->prepare("
        UPDATE appointment
        SET status = ?
        WHERE appointment_id = ?
    ");

    $stmt->execute([$status, $appointment_id]);

    // 2. CREATE PAYMENT ONLY IF CONFIRMED
if ($status == 'confirmed') {

    // check if may existing payment
    $check = $conn->prepare("
        SELECT * FROM payment WHERE appointment_id = ?
    ");
    $check->execute([$appointment_id]);

    if ($check->rowCount() == 0) {

        $stmt2 = $conn->prepare("
            INSERT INTO payment (appointment_id, amount, status)
            VALUES (?, ?, 'pending')
        ");

        $stmt2->execute([
            $appointment_id,
            0 // or service price
        ]);
    }

} 
elseif ($status == 'cancelled') {


    $delete = $conn->prepare("
        DELETE FROM payment WHERE appointment_id = ?
    ");
    $delete->execute([$appointment_id]);
}
    header("Location: appointment.php");
    exit();
    }



?>