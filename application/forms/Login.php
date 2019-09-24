<?php

class Application_Form_Login extends TravelOrganizer_Form_AbstractForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement(
            'text', 'username', [
            'label' => 'label.username',
            'required' => true,
            'filters' => ['StringTrim'],
        ]);

        $this->addElement('password', 'password', [
            'label' => 'label.password',
            'required' => true,
        ]);

        $this->addElement('submit', 'submit', [
            'ignore' => true,
            'label' => 'label.sign_in',
        ]);
    }
}
