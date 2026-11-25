# Checkpoint 06: Cart: database and model

## Goal

Give the shopping cart from Unit 7 a database. Write `DBConnect`, `User`, and a `Product` and `Cart` that read and write the database, and check them from the command line. The pages do not change yet.

## Concepts

- The cart app is split into **model** classes (the data and the rules) and **view** pages (what the visitor sees). This checkpoint only touches the model.
- **Secrets stay out of the code.** A database user and password in a PHP file end up in Git. Put them in a `.env` file, read it with `parse_ini_file()`, and tell Git to ignore it with `.gitignore`. Share a `.env.example` with the names and no real values, so the next developer knows what to fill in.
- **Objects in the session need to be serializable.** PHP turns an object into a string to save it in the session (serializes it), and builds it again on the next request (unserializes it). A `PDO` connection cannot be turned into a string. A class that holds one needs two magic methods: `__serialize()` returns the array of values to keep, and `__unserialize($data)` puts them back. `DBConnect` keeps nothing (`[]`). Every class that holds a `DBConnect` asks for the instance again in its `__unserialize()`.
- **Passwords are hashed, never stored.** `password_hash()` turns a password into a long string (a hash, with a random salt) that cannot be turned back. `password_verify($typed, $hash)` checks a typed password against it. A copy of the database then does not give anyone the passwords. `User::__serialize()` also leaves the password out, so it never reaches the session file.
- **An upsert** is one method that inserts a new row, or updates it if it already exists. `save()` decides by whether the object has an `id`.
- **A transaction** groups statements so that either all of them happen or none do. Saving a cart is a cart row plus its items. `beginTransaction()`, `commit()` and `rollBack()` make sure you never save half a cart.
- The classes are the slides' classes with a few changes. Every change is in the answer's `NOTES.md`.

## Before you start

You need the shopping cart from Unit 7, and the database. If you skipped Unit 7, the unit's [`shoppingcart/`](../../shoppingcart) folder already has a copy. Load the database with `./sql reset shoppingcart` if you have not (see the [unit README](../../README.md)).

You work in `modules/m5-php/u8-working-with-databases/shoppingcart`. Start the server there and visit <http://localhost:8000>. *Check: the cart works as in Unit 7, with the four courses held in memory.*

## Steps

1. Follow along: store the secrets. In the `shoppingcart` folder, create `.env`:

   ```ini
   DB_HOST="127.0.0.1"
   DB_NAME="shoppingcart"
   DB_USER="root"
   DB_PASS="root"
   ```

   If you are not in the dev container, keep `DB_HOST` as `127.0.0.1`. In the dev container, use `db`. Copy it to `.env.example` too. Then create `.gitignore` in the same folder:

   ```text
   .env
   outbox/
   ```

   *Check: in the Source Control panel, `.env` does not appear, and `.env.example` does.*

2. Follow along: write `com/icemalta/shoppingcart/model/DBConnect.php`, the Singleton from checkpoint 05, now with the credentials read from `.env`. `__DIR__` is the `model` folder, so `dirname(__DIR__, 4)` is the app root, wherever the app is served from.

   ```php
   <?php
   namespace com\icemalta\shoppingcart\model;

   use PDO;
   use RuntimeException;

   class DBConnect
   {
       private static ?DBConnect $singleton = null;
       private PDO $dbh;

       private function __construct()
       {
           // The credentials live in .env, which is never committed.
           // __DIR__ is .../com/icemalta/shoppingcart/model, so four levels up is the app root.
           $env = @parse_ini_file(dirname(__DIR__, 4) . '/.env');
           if ($env === false) {
               throw new RuntimeException('Missing .env file. Copy .env.example to .env and fill it in.');
           }

           $this->dbh = new PDO(
               "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4",
               $env['DB_USER'],
               $env['DB_PASS'],
               [
                   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                   PDO::ATTR_PERSISTENT => true,
               ]
           );
       }

       public static function getInstance(): DBConnect
       {
           self::$singleton = self::$singleton ?? new DBConnect();
           return self::$singleton;
       }

       public function getConnection(): PDO
       {
           return $this->dbh;
       }

       // A PDO connection cannot be serialized, so the session gets nothing.
       // Classes that hold a DBConnect ask for the instance again when they are unserialized.
       public function __serialize(): array
       {
           return [];
       }

       public function __unserialize(array $data): void
       {
       }
   }
   ```

