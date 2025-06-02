<?php
    session_start();
    require_once "entities\product.class.php";
    $pd=new product();
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}
    if (isset($_POST['login']) & $POST['role']=='admin'){
        $product->create_product($data);
        if ($product->save()) {
            echo json_encode(['success' => true, 'message' => 'Product added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product']);
        }
    }
?>
