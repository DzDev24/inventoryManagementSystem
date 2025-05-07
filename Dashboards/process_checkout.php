<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['customer_id']) || (empty($_SESSION['cart']) && empty($_SESSION['buy_now']))) {
    header("Location: ../index.php");
    exit;
}

$customerId = $_SESSION['customer_id'];

$paymentMethod = trim($_POST['payment_method'] ?? '');

if ($paymentMethod === '') {
    $_SESSION['error'] = "Please select a payment method.";
    header("Location: checkout.php");
    exit;
}

$paymentStatus = 'Unpaid';
if ($paymentMethod === 'Credit Card' || $paymentMethod === 'Bank Transfer') {
    $paymentStatus = 'Paid';
}



$notes = trim($_POST['notes'] ?? '');
$cardId = null;



// If payment is credit card, store card info
if ($paymentMethod === 'Credit Card') {
    $cardholderName = trim($_POST['cardholder_name'] ?? '');
    $cardNumber = trim($_POST['card_number'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');

    $expiryMonth = intval($_POST['expiry_month'] ?? 0);
$expiryYear = intval($_POST['expiry_year'] ?? 0);

if (
    $cardholderName === '' || $cardNumber === '' || $cvv === '' ||
    $expiryMonth < 1 || $expiryMonth > 12 ||
    $expiryYear < 2025 || $expiryYear > 2050
) {
    $_SESSION['error'] = "Please fill in all credit card details correctly.";
    header("Location: checkout.php");
    exit;
}

$stmt = $conn->prepare("INSERT INTO card_details (Customer_ID, Cardholder_Name, Card_Number, CVV, Expiry_Month, Expiry_Year)
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssii", $customerId, $cardholderName, $cardNumber, $cvv, $expiryMonth, $expiryYear);


    if ($stmt->execute()) {
        $cardId = $conn->insert_id;
    } else {
        $_SESSION['error'] = "Failed to save card details.";
        header("Location: checkout.php");
        exit;
    }
}

// Save main sale
$totalAmount = 0;
$items = isset($_SESSION['buy_now']) ? [$_SESSION['buy_now']] : $_SESSION['cart'];
foreach ($items as $item) {
    $totalAmount += $item['price'] * $item['quantity'];

    // Optional protection: stock check
    $productId = $item['id'];
    $quantity = $item['quantity'];

    $stockCheck = $conn->prepare("SELECT Quantity FROM products WHERE Product_ID = ?");
    $stockCheck->bind_param("i", $productId);
    $stockCheck->execute();
    $stockResult = $stockCheck->get_result()->fetch_assoc();

    if ($stockResult['Quantity'] < $quantity) {
        $_SESSION['error'] = "Not enough stock for " . htmlspecialchars($item['name']);
        header("Location: checkout.php");
        exit;
    }
}


$stmt = $conn->prepare("INSERT INTO sales (Customer_ID, Payment_Method, Payment_Status, Notes, Total_Amount, Created_At)
                        VALUES (?, ?, ?, ?, ?, NOW())");
if (!$stmt) {
    die("Error preparing sales statement: " . $conn->error);
}
$stmt->bind_param("isssd", $customerId, $paymentMethod, $paymentStatus, $notes, $totalAmount);




if (!$stmt->execute()) {
    $_SESSION['error'] = "Failed to process order.";
    header("Location: checkout.php");
    exit;
}

$saleId = $conn->insert_id;

// Update customer's order count and total spending
$updateCustomer = $conn->prepare("UPDATE customers SET Orders = Orders + 1, Total_Spend = Total_Spend + ? WHERE Customer_ID = ?");
$updateCustomer->bind_param("di", $totalAmount, $customerId);
$updateCustomer->execute();


// Insert products into sales_order_details
foreach ($items as $item) {
    $productId = $item['id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    $totalCost = $price * $quantity;
    $stmt = $conn->prepare("INSERT INTO sales_order_details (Sale_ID, Product_ID, QTY, Sale_Price, Total_Cost, Status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iiidd", $saleId, $productId, $quantity, $price, $totalCost);
    
    $stmt->execute();

    // Reduce stock from products table
   $updateStock = $conn->prepare("UPDATE products SET Quantity = Quantity - ? WHERE Product_ID = ?");
   $updateStock->bind_param("ii", $quantity, $productId);
   $updateStock->execute();

}

if (isset($_SESSION['buy_now'])) {
    unset($_SESSION['buy_now']);
}

// Clear cart
unset($_SESSION['cart']);

$_SESSION['success'] = "Thank you for your purchase! Your items will be delivered soon.";

// Store order alert in session (for Alerts Center)
if (!isset($_SESSION['alerts'])) {
    $_SESSION['alerts'] = [];
}

$_SESSION['alerts'][] = [
    'icon' => 'check-circle',                      // Feather icon
    'color' => 'success',                          // Bootstrap background color (e.g. success, warning)
    'title' => 'Order Placed',
    'message' => "Your order was placed successfully (Sale ID: $saleId)",
    'time' => date('H:i')
];

header("Location: customer_dashboard.php");
exit;
?>
