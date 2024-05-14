<?php

namespace YousignApiClient\Fields;

use YousignApiClient\Font;

class ReadOnlyField extends Field
{
    private int $maxLength;
    private string $question;
    private ?string $instruction = null;
    private bool $optional;
    private Font $font;
    private int $height;
    private int $width;

    public function __construct(int $maxLength, string $question, bool $optional, Font $font, string $documentId, int $page, int $x, int $y, int $height = 24, int $width = 24, int $signerId)
    {
        parent::__construct($documentId, $page, $x, $y);

        // Vérification de la taille max
        if ($maxLength < 1 || $maxLength > 32767) {
            throw new \InvalidArgumentException("Max length must be an integer between 1 and 32767");
        }

        // Vérification de la question
        if ($question === '' || strlen($question) > 255) {
            throw new \InvalidArgumentException("Mention must be a string between 1 and 255 characters");
        }

        // Vérification de la hauteur
        if ($height < 24 || $height > 2147483647) {
            throw new \InvalidArgumentException("Height must be an integer between 24 and 2147483647");
        }

        // Vérification de la largeur
        if ($width < 24 || $width > 2147483647) {
            throw new \InvalidArgumentException("Width must be an integer between 24 and 2147483647");
        }

        // Vérification de l'ID du signataire
        if (empty($signerId)) {
            throw new \InvalidArgumentException("Signer ID is required");
        }

        $this->maxLength = $maxLength;
        $this->question = $question;
        $this->optional = $optional;
        $this->font = $font;
        $this->height = $height;
        $this->width = $width;
    }

    public function toJson(): string
    {
        $font = $this->font->toJson();
        $optional = $this->optional ? 'true' : 'false';
        $instructionJson = $this->instruction !== null ? '"instruction": "' . $this->instruction . '",' : '"instruction": null,';

        return <<< JSON
        {
          "type": "text",
          "height": {$this->getHeight()},
          "width": {$this->getWidth()},
          "page": {$this->getPage()},
          "x": {$this->getX()},
          "y": {$this->getY()},
          "signer_id": "{$this->getSignerId()}",
          "max_length": $this->maxLength,
          "question": "$this->question",
          $instructionJson,
          $font
          "optional": $optional
        }
        JSON;
    }

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    public function setMaxLength(int $maxLength): ReadOnlyField
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): ReadOnlyField
    {
        $this->question = $question;
        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(?string $instruction): ReadOnlyField
    {
        $this->instruction = $instruction;
        return $this;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): ReadOnlyField
    {
        $this->optional = $optional;
        return $this;
    }

    public function getFont(): Font
    {
        return $this->font;
    }

    public function setFont(Font $font): ReadOnlyField
    {
        $this->font = $font;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): ReadOnlyField
    {
        $this->height = $height;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): ReadOnlyField
    {
        $this->width = $width;
        return $this;
    }

}