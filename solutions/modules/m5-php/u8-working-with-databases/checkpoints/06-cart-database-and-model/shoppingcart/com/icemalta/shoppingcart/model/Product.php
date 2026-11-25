<?php
namespace com\icemalta\shoppingcart\model;

use PDO;

class Product
{
    private DBConnect $db;
    private ?int $id;
    private string $name;
    private float $price;
    private string $description;
    private ?string $featuredImage = null;
    private bool $requiresDeposit = false;

    public function __construct(?int $id, string $name, float $price, string $description, ?string $featuredImage = null, bool $requiresDeposit = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->featuredImage = $featuredImage;
        $this->requiresDeposit = $requiresDeposit;
        $this->db = DBConnect::getInstance();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(?string $featuredImage): void
    {
        $this->featuredImage = $featuredImage;
    }

    public function requiresDeposit(): bool
    {
        return $this->requiresDeposit;
    }

    public function setRequiresDeposit(bool $requiresDeposit): void
    {
        $this->requiresDeposit = $requiresDeposit;
    }

    // Every product in the database, as Product objects.
    public static function getAll(): array
    {
        $sth = DBConnect::getInstance()->getConnection()->prepare('SELECT * FROM Product ORDER BY id');
        $sth->execute();

        return $sth->fetchAll(
            PDO::FETCH_FUNC,
            fn(...$fields) => new Product(...$fields)
        );
    }

    // Insert a new product, or update an existing one.
    public function save(): int
    {
        if ($this->id === null) {
            $sql = 'INSERT INTO Product(name, price, description, featuredImage, requiresDeposit)
                VALUES (:name, :price, :description, :featuredImage, :requiresDeposit)';
            $sth = $this->db->getConnection()->prepare($sql);
        } else {
            $sql = 'UPDATE Product SET name = :name, price = :price, description = :description,
                featuredImage = :featuredImage, requiresDeposit = :requiresDeposit WHERE id = :id';
            $sth = $this->db->getConnection()->prepare($sql);
            $sth->bindValue('id', $this->id, PDO::PARAM_INT);
        }
        $sth->bindValue('name', $this->name);
        $sth->bindValue('price', $this->price);
        $sth->bindValue('description', $this->description);
        $sth->bindValue('featuredImage', $this->featuredImage);
        $sth->bindValue('requiresDeposit', $this->requiresDeposit, PDO::PARAM_BOOL);
        $sth->execute();

        if ($this->id === null) {
            $this->id = (int) $this->db->getConnection()->lastInsertId();
        }

        return $sth->rowCount();
    }

    // A Product is held inside the cart, and the cart is kept in the session, so it must be serializable too.
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'featuredImage' => $this->featuredImage,
            'requiresDeposit' => $this->requiresDeposit,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->description = $data['description'];
        $this->featuredImage = $data['featuredImage'] ?? null;
        $this->requiresDeposit = $data['requiresDeposit'] ?? false;
        $this->db = DBConnect::getInstance();
    }
}
