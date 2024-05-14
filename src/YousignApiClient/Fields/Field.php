<?php

namespace YousignApiClient\Fields;

use InvalidArgumentException;

class Field
{

    protected string $documentId;
    protected int $page;
    protected int $x;
    protected int $y;

    /**
     * @var string|null $signerId is only required when adding a field outside the Signer object
     */
    protected ?string $signerId = null;


    public function __construct(string $documentId, int $page, int $x, int $y)
    {

        // Vérification de l'ID du document
        if (empty($documentId)) {
            throw new InvalidArgumentException("Document ID is required");
        }

        // Vérification de la page
        if ($page <= 0) {
            throw new InvalidArgumentException("Page number must be a positive integer");
        }

        // Vérification des coordonnées
        if ($x < 0 || $y < 0) {
            throw new InvalidArgumentException("Coordinates (x, y) must be non-negative integers");
        }

        $this->documentId = $documentId;
        $this->page = $page;
        $this->x = $x;
        $this->y = $y;
    }

    public function getSignerIdJson() : string
    {
        $signerId = $this->getSignerId();
        return $signerId !== null ? '"signer_id": "' . $signerId . '"' : '';
    }
    public function getSignerId(): ?string
    {
        return $this->signerId;
    }

    public function setSignerId(?string $signerId) : Field
    {
        $this->signerId = $signerId;
        return $this;
    }

    public function getDocumentIdJson() : string
    {
        return '"document_id": "' . $this->getDocumentId() . '"';
    }
    public function getDocumentId(): string
    {
        return $this->documentId;
    }

    public function setDocumentId(string $documentId): Field
    {
        $this->documentId = $documentId;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): Field
    {
        $this->page = $page;
        return $this;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function setX(int $x): Field
    {
        $this->x = $x;
        return $this;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function setY(int $y): Field
    {
        $this->y = $y;
        return $this;
    }

}