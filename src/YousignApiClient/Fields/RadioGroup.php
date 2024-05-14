<?php

namespace YousignApiClient\Fields;


use YousignApiClient\InvalidArgumentException;

class RadioGroup
{

    private ?string $signerId;
    private string $documentId;
    private int $page;
    private string $name;
    private bool $optional;
    private array $radios;


    public function __construct(string $documentId, int $page, string $name, bool $optional, array $radios)
    {
        // Vérification de la mention
        if ($name === '' || strlen($name) > 255) {
            throw new \InvalidArgumentException("The name must be a string between 1 and 255 characters");
        }

        // Vérification de la page
        if ($page <= 0) {
            throw new \InvalidArgumentException("Page number must be a positive integer");
        }

        if (empty($radios)){
            throw new \InvalidArgumentException("RadioGroup must contains at least one RadioField object");
        }

        foreach ($radios as $radio) {
            if (!$radio instanceof RadioField) {
                throw new \InvalidArgumentException("Radios can only contains RadioField object");
            }
        }

        $this->documentId = $documentId;
        $this->page = $page;
        $this->name = $name;
        $this->optional = $optional;
        $this->radios = $radios;
    }


    public function toJson(): string
    {
        $radiosJson = '';
        $radios = $this->getRadios();
        $count = count($radios);

        $documentId = $this->getDocumentIdJson();

        /**
         * @var  $index
         * @var  $radio
         * @var RadioField $radio
         */
        foreach ($radios as $index => $radio) {
            $radiosJson .= $radio->toJson();
            // Ajouter une virgule si ce n'est pas le dernier élément
            if ($index < $count - 1) {
                $radiosJson .= ',';
            }
        }

        $optional = $this->optional ? 'true' : 'false';
        return <<< JSON
        {     
          $documentId,
          "type": "radio_group",  
          "page": {$this->getPage()},
          "name": "{$this->getName()}",
          "optional": $optional,
          "radios":[$radiosJson]
        }
        JSON;
    }

    public function getSignerId(): string
    {
        return $this->signerId;
    }

    public function setSignerId(string $signerId): RadioGroup
    {
        $this->signerId = $signerId;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): RadioGroup
    {
        $this->page = $page;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): RadioGroup
    {
        $this->name = $name;
        return $this;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): RadioGroup
    {
        $this->optional = $optional;
        return $this;
    }

    public function getRadios(): array
    {
        return $this->radios;
    }

    public function addRadioToArray(RadioField $radioField): self
    {
        if (!in_array($radioField, $this->radios, true)) {
            $this->$radioField[] = $radioField;
        }
        return $this;
    }

    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    public function setDocumentId(string $documentId): RadioGroup
    {
        $this->documentId = $documentId;
        return $this;
    }

    public function getDocumentIdJson() : string
    {
        return '"document_id": "' . $this->getDocumentId() . '"';
    }
}
