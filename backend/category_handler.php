<?php
// category_handler.php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isEdit = isset($_POST['category_id']) && !empty($_POST['category_id']);
    $category_id = $isEdit ? intval($_POST['category_id']) : null;
    $name = trim($_POST['category_name']);
    $description = trim($_POST['description']);

    if ($isEdit) {
        $stmt = $conn->prepare("UPDATE category SET Category_Name = ?, Description = ? WHERE Category_ID = ?");
        $stmt->bind_param("ssi", $name, $description, $category_id);
        $stmt->execute();
        header("Location: ../categories_list.php?updated=1");
    } else {
        $stmt = $conn->prepare("INSERT INTO category (Category_Name, Description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();
        header("Location: ../categories_list.php?added=1");
    }
    exit();
}

if (isset($_GET['deleteid'])) {
    $deleteId = intval($_GET['deleteid']);
    $conn->query("DELETE FROM category WHERE Category_ID = $deleteId");
    header("Location: ../categories_list.php?deleted=1");
    exit();
}
