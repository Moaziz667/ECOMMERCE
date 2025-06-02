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

    public function __construct($data = [])
    {
        $this->product_id = $data['product_id'] ?? null;
        $this->libelle = $data['libelle'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->prix = $data['prix'] ?? 0.0;
        $this->quantity = $data['quantity'] ?? 0;
        $this->category = $data['category'] ?? '';
        $this->image_url = $data['image_url'] ?? null;
    }
}
?>