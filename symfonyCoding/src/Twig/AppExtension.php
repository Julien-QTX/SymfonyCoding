<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Symfony\Component\Intl\Locales;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension{

    private $localesCodes;
    private $locales;

    public function __construct(string $locales)
    {
        $localeCodes =explode('|', $locales);
        sort($localeCodes);
        $this->localesCodes = $localeCodes;
    }

    public function getFunctions(): array{
        return [
            new TwigFunction('locales', [$this, 'getLocales']),
        ];
    }

    public function getLocales(): array
    {
        if (null!== $this->locales){
            return $this->locales;
        }
        $this->locales =[];

        foreach ($this->localesCodes as $localesCode){
            $this->locales[] =['code' => $localesCode,
                'name' => Locales::getName($localesCode, $localesCode)];

        }

        return $this->locales;
    }
}