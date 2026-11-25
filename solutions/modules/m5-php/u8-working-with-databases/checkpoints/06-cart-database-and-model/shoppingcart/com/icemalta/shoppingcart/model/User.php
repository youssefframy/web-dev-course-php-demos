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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAccessLevel(): string
    {
        return $this->accessLevel;
    }

    public function setAccessLevel(string $accessLevel): void
    {
        $this->accessLevel = $accessLevel;
    }

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

    public function isEmailAvailable(): bool
    {
        $sth = $this->db->getConnection()->prepare('SELECT id FROM User WHERE email = :email');
        $sth->bindValue('email', $this->email);
        $sth->execute();

        return !$sth->fetch(PDO::FETCH_OBJ);
    }
}
