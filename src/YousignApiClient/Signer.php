<?php

namespace YousignApiClient;

use InvalidArgumentException;
use YousignApiClient\Fields\Field;
use YousignApiClient\Fields\RadioField;
use YousignApiClient\Fields\RadioGroup;
use YousignApiClient\Fields\SignatureField;

class Signer
{
    private ?string $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private ?string $phoneNumber;
    private string $locale;
    private ?string $signatureAuthenticationMode;
    private string $signatureLevel;
    private array $fields = [];

    private const OTP_SMS = 'otp_sms';
    private const OTP_EMAIL = 'otp_email';
    private const NO_OTP = 'no_otp';

    private const SIMPLE_LEVEL = 'electronic_signature';
    private const ADVANCED_LEVEL = 'advanced_electronic_signature';
    private const QUALIFIED_LEVEL = 'qualified_electronic_signature';

    public function __construct(string $firstName, string $lastName, string $email, ?string $phoneNumber, string $locale, ?string $signatureAuthenticationMode, ?string $signatureLevel)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->locale = $locale;
        $this->signatureAuthenticationMode = $signatureAuthenticationMode;
        $this->signatureLevel = $signatureLevel;

        // Vérification du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email address");
        }

        // Vérification du mode d'authentification saisie
        if ($signatureLevel === self::QUALIFIED_LEVEL && $signatureAuthenticationMode !== null) {
            throw new InvalidArgumentException("Authentication mode must be null for qualified level");
        } elseif($signatureLevel === self::ADVANCED_LEVEL){
            $this->signatureAuthenticationMode = self::OTP_SMS;
        } elseif ($signatureAuthenticationMode !== null && !in_array($signatureAuthenticationMode, [self::OTP_SMS, self::OTP_EMAIL, self::NO_OTP], true)) {
            throw new InvalidArgumentException("Invalid authentication mode");
        }

        // Vérification si OTP_SMS : téléphone obligatoire
        if ($signatureAuthenticationMode === self::OTP_SMS && empty($phoneNumber)) {
            throw new InvalidArgumentException("Phone required");
        }

        // Vérification du niveau saisi
        if (!in_array($signatureLevel, [self::SIMPLE_LEVEL, self::ADVANCED_LEVEL, self::QUALIFIED_LEVEL], true)) {
            throw new InvalidArgumentException("Invalid signature level");
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): Signer
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Signer
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Signer
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Signer
    {
        $this->email = $email;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): Signer
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): Signer
    {
        $this->locale = $locale;
        return $this;
    }

    public function getSignatureAuthenticationMode(): ?string
    {
        return $this->signatureAuthenticationMode;
    }

    public function setSignatureAuthenticationMode(?string $signatureAuthenticationMode): Signer
    {
        $this->signatureAuthenticationMode = $signatureAuthenticationMode;
        return $this;
    }

    public function getSignatureLevel(): string
    {
        return $this->signatureLevel;
    }

    public function setSignatureLevel(string $signatureLevel): Signer
    {
        $this->signatureLevel = $signatureLevel;
        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
    public function addField(Field|RadioGroup $field): self
    {
        if (!in_array($field, $this->fields, true)) {
            $this->fields[] = $field;
        }
        return $this;
    }


    public function toJson(): bool|string
    {
        $fieldsJson = null;
        $fields = $this->getFields();

        if (!empty($fields)){

            $count = count($fields);

            /**
             * @var  $index
             * @var  $field
             */
            foreach ($fields as $index => $field) {
                $fieldsJson .= $field->toJson();
                // Ajouter une virgule si ce n'est pas le dernier élément
                if ($index < $count - 1) {
                    $fieldsJson .= ',';
                }
            }
        }
        $mode = $this->getSignatureAuthenticationModeJson();

        return <<< JSON
             {
                "info": {
                    "first_name": "{$this->getFirstName()}",
                    "last_name": "{$this->getLastName()}",
                    "email": "{$this->getEmail()}",
                    "phone_number": "{$this->getPhoneNumber()}",
                    "locale": "{$this->getLocale()}"
                },
                "fields": [$fieldsJson],
                $mode
                "signature_level": "{$this->getSignatureLevel()}"
            }
        JSON;
    }

    public function getSignatureAuthenticationModeJson() : string
    {
        return $this->getSignatureAuthenticationMode() !== null ? '"signature_authentication_mode": "' . $this->getSignatureAuthenticationMode() . '",' : '';
    }
}
