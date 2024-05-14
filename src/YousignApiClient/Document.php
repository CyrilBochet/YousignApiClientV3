<?php

namespace YousignApiClient;

class Document
{
    private ?string $id;
    private string $path;
    private string $nature;
    private string $parseAnchors;

    private const NATURE_ATTACHMENT = 'attachment';
    private const NATURE_SIGNABLE_DOCUMENT = 'signable_document';

    public function __construct(string $nature, string $path, bool $parseAnchors)
    {
        // Vérification de la nature du document
        if (!in_array($nature, [self::NATURE_ATTACHMENT, self::NATURE_SIGNABLE_DOCUMENT], true)) {
            throw new \InvalidArgumentException("Invalid document nature");
        }
        $this->nature = $nature;
        $this->path = $path;
        $this->parseAnchors = $parseAnchors ? 'true' : 'false';

    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Document
    {
        $this->id = $id;
        return $this;
    }

    public function getNature(): string
    {
        return $this->nature;
    }

    public function setNature(string $nature): Document
    {
        $this->nature = $nature;
        return $this;
    }
    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): Document
    {
        $this->path = $path;
        return $this;
    }

    public function getParseAnchors(): string
    {
        return $this->parseAnchors;
    }

    public function setParseAnchors(string $parseAnchors): Document
    {
        $this->parseAnchors = $parseAnchors;
        return $this;
    }

}