3. Follow along: write `com/icemalta/shoppingcart/model/User.php`. Start with the fields and the constructor. Let the editor generate the getters and setters (as in Unit 7 checkpoint 02): `getId`, `getFirstName`/`setFirstName`, `getLastName`/`setLastName`, `getEmail`/`setEmail`, `getPassword`/`setPassword` and `getAccessLevel`/`setAccessLevel`.

   ```php
   <?php
   namespace com\icemalta\shoppingcart\model;

   use PDO;

   class User
   {
       private DBConnect $db;

       private ?int $id;
       private ?string $firstName;
       private ?string $lastName;
       private string $email;
       private string $password;
       private string $accessLevel = 'standard';

       public function __construct(string $email, string $password, ?string $firstName = null, ?string $lastName = null, ?int $id = null, ?string $accessLevel = 'standard')
       {
           $this->firstName = $firstName;
           $this->lastName = $lastName;
           $this->email = $email;
           $this->password = $password;
           $this->id = $id;
           $this->accessLevel = $accessLevel ?? 'standard';
           $this->db = DBConnect::getInstance();
       }

       // getters and setters here
       // ... (the class is closed after step 4)
   ```

   Add the two serialize methods:

   ```php
       // The password is left out, so it never reaches the session file.
       public function __serialize(): array
       {
           return [
               'id' => $this->id,
               'firstName' => $this->firstName,
               'lastName' => $this->lastName,
               'email' => $this->email,
               'accessLevel' => $this->accessLevel,
           ];
       }

       public function __unserialize(array $data): void
       {
           $this->id = $data['id'];
           $this->firstName = $data['firstName'];
           $this->lastName = $data['lastName'];
           $this->email = $data['email'];
           $this->accessLevel = $data['accessLevel'];
           $this->password = '';
           $this->db = DBConnect::getInstance();
       }
   ```

   The class is not closed yet. It is finished in the next step.

4. Follow along: add the methods that use the database. `save()` inserts or updates. `login()` fills the object from the matching row. `isEmailAvailable()` is `true` when no user has the email yet.

   ```php
       // Insert a new user, or update an existing one (an "upsert").
       public function save(): int
       {
           $pdo = $this->db->getConnection();

           if ($this->id === null) {
               $sql = 'INSERT INTO User(firstName, lastName, email, password, accessLevel)
                   VALUES (:firstName, :lastName, :email, :password, :accessLevel)';
               $sth = $pdo->prepare($sql);
               $sth->bindValue('password', password_hash($this->password, PASSWORD_DEFAULT));
           } elseif ($this->password !== '') {
               $sql = 'UPDATE User SET firstName = :firstName, lastName = :lastName, email = :email,
                   password = :password, accessLevel = :accessLevel WHERE id = :id';
               $sth = $pdo->prepare($sql);
               $sth->bindValue('password', password_hash($this->password, PASSWORD_DEFAULT));
               $sth->bindValue('id', $this->id, PDO::PARAM_INT);
           } else {
               // A user restored from the session has no password. Leave the stored one alone.
               $sql = 'UPDATE User SET firstName = :firstName, lastName = :lastName, email = :email,
                   accessLevel = :accessLevel WHERE id = :id';
               $sth = $pdo->prepare($sql);
               $sth->bindValue('id', $this->id, PDO::PARAM_INT);
           }
           $sth->bindValue('firstName', $this->firstName);
           $sth->bindValue('lastName', $this->lastName);
           $sth->bindValue('email', $this->email);
           $sth->bindValue('accessLevel', $this->accessLevel);
           $sth->execute();

           if ($this->id === null) {
               $this->id = (int) $pdo->lastInsertId();
           }

           return $sth->rowCount();
       }
   ```

   ```php
       // Check the email and password against the database. On success, fill in this object from the row.
       public function login(): bool
       {
           $sth = $this->db->getConnection()->prepare('SELECT * FROM User WHERE email = :email');
           $sth->bindValue('email', $this->email);
           $sth->execute();
           $result = $sth->fetch(PDO::FETCH_OBJ);

           if ($result && password_verify($this->password, $result->password)) {
               $this->id = $result->id;
               $this->firstName = $result->firstName;
               $this->lastName = $result->lastName;
               $this->email = $result->email;
               $this->accessLevel = $result->accessLevel;
               $this->password = ''; // no longer needed, and best not kept around
               return true;
           }

           return false;
       }
   ```

   ```php
       public function isEmailAvailable(): bool
       {
           $sth = $this->db->getConnection()->prepare('SELECT id FROM User WHERE email = :email');
           $sth->bindValue('email', $this->email);
           $sth->execute();

           return !$sth->fetch(PDO::FETCH_OBJ);
       }
   ```

   Now close the class with `}`. *Check: `php -l` on the file says no syntax errors.*

5. Follow along: refactor `Product.php`. The data now comes from the table, so the class gets a database, an `id` that can be `null` (a product not saved yet), and the methods below. Change the constructor's first parameter to `?int $id`, and add `private DBConnect $db;` and `$this->db = DBConnect::getInstance();` to the constructor. Change `getId()` and `setId()` to use `?int`. Add `use PDO;` under the namespace.

   ```php
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
   ```

   ```php
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
   ```

   ```php
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
   ```

   `getAll()` is `static`: it belongs to the class, so you call it as `Product::getAll()`, with no product needed. The arrow function in `FETCH_FUNC` receives the six columns in order and builds a `Product` from each.

