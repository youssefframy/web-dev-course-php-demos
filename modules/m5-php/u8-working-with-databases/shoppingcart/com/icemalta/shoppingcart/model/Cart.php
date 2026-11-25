<?php
namespace com\icemalta\shoppingcart\model;

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
    public array $cartItems = [];

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
                return $this->cartItems;
            }
        }

        // Add new product to cart
        $this->cartItems[] = new CartItem($product, $qty);
        return $this->cartItems;
    }

    public function removeProduct(int $productId): array 
    {
        foreach ($this->cartItems as $key=>$cartItem) {
            if ($cartItem->product->getId() === $productId) {
                unset($this->cartItems[$key]);
            }
        }

        return $this->cartItems;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach($this->cartItems as $cartItem) {
            $total += $cartItem->getSubtotal();
        }

        return $total;
    }

    public function getItemCount(): int
    {
        $count = 0;
        foreach($this->cartItems as $cartItem) {
            $count += $cartItem->qty;
        }
        
        return $count;
    }

}