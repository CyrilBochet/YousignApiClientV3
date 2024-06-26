<?php

namespace YousignApiClient;

use InvalidArgumentException;
use YousignApiClient\Fields\Field;
use YousignApiClient\Fields\RadioField;
use YousignApiClient\Fields\RadioGroup;
use YousignApiClient\Fields\SignatureField;

class Approver
{
    private ?string $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private ?string $phoneNumber;
    private string $locale;


    public function __construct(string $firstName, string $lastName, string $email, ?string $phoneNumber, string $locale)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->locale = $locale;

        // Vérification du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address");
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): Approver
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Approver
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Approver
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Approver
    {
        $this->email = $email;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): Approver
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): Approver
    {
        $this->locale = $locale;
        return $this;
    }


    public function toJson(): bool|string
    {

        return <<< JSON
             {
                "info": {
                    "first_name": "{$this->getFirstName()}",
                    "last_name": "{$this->getLastName()}",
                    "email": "{$this->getEmail()}",
                    "phone_number": "{$this->getPhoneNumber()}",
                    "locale": "{$this->getLocale()}"
                }
            }
        JSON;
    }
}
