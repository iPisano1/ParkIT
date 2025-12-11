<?php
    header('Content-Type: application/json');
    include("php/config.php");

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    $response = ['success' => false, 'message' => '', 'total'=>0,'occupied'=>0,'available'=>0];

    function send($resp) {
        echo json_encode($resp);
        exit;
    }

    function getStats($conn) {
        $res = $conn->query("SELECT COUNT(*) AS total, SUM(status='occupied') AS occupied FROM parking_slots");
        $row = $res->fetch_assoc();
        $total = intval($row['total'] ?? 0);
        $occupied = intval($row['occupied'] ?? 0);
        return ['total'=>$total, 'occupied'=>$occupied, 'available'=>($total - $occupied)];
    }

    if ($action === 'toggle') {
        $slot = $_POST['slot'] ?? '';
        $newStatus = $_POST['status'] ?? null;
        if (!$slot) send(['success'=>false,'message'=>'Missing slot.']);

        $slot_code = $conn->real_escape_string($slot);

        if ($newStatus === 'occupied' || $newStatus === 'available') {
            $stmt = $conn->prepare("UPDATE parking_slots SET status=? WHERE slot_code = ?");
            $stmt->bind_param("ss", $newStatus, $slot_code);
            $ok = $stmt->execute();
            $stmt->close();
            if (!$ok) send(['success'=>false,'message'=>'DB update failed.']);
        } else {
            $row = $conn->query("SELECT status FROM parking_slots WHERE slot_code='".$slot_code."'")->fetch_assoc();
            if (!$row) send(['success'=>false,'message'=>'Slot not found.']);
            $curr = $row['status'];
            $target = ($curr === 'occupied') ? 'available' : 'occupied';
            $stmt = $conn->prepare("UPDATE parking_slots SET status=? WHERE slot_code = ?");
            $stmt->bind_param("ss", $target, $slot_code);
            $ok = $stmt->execute();
            $stmt->close();
            if (!$ok) send(['success'=>false,'message'=>'DB update failed.']);
        }

        $stats = getStats($conn);
        send(array_merge(['success'=>true,'message'=>'Slot updated.'], $stats));
    }

    if ($action === 'set_all') {
        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['occupied','available'])) send(['success'=>false,'message'=>'Invalid status.']);
        $stmt = $conn->prepare("UPDATE parking_slots SET status = ?");
        $stmt->bind_param("s", $status);
        $ok = $stmt->execute();
        $stmt->close();
        if (!$ok) send(['success'=>false,'message'=>'DB update failed.']);
        $stats = getStats($conn);
        send(array_merge(['success'=>true,'message'=>'All slots updated.'], $stats));
    }

    if ($action === 'stats') {
        $stats = getStats($conn);
        send(array_merge(['success'=>true,'message'=>'Stats retrieved.'], $stats));
    }

    send(['success'=>false,'message'=>'Unknown action.']);
