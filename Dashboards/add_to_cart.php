<?php
session_start();
require_once "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));

    $stmt = $conn->prepare("SELECT Product_Name, Sale_Price, Media_ID FROM products WHERE Product_ID = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        // Redirect back with error if needed
        header("Location: ../index.php");
        exit;
    }
    

    $image_path = "";
    if (!empty($product['Media_ID'])) {
        $img_res = $conn->query("SELECT File_Path FROM media WHERE Media_ID = " . $product['Media_ID']);
        $img = $img_res->fetch_assoc();
        $image_path = $img['File_Path'] ?? '';
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product is already in cart, increase quantity
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product['Product_Name'],
            'price' => $product['Sale_Price'],
            'quantity' => $quantity,
            'image' => $image_path
        ];
    }

    // ✅ Redirect back to the page the form was submitted from
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

}
?>
