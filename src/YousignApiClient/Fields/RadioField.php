<?php

namespace YousignApiClient\Fields;


use YousignApiClient\InvalidArgumentException;

class RadioField
{

    private string $name;
    private int $size;
    private int $x;
    private int $y;

    public function __construct(string $name, int $x, int $y, int $size)
    {

        // Vérification du nom
        if ($name === '' || strlen($name) > 255) {
            throw new \InvalidArgumentException("Mention must be a string between 1 and 255 characters");
        }

        // Vérification de la taille
        if ($size < 8 || $size > 30) {
            throw new \InvalidArgumentException("The size must be an integer between 8 and 30");
        }
        // Vérification des coordonnées
        if ($x < 0 || $y < 0) {
            throw new \InvalidArgumentException("Coordinates (x, y) must be non-negative integers");
        }

        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
        $this->size = $size;
    }


    public function toJson(): string
    {
        return <<< JSON
        {
          "name": "{$this->getName()}",
          "x": {$this->getX()},
          "y": {$this->getY()},
          "size": {$this->getSize()}
        }
        JSON;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): RadioField
    {
        $this->name = $name;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): RadioField
    {
        $this->size = $size;
        return $this;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function setX(int $x): RadioField
    {
        $this->x = $x;
        return $this;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function setY(int $y): RadioField
    {
        $this->y = $y;
        return $this;
    }

}
