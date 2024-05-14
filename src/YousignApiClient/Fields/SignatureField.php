<?php

namespace YousignApiClient\Fields;


use InvalidArgumentException;

class SignatureField extends Field
{

    private int $height;
    private int $width;


    public function __construct(string $documentId, int $page, int $x, int $y, int $height = 37, int $width = 85)
    {
        parent::__construct($documentId, $page, $x, $y);

        // Vérification de la hauteur
        if ($height !== null && ($height < 37 || $height > 253)) {
            throw new InvalidArgumentException("Height must be an integer between 37 and 253");
        }

        // Vérification de la largeur
        if ($width !== null && ($width < 85 || $width > 580)) {
            throw new InvalidArgumentException("Width must be an integer between 85 and 580");
        }

        $this->height = $height;
        $this->width = $width;

    }

    public function toJson(): string
    {

        $signerIdJson = $this->getSignerIdJson();
        $documentIdJson = $this->getDocumentIdJson();

        return <<< JSON
        {
        $documentIdJson, 
        "type": "signature",
        "height": {$this->getHeight()},
        "width": {$this->getWidth()},
        "page": {$this->getPage()},
        "x": {$this->getX()},
        "y": {$this->getY()}
        }
        JSON;
    }

    public function toArray(): array
    {

        return array(
            "signer_id" => $this->getSignerId(),
            "type" => "signature",
            "height" => $this->getHeight(),
            "width" => $this->getWidth(),
            "page" => $this->getPage(),
            "x" => $this->getX(),
            "y" => $this->getY()
        );
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): SignatureField
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): SignatureField
    {
        $this->height = $height;
        return $this;
    }


}
