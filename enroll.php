<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in to enroll!']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['courseId'];
    $fee = $_POST['courseFee']; // Includes ₹ symbol, so remove it
    $fee = floatval(str_replace('₹', '', $fee));
    $full_name = $_POST['fullName'];
    $email = $_POST['email'];
    $card_number = $_POST['cardNumber'];
    $expiry_date = $_POST['expiryDate'];
    $cvv = $_POST['cvv'];

    // Basic validation (you can expand this)
    if (empty($full_name) || empty($email) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required!']);
        exit();
    }

    // Simulate payment processing (replace with real payment gateway logic if needed)
    $payment_status = 'paid'; // Assuming payment is successful for demo

    $sql = "INSERT INTO enrollments (user_id, course_id, payment_amount, payment_status) 
            VALUES (:user_id, :course_id, :fee, :payment_status)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':fee', $fee);
    $stmt->bindParam(':payment_status', $payment_status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Enrollment and payment successful for {$course_id}!"]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Enrollment failed!']);
    }
}
?>