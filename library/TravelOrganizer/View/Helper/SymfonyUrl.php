<?php

final class TravelOrganizer_View_Helper_SymfonyUrl extends Zend_View_Helper_Abstract
{
    public function symfonyUrl(string $route = null, array $params = [])
    {
        if ($route === null) {
            return $this;
        }

        global $kernel;

        return $kernel
            ->getContainer()
            ->get('router')
            ->generate($route, $params);
    }
}
