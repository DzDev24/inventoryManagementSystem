<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    if (isset($_SESSION['supply_proposals'])) {
        foreach ($_SESSION['supply_proposals'] as $index => $item) {
            if ($item['id'] === $product_id) {
                unset($_SESSION['supply_proposals'][$index]);
                $_SESSION['supply_proposals'] = array_values($_SESSION['supply_proposals']); // Reindex array
                break;
            }
        }
    }
}

// Redirect back to previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
