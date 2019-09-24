<?php

namespace TravelOrganizer\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AuthController extends AbstractController
{
    private $translate;

    public function __construct(\Zend_Translate $translate)
    {
        $this->translate = $translate;
    }

    /**
     * @Route("/auth/login", name="auth_login", methods={"GET", "POST"})
     */
    public function loginAction(Request $request): Response
    {
        if (\Zend_Auth::getInstance()->hasIdentity()) {
            return $this->redirectToRoute('zend');
        }

        $error = null;
        $form = new \Application_Form_Login();

        if ($request->isMethod(Request::METHOD_POST) && $form->isValid($request->request->all())) {
            $adapter = new \TravelOrganizer_Auth_DbTableAdapter(
                $form->getValue('username'),
                $form->getValue('password')
            );

            $auth = \Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                return $this->redirectToRoute('zend');
            }

            $error = implode("\n", array_map([$this->translate, 'translate'], $result->getMessages()));
        }

        return $this->render('login.html.twig', [
            'form' => $form,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/auth/logout", name="auth_logout", methods={"GET"})
     */
    public function logoutAction(): Response
    {
        \Zend_Auth::getInstance()->clearIdentity();

        return $this->redirectToRoute('auth_login');
    }
}
