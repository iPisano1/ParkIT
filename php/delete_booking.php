<?php
session_start();
include("config.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_POST['id']) || !isset($_POST['slot_code'])) {
    die("Invalid request");
}

$id = intval($_POST['id']);
$slot_code = $_POST['slot_code'];

$deleteStmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
$deleteStmt->bind_param("i", $id);
$deleteStmt->execute();

$updateStmt = $conn->prepare("UPDATE parking_slots SET status = 'available' WHERE slot_code = ?");
$updateStmt->bind_param("s", $slot_code);
$updateStmt->execute();

header("Location: ../admin.php");
exit();
