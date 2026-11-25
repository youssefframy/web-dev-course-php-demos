<?php

class Vehicle {
  protected string $color= 'black';
  public string $engine;
  public string $make;
  public string $honk = 'Beep Beep';

  public const MAX_SPEED = 140;


  public function __construct(string $color, string $engine, string $make, string $honk) {
    $this->color = $color;
    $this->engine = $engine;
    $this->make = $make;
    $this->honk = $honk;
  }

  public function honk(): void {
    echo $this->honk;
  }

  public function setColor(string $color): void {
    $this->color = $color;
  }

  public function getColor(): string {
    return $this->color;
  }

  public function carInfo(): void {
    printf('This is a %s car with a %s engine', $this->color, $this->engine);
  }

}


class Car extends Vehicle {
  private int $numberOfWheels = 4;
  private bool $isElectric = false;

  public function __construct(string $color, string $engine, string $make, string $honk ,int $numberOfWheels, bool $isElectric = false) {
    $this->numberOfWheels = $numberOfWheels;
    $this->isElectric = $isElectric;
    $this->color = 'Blue';
  }

  public function isElectric(): void {
    if ($this->isElectric) {
      echo 'No Engine Sound';
    } else {
      echo 'Vumm Vumm';
    }
  }
}

abstract class Geometric {
  private const PY = 3.14;
  

  abstract public function getArea(): void;

}

class Rectangle extends Geometric {
  public function getArea(): void {
    echo 'Rectangle area';
  }
}

$rectangle1 = new Rectangle();
$rectangle1->getArea();
