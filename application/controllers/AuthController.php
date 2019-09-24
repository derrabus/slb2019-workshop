<?php

class AuthController extends Zend_Controller_Action
{
    public function loginAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            return $this->_helper->redirector('index', 'index');
        }

        $form = new Application_Form_Login();

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $adapter = new TravelOrganizer_Auth_DbTableAdapter(
                $form->getValue('username'),
                $form->getValue('password')
            );

            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                return $this->_helper->redirector('index', 'index');
            }

            $this->view->error = implode("\n", array_map([$this->view, 'translate'], $result->getMessages()));
        }

        $this->view->form = $form;
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login');
    }
}
