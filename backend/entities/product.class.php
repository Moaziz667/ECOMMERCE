<?php
class Product
{
    public $product_id;
    public $libelle;
    public $description;
    public $prix;
    public $quantity;
    public $category;
    public $image_url;

    public function create_product($data = [])
    {
        $this->product_id = $data['product_id'] ?? null;
        $this->libelle = $data['libelle'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->prix = $data['prix'] ?? 0.0;
        $this->quantity = $data['quantity'] ?? 0;
        $this->category = $data['category'] ?? '';
        $this->image_url = $data['image_url'] ?? null;
    }

    public function save()
    {
        require_once('..\config.php');
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $stmt = $pdo->prepare("INSERT INTO product (libelle, description, prix, quantity, category, image_url) VALUES (:libelle, :description, :prix, :quantity, :category, :image_url)");

        return $stmt->execute([
            ':libelle' => $this->libelle,
            ':description' => $this->description,
            ':prix' => $this->prix,
            ':quantity' => $this->quantity,
            ':category' => $this->category,
            ':image_url' => $this->image_url
        ]);
    }

    public static function getAll()
    {
        require_once('config.php');
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $stmt = $pdo->query("SELECT * FROM product");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        require_once('config.php');
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}  