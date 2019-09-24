<?php

namespace TravelOrganizer\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class DemoController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('demo.html.twig');
    }
}
