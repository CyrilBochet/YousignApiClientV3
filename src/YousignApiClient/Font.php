<?php

namespace YousignApiClient;

class Font
{
    private string $family;
    private string $color;
    private int $size;
    private bool $italic = false;
    private bool $bold = false;
    public const INCONSOLATA = 'Inconsolata';
    public const OPEN_SANS = 'Open Sans';
    public const LATO = 'Lato';
    public const RALEWAY = 'Raleway';
    public const MERRIWEATHER = 'Merriweather';
    public const EB_GARAMOND = 'EB Garamond';
    public const COMIC_NEUE = 'Comic Neue';

    public function __construct(string $family = self::INCONSOLATA, string $color = '#000000', int $size = 12)
    {
        // Vérification de la police
        if (!in_array($family, [
            self::INCONSOLATA,
            self::OPEN_SANS,
            self::LATO,
            self::RALEWAY,
            self::MERRIWEATHER,
            self::EB_GARAMOND,
            self::COMIC_NEUE,
        ], true)) {
            throw new \InvalidArgumentException("Invalid font family");
        }

        $this->family = $family;

        // Vérification de la couleur
        if (!preg_match('/^#([a-f0-9]{6}|[A-F0-9]{6})$/', $color)) {
            throw new \InvalidArgumentException("Invalid color format. Color must be in hexadecimal format (#RRGGBB)");
        }

        $this->color = $color;

        // Vérification de la taille
        if ($size < 8 || $size > 12) {
            throw new \InvalidArgumentException("Size must be between 8 and 12");
        }

        $this->size = $size;
    }

    public function getFamily(): string
    {
        return $this->family;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function toJson()
    {

        $italic = $this->italic ? 'true' : 'false';
        $bold = $this->bold ? 'true' : 'false';
        return <<< JSON
          "font": {
            "family": "$this->family",
            "color":  "$this->color",
            "size": $this->size,
            "variants": {
             "italic": $italic,
             "bold": $bold
            }
          },
        JSON;
    }
}
