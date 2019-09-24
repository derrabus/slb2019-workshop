<?php

namespace TravelOrganizer\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LegacyBridgeController
{
    private $zendApplication;

    public function __construct(\Zend_Application $zendApplication)
    {
        $this->zendApplication = $zendApplication;
    }

    public function __invoke(Request $request, string $zendController, string $zendAction): Response
    {
        $zendRequest = new \Zend_Controller_Request_Http(
            $request->getUri()
        );
        $zendRequest->setQuery($request->query->all());
        $zendRequest->setPost($request->request->all());
        $zendRequest->setControllerName($zendController);
        $zendRequest->setActionName($zendAction);

        /** @var \Zend_Application_Bootstrap_Bootstrap $bootstrap */
        $bootstrap = $this->zendApplication->getBootstrap();
        /** @var \Zend_Controller_Front $frontController */
        $frontController = $bootstrap->getResource('FrontController');

        $frontController->returnResponse(true);
        $frontController->throwExceptions(true);
        $frontController->setRequest($zendRequest);

        try {
            /** @var \Zend_Controller_Response_Http $zendResponse */
            $zendResponse = $bootstrap->run();
        } catch (\Zend_Controller_Action_Exception $e) {
            if (Response::HTTP_NOT_FOUND === $e->getCode()) {
                throw new NotFoundHttpException($e->getMessage(), $e);
            }

            throw $e;
        }

        $response = new Response(
            implode('', (array)$zendResponse->getBody()),
            $zendResponse->getHttpResponseCode()
        );

        foreach ($zendResponse->getRawHeaders() as $header) {
            [$name, $value] = explode(':', $header, 2);
            $response->headers->set($name, trim($value), true);
        }

        foreach ($zendResponse->getHeaders() as $headerInfo) {
            $response->headers->set($headerInfo['name'], $headerInfo['value'], $headerInfo['replace']);
        }

        return $response;
    }
}
