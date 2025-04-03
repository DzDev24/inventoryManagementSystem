<?php

require_once "../includes/db.php";


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isEdit = isset($_POST['product_id']) && !empty($_POST['product_id']);
    $product_id = $isEdit ? intval($_POST['product_id']) : null;
    // Get POST values
    $product_name = $_POST["product_name"];
    $quantity = $_POST["quantity"];
    $buy_price = $_POST["buy_price"];
    $sale_price = $_POST["sale_price"];
    $minimum_stock = $_POST["minimum_stock"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $supplier_id = $_POST["supplier_id"];
    $unit_id = $_POST["unit_id"];
    $report_id = null;

    // Image upload
    $media_id = null;
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $original_name = basename($_FILES["image"]["name"]);
        $unique_name = time() . "_" . $original_name;
        $target_path = $target_dir . $unique_name;


        if (move_uploaded_file($_FILES["image"]["tmp_name"], '../' . $target_path)) {
            $file_type = pathinfo($original_name, PATHINFO_EXTENSION);
            $stmt_media = $conn->prepare("INSERT INTO media (File_Name, File_Type, File_Path) VALUES (?, ?, ?)");
            $stmt_media->bind_param("sss", $original_name, $file_type, $target_path);
            $stmt_media->execute();
            $media_id = $stmt_media->insert_id;
            $stmt_media->close();
        }
    }
    if ($isEdit) {
        // UPDATE
        $sql = "UPDATE products SET Category_ID = ?, Supplier_ID = ?, Product_Name = ?, Quantity = ?, Buy_Price = ?, Sale_Price = ?, Unit_ID = ?, Minimum_Stock = ?, Description = ?, Report_ID = ?" .
            ($media_id ? ", Media_ID = ?" : "") .
            " WHERE Product_ID = ?";
        if ($media_id) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisiiiiisiii", $category_id, $supplier_id, $product_name, $quantity, $buy_price, $sale_price, $unit_id, $minimum_stock, $description, $report_id, $media_id, $product_id);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisiiiiisii", $category_id, $supplier_id, $product_name, $quantity, $buy_price, $sale_price, $unit_id, $minimum_stock, $description, $report_id, $product_id);
        }

        if ($stmt->execute()) {
            header("Location: ../products_list.php?updated=1");
        } else {
            echo "Error updating product: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // INSERT
        $sql = "INSERT INTO products (Media_ID, Category_ID, Supplier_ID, Product_Name, Quantity, Buy_Price, Sale_Price, Unit_ID, Minimum_Stock, Description, Report_ID)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisiiisssi", $media_id, $category_id, $supplier_id, $product_name, $quantity, $buy_price, $sale_price, $unit_id, $minimum_stock, $description, $report_id);

        if ($stmt->execute()) {
            // header("Location: ../products_list.php?added=1");
            echo "<p>Product added successfully!</p>";
            echo "<p><a href='../products_list.php'>Go back to products list</a></p>";
        } else {
            echo "Error adding product: " . $stmt->error;
        }
        $stmt->close();
    }
}



if (isset($_GET['deleteid'])) {
    $product_id = intval($_GET['deleteid']);

    // Optional: Delete image and media entry too (if needed)
    // Get Media_ID
    $mediaResult = $conn->query("SELECT Media_ID FROM products WHERE Product_ID = $product_id");

    if ($mediaResult && $mediaResult->num_rows > 0) {
        $media = $mediaResult->fetch_assoc();

        if (!is_null($media['Media_ID'])) {
            $media_id = (int)$media['Media_ID']; // cast to int to prevent injection
            $conn->query("DELETE FROM media WHERE Media_ID = $media_id");
        }
    }

    // Delete product
    $conn->query("DELETE FROM products WHERE Product_ID = $product_id");

    header("Location: ../products_list.php?deleted=1");

    exit();
}
