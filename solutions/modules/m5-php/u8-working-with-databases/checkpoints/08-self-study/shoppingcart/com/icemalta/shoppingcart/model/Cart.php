<?php
namespace com\icemalta\shoppingcart\model;

use LogicException;
use PDO;

class CartItem
{
    public Product $product;
    public int $qty;

    public function __construct(Product $product, int $qty)
    {
        $this->product = $product;
        $this->qty = $qty;
    }

    public function getSubtotal(): float
    {
        return $this->qty * $this->product->getPrice();
    }
}

class Cart
{
    private DBConnect $db;
    private ?int $userId;
    public int $id = 0; // 0 means "not saved yet"
    public array $cartItems = [];

    public function __construct(?int $userId = 0)
    {
        $this->userId = $userId;
        $this->db = DBConnect::getInstance();
    }

    public function __serialize(): array
    {
        return [
            'userId' => $this->userId,
            'id' => $this->id,
            'cartItems' => $this->cartItems,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->userId = $data['userId'];
        $this->id = $data['id'];
        $this->cartItems = $data['cartItems'];
        $this->db = DBConnect::getInstance();
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    // Write the cart to the database: the cart row, then all its items.
    public function save(): int
    {
        if (!$this->userId) {
            throw new LogicException('A cart needs a user before it can be saved.');
        }

        $pdo = $this->db->getConnection();
        $rowsAffected = 0;

        // One transaction: either the whole cart is saved, or none of it.
        $pdo->beginTransaction();
        try {
            if ($this->id === 0) {
                $sth = $pdo->prepare('INSERT INTO Cart(userId) VALUES (:userId)');
                $sth->bindValue('userId', $this->userId, PDO::PARAM_INT);
                $sth->execute();
                $this->id = (int) $pdo->lastInsertId();
                $rowsAffected++;
            }

            // Delete the old items, then write the current ones.
            $sth = $pdo->prepare('DELETE FROM CartItem WHERE cartId = :cartId');
            $sth->bindValue('cartId', $this->id, PDO::PARAM_INT);
            $sth->execute();
            $rowsAffected += $sth->rowCount();

            $sth = $pdo->prepare('INSERT INTO CartItem(cartId, productId, price, qty) VALUES (:cartId, :productId, :price, :qty)');
            $productId = null;
            $price = 0;
            $qty = 0;
            $sth->bindValue('cartId', $this->id, PDO::PARAM_INT);
            $sth->bindParam('productId', $productId, PDO::PARAM_INT);
            $sth->bindParam('price', $price);
            $sth->bindParam('qty', $qty, PDO::PARAM_INT);
            foreach ($this->cartItems as $cartItem) {
                $productId = $cartItem->product->getId();
                $price = $cartItem->product->getPrice();
                $qty = $cartItem->qty;
                $sth->execute();
                $rowsAffected += $sth->rowCount();
            }

            $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }

        return $rowsAffected;
    }

    // Replace this cart with the user's latest pending cart from the database, if they have one.
    public function load(): self
    {
        $pdo = $this->db->getConnection();

        $sth = $pdo->prepare('SELECT * FROM Cart WHERE userId = :userId AND status = :status ORDER BY id DESC LIMIT 1');
        $sth->bindValue('userId', $this->userId, PDO::PARAM_INT);
        $sth->bindValue('status', 'PENDING');
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_OBJ);

        $this->cartItems = [];
        if (!$row) {
            $this->id = 0;
            return $this;
        }
        $this->id = $row->id;

        $sth = $pdo->prepare('SELECT * FROM CartItem WHERE cartId = :cartId');
        $sth->bindValue('cartId', $this->id, PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_OBJ);

        $products = Product::getAll();
        foreach ($rows as $row) {
            $product = current(array_filter($products, fn($product) => $product->getId() === $row->productId));
            if ($product !== false) {
                $this->cartItems[] = new CartItem($product, $row->qty);
            }
        }

        return $this;
    }

    public function upsertProduct(Product $product, int $qty): array
    {
        // User set product qty in cart to 0
        if ($qty < 1) {
            return $this->removeProduct($product->getId());
        }

        // Does the user already have this product in their cart?
        foreach ($this->cartItems as $cartItem) {
            if ($cartItem->product->getId() === $product->getId()) {
                $cartItem->qty = $qty; // objects are handles, so this changes the real item
                $this->saveIfOwned();
                return $this->cartItems;
            }
        }

        // Add new product to cart
        $this->cartItems[] = new CartItem($product, $qty);
        $this->saveIfOwned();
        return $this->cartItems;
    }

    public function removeProduct(int $productId): array
    {
        foreach ($this->cartItems as $key => $cartItem) {
            if ($cartItem->product->getId() === $productId) {
                unset($this->cartItems[$key]);
            }
        }

        $this->saveIfOwned();
        return $this->cartItems;
    }

    // A guest's cart lives in the session only. Once it belongs to a user, every change is saved.
    private function saveIfOwned(): void
    {
        if ($this->userId) {
            $this->save();
        }
    }

    // Mark the cart as checked out, so load() no longer finds it.
    public function checkout(): bool
    {
        $sth = $this->db->getConnection()->prepare('UPDATE Cart SET status = :status WHERE id = :id');
        $sth->bindValue('status', 'CHECKOUT');
        $sth->bindValue('id', $this->id, PDO::PARAM_INT);
        $sth->execute();

        return $sth->rowCount() > 0;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->cartItems as $cartItem) {
            $total += $cartItem->getSubtotal();
        }

        return $total;
    }

    public function getItemCount(): int
    {
        $count = 0;
        foreach ($this->cartItems as $cartItem) {
            $count += $cartItem->qty;
        }

        return $count;
    }
}