6. Follow along: refactor `Cart.php`. `CartItem` stays as it is. `Cart` gets a database, an owner (`userId`), and an `id` (0 means "not saved yet"). Add `use LogicException;` and `use PDO;` under the namespace, then change the top of the `Cart` class to:

   ```php
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

       // __serialize() and __unserialize() here

       public function setUserId(int $userId): self
       {
           $this->userId = $userId;
           return $this;
       }
   ```

   Write `__serialize()` and `__unserialize()` yourself, from the `User` ones: keep `userId`, `id` and `cartItems`, and ask for the `DBConnect` again. *Your turn.*

   Then add the database methods:

   ```php
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
   ```

   ```php
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
   ```

   ```php
       // Mark the cart as checked out, so load() no longer finds it.
       public function checkout(): bool
       {
           $sth = $this->db->getConnection()->prepare('UPDATE Cart SET status = :status WHERE id = :id');
           $sth->bindValue('status', 'CHECKOUT');
           $sth->bindValue('id', $this->id, PDO::PARAM_INT);
           $sth->execute();

           return $sth->rowCount() > 0;
       }
   ```

   Keep `getTotal()` and `getItemCount()` as they were. Change `upsertProduct()` and `removeProduct()` so each calls `$this->saveIfOwned()` just before every `return` that comes after a change, and add:

   ```php
       // A guest's cart lives in the session only. Once it belongs to a user, every change is saved.
       private function saveIfOwned(): void
       {
           if ($this->userId) {
               $this->save();
           }
       }
   ```

   A guest's cart stays in the session only. Once the cart belongs to a user, every change is saved.

7. Follow along: try it all from the command line, with a scratch script. Create `scratch.php` in the `shoppingcart` folder:

   ```php
   <?php
   // A scratch script to try the model from the command line: php scratch.php
   // Delete it when you are done.
   require 'com/icemalta/shoppingcart/model/DBConnect.php';
   require 'com/icemalta/shoppingcart/model/User.php';
   require 'com/icemalta/shoppingcart/model/Product.php';
   require 'com/icemalta/shoppingcart/model/Cart.php';

   use com\icemalta\shoppingcart\model\Cart;
   use com\icemalta\shoppingcart\model\Product;
   use com\icemalta\shoppingcart\model\User;

   $email = 'scratch' . random_int(1000, 9999) . '@example.com';

   $user = new User($email, 'secret123', 'Sam', 'Scratch');
   echo 'Email available: ', var_export($user->isEmailAvailable(), true), "\n";
   $user->save();
   echo 'Saved user id ', $user->getId(), "\n";
   echo 'Email available now: ', var_export($user->isEmailAvailable(), true), "\n";

   echo 'Wrong password: ', var_export((new User($email, 'nope'))->login(), true), "\n";
   $login = new User($email, 'secret123');
   echo 'Right password: ', var_export($login->login(), true), ' (', $login->getFirstName(), ")\n";

   $products = Product::getAll();
   echo 'Products: ', count($products), ', first is ', $products[0]->getName(), "\n";

   $cart = new Cart($user->getId());
   $cart->upsertProduct($products[0], 2);
   $cart->upsertProduct($products[2], 1);
   echo 'Cart items ', $cart->getItemCount(), ', total ', $cart->getTotal(), ', cart id ', $cart->id, "\n";

   $reloaded = (new Cart($user->getId()))->load();
   echo 'Reloaded items ', $reloaded->getItemCount(), ', total ', $reloaded->getTotal(), "\n";

   $copy = unserialize(serialize($reloaded));
   echo 'Survives serialize: ', $copy->getItemCount(), " items\n";

   $cart->checkout();
   echo 'After checkout, a fresh load finds ', (new Cart($user->getId()))->load()->getItemCount(), " items\n";
   ```

   Run it:

   ```sh
   php scratch.php
   ```

   *Check: a new user is saved and gets an id; the email is available before and not after; a wrong password gives `false` and the right one `true`; the first product is `Getting Started with React`; the cart has 3 items and a total of 540; the reloaded cart matches; the serialized copy has 3 items; after checkout a fresh load finds 0 items.* Check phpMyAdmin: the `User` row has a long `$2y$…` password, not the real one.

8. Your turn: in `scratch.php`, try to save a second user with the same email. *Check: a `PDOException` about a duplicate entry, from the `UNIQUE` constraint on `email`. That is why the register page will call `isEmailAvailable()` first.* Then delete `scratch.php`.

9. Your turn: look at the web app again. Visit <http://localhost:8000> and use the cart as a guest.

   *Check: it still works, because the pages still build their products in memory. The views change in the next checkpoint.*

## Done when

- [ ] `.env` is not in Git, and `.env.example` is.
- [ ] `scratch.php` ran to the end and every check matched.
- [ ] The password in the `User` table is a hash.
- [ ] You can say why `DBConnect::__serialize()` returns an empty array.
- [ ] You can say what a transaction protects `Cart::save()` from.

## Previous / Next

Previous: [05 The Singleton pattern](../05-singleton/README.md). Next: [07 Cart: accounts, views and checkout](../07-accounts-views-checkout/README.md).
