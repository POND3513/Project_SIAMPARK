<?php
ini_set('display_errors', 1); 
error_reporting(E_ALL); 

try {
    $pdo = new PDO('sqlite:ticket.db'); //
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticketType = $_POST['ticketType'];
    $ticketQuantity = $_POST['ticketQuantity'];

    if (empty($ticketType) || empty($ticketQuantity)) {
        echo json_encode(['error' => 'ข้อมูลไม่ครบ']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (ticket_type, quantity) VALUES (?, ?)");
        $stmt->execute([$ticketType, $ticketQuantity]);
        echo json_encode(['success' => 'การจองตั๋วสำเร็จ']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'การส่งข้อมูลผิดพลาด']);
}
?>