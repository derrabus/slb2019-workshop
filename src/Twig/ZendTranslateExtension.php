<?php

namespace TravelOrganizer\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class ZendTranslateExtension extends AbstractExtension
{
    private $translate;

    public function __construct(\Zend_Translate $translate)
    {
        $this->translate = $translate;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('zendTranslate', [$this, 'translate']),
        ];
    }

    public function translate(string $message, array $params = []): string
    {
        $translatedMessage = $this->translate->translate($message);

        if ($params) {
            $translatedMessage = sprintf($translatedMessage, ...$params);
        }

        return $translatedMessage;
    }
}
