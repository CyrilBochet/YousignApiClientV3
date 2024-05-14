<?php

namespace YousignApiClient\Fields;

use YousignApiClient\Font;

class TextField extends Field
{
    private int $maxLength;
    private string $question;
    private ?string $instruction = null;
    private bool $optional;
    private Font $font;
    private int $height;
    private int $width;

    public function __construct(int $maxLength, string $question, bool $optional, Font $font, string $documentId, int $page, int $x, int $y, int $height = 24, int $width = 24)
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
        if (($height < 24 || $height > 2147483647) && $height % 15 !== 0) {
            throw new \InvalidArgumentException("Height must be an integer between 24 and 2147483647 and a multiple of 15");
        }

        // Vérification de la largeur
        if ($width < 24 || $width > 2147483647) {
            throw new \InvalidArgumentException("Width must be an integer between 24 and 2147483647");
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
        $fontJson = $this->font->toJson();
        $optional = $this->optional ? 'true' : 'false';
        $documentId = $this->getDocumentIdJson();
        $instructionJson = $this->instruction !== null ? '"instruction": "' . $this->instruction . '",' : '"instruction": null,';

        return <<< JSON
        {
          $documentId,
          "type": "text",
          "height": {$this->getHeight()},
          "width": {$this->getWidth()},
          "page": {$this->getPage()},
          "x": {$this->getX()},
          "y": {$this->getY()},
          "max_length": {$this->getMaxLength()},
          $fontJson
          "question": "{$this->getQuestion()}",
          $instructionJson
          "optional": $optional
        }
    JSON;
    }


    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    public function setMaxLength(int $maxLength): TextField
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): TextField
    {
        $this->question = $question;
        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(?string $instruction): TextField
    {
        $this->instruction = $instruction;
        return $this;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function setOptional(bool $optional): TextField
    {
        $this->optional = $optional;
        return $this;
    }

    public function getFont(): Font
    {
        return $this->font;
    }

    public function setFont(Font $font): TextField
    {
        $this->font = $font;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): TextField
    {
        $this->height = $height;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): TextField
    {
        $this->width = $width;
        return $this;
    }

    // j'ai refait une fonction getSignerIdJson juste pour le textfield car la virgule était mal placée uniquement pour celui-ci
    public function getSignerIdJson() : string
    {
        $signerId = $this->getSignerId();
        return $signerId !== null ? '"signer_id": "' . $signerId . '",' : '';
    }
}