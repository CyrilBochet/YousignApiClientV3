<?php

namespace YousignApiClient\Fields;


class CheckboxField extends Field
{

    private int $size;
    private string $name;
    private bool $checked;
    private bool $optional;


    public function __construct(int $size, string $name, bool $checked, bool $optional, string $documentId, int $page, int $x, int $y)
    {
        parent::__construct($documentId, $page, $x, $y);

        // Vérification de la mention
        if ($name === '' || strlen($name) > 255) {
            throw new \InvalidArgumentException("The name must be a string between 1 and 255 characters");
        }

        // Vérification de la taille
        if ($size < 8 || $size > 30 ) {
            throw new \InvalidArgumentException("The size must be an integer between 8 and 30");
        }
        $this->size = $size;
        $this->name = $name;
        $this->checked = $checked;
        $this->optional = $optional;
    }


    public function toJson(): string
    {
        $checked = $this->checked ? 'true' : 'false';
        $optional = $this->optional ? 'true' : 'false';

        $documentIdJson = $this->getDocumentIdJson();
        return <<< JSON
        {
          "type": "checkbox",
          "size": {$this->getSize()},
          "page": {$this->getPage()},
          "x": {$this->getX()},
          "y": {$this->getY()},
          "signer_id": "{$this->getSignerId()}",
          "name": "{$this->getName()}",
          "checked": $checked,
          $documentIdJson,
          "optional": $optional
        }
        JSON;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): CheckboxField
    {
        $this->size = $size;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CheckboxField
    {
        $this->name = $name;
        return $this;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): CheckboxField
    {
        $this->checked = $checked;
        return $this;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): CheckboxField
    {
        $this->optional = $optional;
        return $this;
    }

}
