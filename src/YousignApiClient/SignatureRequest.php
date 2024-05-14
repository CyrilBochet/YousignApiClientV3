<?php

namespace YousignApiClient;
;

class SignatureRequest
{
    private ?string $id;
    private string $name;
    private string $deliveryMode;
    private string $timezone;
    private bool $orderedSigners;
    private ?string $customExperienceId = null;
    private ?string $externalId = null;
    private ?string $auditTrailLocale = null;
    private bool $signersAllowedToDecline = false;
    private ?\DateTime $expirationDate = null;
    private const DELIVERY_MODE_NONE = 'none';
    private const DELIVERY_MODE_EMAIL = 'email';

    public function __construct(string $name, string $deliveryMode, string $timezone, bool $orderedSigners)
    {
        // Vérification du mode de livraison
        if (!in_array($deliveryMode, [self::DELIVERY_MODE_NONE, self::DELIVERY_MODE_EMAIL], true)) {
            throw new \InvalidArgumentException("Invalid delivery mode");
        }

        // Vérification du fuseau horaire
        $validTimezones = \DateTimeZone::listIdentifiers();
        if (!in_array($timezone, $validTimezones, true)) {
            throw new \InvalidArgumentException("Invalid timezone");
        }

        // Conversion de $orderedSigners en string
        $this->orderedSigners = $orderedSigners;
        $this->name = $name;
        $this->deliveryMode = $deliveryMode;
        $this->timezone = $timezone;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): SignatureRequest
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): SignatureRequest
    {
        $this->name = $name;
        return $this;
    }

    public function getDeliveryMode(): string
    {
        return $this->deliveryMode;
    }

    public function setDeliveryMode(string $deliveryMode): SignatureRequest
    {
        $this->deliveryMode = $deliveryMode;
        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): SignatureRequest
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function getOrderedSigners(): bool
    {
        return $this->orderedSigners;
    }

    public function setOrderedSigners(bool $orderedSigners): SignatureRequest
    {
        $this->orderedSigners = $orderedSigners;
        return $this;
    }

    public function getCustomExperienceId(): ?string
    {
        return $this->customExperienceId;
    }

    public function setCustomExperienceId(?string $customExperienceId): SignatureRequest
    {
        $this->customExperienceId = $customExperienceId;
        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): SignatureRequest
    {
        $this->externalId = $externalId;
        return $this;
    }

    public function getAuditTrailLocale(): ?string
    {
        return $this->auditTrailLocale;
    }

    public function setAuditTrailLocale(?string $auditTrailLocale): SignatureRequest
    {
        $this->auditTrailLocale = $auditTrailLocale;
        return $this;
    }

    public function getSignersAllowedToDecline(): bool
    {
        return $this->signersAllowedToDecline;
    }

    public function setSignersAllowedToDecline(bool $signersAllowedToDecline): SignatureRequest
    {
        $this->signersAllowedToDecline = $signersAllowedToDecline;
        return $this;
    }

    public function getExpirationDate(): ?\DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTime $expirationDate): SignatureRequest
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function toJson(): string
    {
        $orderedSigners = $this->orderedSigners ? 'true' : 'false';
        $signersAllowedToDecline = $this->signersAllowedToDecline ? 'true' : 'false';
        return <<< JSON
        {
          "name": "$this->name",
          "delivery_mode":"$this->deliveryMode",
          "timezone": "$this->timezone",
          "ordered_signers": $orderedSigners,
          "custom_experience_id": "$this->customExperienceId",
          "signers_allowed_to_decline": $signersAllowedToDecline
        }
        JSON;
    }
}
