<?php

namespace YousignApiClient\Fields;


use YousignApiClient\Font;

class MentionField extends Field
{

    private string $mention;
    private Font $font;
    private int $height;
    private int $width;

    public function __construct(string $mention, Font $font, string $documentId, int $page, int $x, int $y, int $height = 24, int $width = 24)
    {
        parent::__construct($documentId, $page, $x, $y);

        // Vérification de la mention
        if ($mention === '' || strlen($mention) > 255) {
            throw new \InvalidArgumentException("Mention must be a string between 1 and 255 characters");
        }

        // Vérification de la hauteur
        if ($height !== 24 && ($height % 15 !== 0 || $height < 24)) {
            throw new \InvalidArgumentException("Height must be equal to 24 or a multiple of 15 greater than 24");
        }

        // Vérification de la largeur
        if ($width < 24 || $width > 2147483647) {
            throw new \InvalidArgumentException("Width must be an integer between 24 and 2147483647");
        }

        $this->mention = $mention;
        $this->font = $font;
        $this->height = $height;
        $this->width = $width;
    }


    public function toJson(): string
    {

        $font = $this->font->toJson();
        $signerIdJson = $this->getSignerIdJson();
        $documentId = $this->getDocumentIdJson();

        return <<< JSON
        {
          "type": "mention",
          "height": {$this->getHeight()},
          "width": {$this->getWidth()},
          "page": {$this->getPage()},
          "x": {$this->getX()},
          "y": {$this->getY()},
          $signerIdJson
          "mention": "{$this->getMention()}",
          $font
          $documentId
        }
        JSON;
    }

    public function getMention(): string
    {
        return $this->mention;
    }

    public function setMention(string $mention): MentionField
    {
        $this->mention = $mention;
        return $this;
    }

    public function getFont(): Font
    {
        return $this->font;
    }

    public function setFont(Font $font): MentionField
    {
        $this->font = $font;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): MentionField
    {
        $this->height = $height;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): MentionField
    {
        $this->width = $width;
        return $this;
    }

}
