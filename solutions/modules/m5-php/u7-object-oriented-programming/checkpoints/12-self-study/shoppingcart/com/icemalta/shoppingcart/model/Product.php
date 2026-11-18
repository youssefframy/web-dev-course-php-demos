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

}