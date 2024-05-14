<?php

namespace YousignApiClient\Webhook;

class Webhook
{
    private ?string $id;
    private bool $sandbox;
    private bool $autoRetry;
    private bool $enabled;
    private string $endpoint;
    private ?string $secretKey;
    private string $description;
    private array $events;
    private array $scopes;

    public function __construct(bool $sandbox, bool $autoRetry, bool $enabled, string $endpoint, string $description, array $events, array $scopes)
    {

        if (empty($events)) {
            throw new \InvalidArgumentException("Webhook must contains at least one WebhookEvent object");
        }

        foreach ($events as $event) {
            if (!$event instanceof WebhookEvent) {
                throw new \InvalidArgumentException("Webhook can only contains WebhookEvent object");
            }
        }

        $this->sandbox = $sandbox;
        $this->autoRetry = $autoRetry;
        $this->enabled = $enabled;
        $this->endpoint = $endpoint;
        $this->description = $description;
        $this->events = $events;
        $this->scopes = $scopes;
    }


    public function toJson(): string
    {
        $sandbox = $this->sandbox ? 'true' : 'false';
        $autoRetry = $this->autoRetry ? 'true' : 'false';
        $enabled = $this->enabled ? 'true' : 'false';

        $events = $this->getEvents();
        $countEvents = count($events);
        $eventJson = "";

        foreach ($events as $index => $event) {
            $eventJson .= '"' . $event->getEventName() . '"';
            if ($index < $countEvents - 1) {
                $eventJson .= ',';
            }
        }

        $scopes = $this->getScopes();
        $countScopes = count($scopes);
        $scopeJson = "";

        foreach ($scopes as $index => $scope) {
            $scopeJson .= '"' . $scope->getScopeName() . '"';
            if ($index < $countScopes - 1) {
                $scopeJson .= ',';
            }
        }

        return <<< JSON
        {
          "endpoint": "{$this->getEndpoint()}",     
          "description": "{$this->getDescription()}",
          "sandbox": $sandbox,
          "subscribed_events": [$eventJson],
          "scopes": [$scopeJson],
          "auto_retry": $autoRetry,
          "enabled": $enabled
        }
        JSON;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Webhook
    {
        $this->id = $id;
        return $this;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function addEvent(WebhookEvent $event): self
    {
        if (!in_array($event, $this->events, true)) {
            $this->events[] = $event;
        }
        return $this;
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function setSandbox(bool $sandbox): Webhook
    {
        $this->sandbox = $sandbox;
        return $this;
    }

    public function isAutoRetry(): bool
    {
        return $this->autoRetry;
    }

    public function setAutoRetry(bool $autoRetry): Webhook
    {
        $this->autoRetry = $autoRetry;
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): Webhook
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): Webhook
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function getSecretKey(): ?string
    {
        return $this->secretKey;
    }

    public function setSecretKey(?string $secretKey): Webhook
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Webhook
    {
        $this->description = $description;
        return $this;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes): Webhook
    {
        $this->scopes = $scopes;
        return $this;
    }

}