<?php

namespace YousignApiClient\Webhook;

class Scope
{
    private string $scopeName;

    public const ALL = '*';
    public const APP = 'app';
    public const PUBLIC_API = 'public_api';
    public const CONNECTOR_SALESFORCE_API = 'connector_salesforce_api';
    public const CONNECTOR_HUBSPOT_API = 'connector_hubspot_api';


    public function __construct(string $scopeName)
    {
        // Vérification du scope
        if (!in_array($scopeName, [self::ALL, self::APP, self::PUBLIC_API, self::CONNECTOR_SALESFORCE_API, self::CONNECTOR_HUBSPOT_API], true)) {
            throw new \InvalidArgumentException("Invalid scope");
        }
        $this->scopeName = $scopeName;

    }

    public function getScopeName(): string
    {
        return $this->scopeName;
    }

    public function setScopeName(string $scopeName): Scope
    {
        $this->scopeName = $scopeName;
        return $this;
    }


}