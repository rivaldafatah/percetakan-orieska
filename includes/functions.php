<?php
function getProductPrice($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    return $product['price'];
}

function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: login.php');
        exit();
    }
}

function checkUser() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}
?>
