# Checkpoint 11: Shopping cart

## Goal

Build the model classes of a small online shop, `Product`, `CartItem` and `Cart`, and use them to make the cart page work.

## Concepts

- The app has a `model` folder for classes that hold the data and rules, and a `view` folder for pages that show them. The page logic should be small. The classes do the work.
- A `Cart` holds an array of `CartItem`s. A `CartItem` holds a `Product` and a quantity. Objects holding objects is the normal way to model this.
- Objects are passed around as handles. If you change `$item->qty` where `$item` came from a `foreach`, you change the real item, with no `&` needed. You needed `&` for arrays in Unit 6, but not for objects.
- `?string` means a string or `null`. Write `?string $image = null`, not `string $image = null`, which PHP 8.4 marks as deprecated.
- The cart lives in the session as an object. PHP must know the class **before** `session_start()`, or it cannot rebuild the object and gives you `__PHP_Incomplete_Class`. The starter's `index.php` already requires the model files first.
- `filter_input(..., FILTER_VALIDATE_INT)` gives an `int`, or `false` if the value is not a whole number, or `null` if it is missing. The slides use `FILTER_SANITIZE_NUMBER_INT`, which gives a *string*, so a strict `===` against an id never matches and delete does nothing.
- `current(array_filter(...))` gives the first match, or `false` when there is none. Check for that before you pass it to a method that wants a `Product`.

## Before you start

The starter app is in the unit's [`shoppingcart/`](../../shoppingcart) folder. It has the pages and the images, and none of the logic. Start the server there:

```sh
cd modules/m5-php/u7-object-oriented-programming/shoppingcart
php -S localhost:8000
```

Visit <http://localhost:8000>. *Check: a fatal error, `Class Product not found`. You fix it in step 1.*

## Steps

1. Follow along: write `com/icemalta/shoppingcart/model/Product.php`. The fields are private, so each needs a getter and a setter.

   ```php
   <?php
   namespace com\icemalta\shoppingcart\model;

   class Product
   {
       private int $id;
       private string $name;
       private float $price;
       private string $description;
       private ?string $featuredImage = null;
       private bool $requiresDeposit = false;

       public function __construct(int $id, string $name, float $price, string $description, ?string $featuredImage = null, bool $requiresDeposit = false)
       {
           $this->id = $id;
           $this->name = $name;
           $this->price = $price;
           $this->description = $description;
           $this->featuredImage = $featuredImage;
           $this->requiresDeposit = $requiresDeposit;
       }

       public function getId(): int
       {
           return $this->id;
       }

       public function setId(int $id): void
       {
           $this->id = $id;
       }

       // getName/setName, getPrice/setPrice, getDescription/setDescription and
       // getFeaturedImage/setFeaturedImage follow the same pattern.

       public function requiresDeposit(): bool
       {
           return $this->requiresDeposit;
       }

       public function setRequiresDeposit(bool $requiresDeposit): void
       {
           $this->requiresDeposit = $requiresDeposit;
       }
   }
   ```

   Let the editor generate the getters and setters, as in checkpoint 02. *Check: reload. The error moves on to `Cart`, or to the missing `$cart`, and the page header shows.*

2. Follow along: write `model/Cart.php`, starting with `CartItem`.

   ```php
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
   ```

3. Follow along: add `Cart` in the same file.

   ```php
   class Cart
   {
       public array $cartItems = [];

       public function upsertProduct(Product $product, int $qty): array
       {
           // A quantity below 1 means "remove it"
           if ($qty < 1) {
               return $this->removeProduct($product->getId());
           }

           // Already in the cart? Change its quantity.
           foreach ($this->cartItems as $cartItem) {
               if ($cartItem->product->getId() === $product->getId()) {
                   $cartItem->qty = $qty; // objects are handles, so this changes the real item
                   return $this->cartItems;
               }
           }

           // Otherwise add it
           $this->cartItems[] = new CartItem($product, $qty);
           return $this->cartItems;
       }

       public function removeProduct(int $productId): array
       {
           foreach ($this->cartItems as $key => $cartItem) {
               if ($cartItem->product->getId() === $productId) {
                   unset($this->cartItems[$key]);
               }
           }
           return $this->cartItems;
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
   ```

   *Check: reload. The page shows the four courses, and "Enrol" leads to the cart page, which still has no logic.*

4. Follow along: in `view/cart.inc.php`, replace the comment in the PHP block at the top with the logic.

   ```php
   $products = $_SESSION['products']; // the list of products from index.php
   $cart = $_SESSION['cart'] ?? new Cart(); // the visitor's cart, or a new one

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $action = filter_input(INPUT_POST, 'action');
       $productId = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

       switch ($action) {
           case 'upsert':
               $product = current(array_filter($products, fn($product) => $product->getId() === $productId));
               $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
               if (!is_int($qty)) {
                   $qty = 1; // no quantity sent (the Enrol button), or nonsense
               }
               if ($product !== false) { // false: no product has that id
                   $cart->upsertProduct($product, $qty);
               }
               break;
           case 'delete':
               if ($productId !== false && $productId !== null) {
                   $cart->removeProduct($productId);
               }
               break;
       }

       $_SESSION['cart'] = $cart; // keep the cart for the next request
   }

   $showDepositWarning = count(array_filter($cart->cartItems, fn($cartItem) => $cartItem->product->requiresDeposit())) > 0;
   ```

   The slides read `qty` with `?? 1`, which does not catch `false`, so a quantity of `abc` would crash. The `is_int()` check does.

5. Try it. *Check: Enrol on two courses, and the cart badge in the menu shows 2. In the cart, change one quantity to 3 and press the refresh button: the subtotal changes, and the badge shows 4. Delete a course: it goes. Enrol on the Unity course: a yellow warning about a deposit appears.*

6. Your turn: the page shows money as plain numbers such as `€ 270`. Show two decimals everywhere in `cart.inc.php`, with `number_format($value, 2)`.

   *Check: `€ 270.00`.*

7. Your turn: in the browser's developer tools, change a hidden `productId` to `99` and press the button. *Check: no error, and the cart is unchanged.*

8. Your turn: escape the names. `products.inc.php` and `cart.inc.php` print `getName()` and `getDescription()` directly. Wrap them in `htmlspecialchars()`. Product names are fixed here, but a real shop would take them from a database, and you should never print text you did not write without escaping it.

## Done when

- [ ] The cart badge, the quantity update, the delete and the deposit warning all work.
- [ ] A bad quantity or product id changes nothing and causes no error.
- [ ] You can say why the model files are required before `session_start()`.
- [ ] You can say why a change made inside a `foreach` over objects reaches the real objects.

## Previous / Next

Previous: [10 Reflection](../10-reflection/README.md). Next: [12 Self-study](../12-self-study/README.md).
