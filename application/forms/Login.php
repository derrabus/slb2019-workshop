<?php

class Application_Form_Login extends TravelOrganizer_Form_AbstractForm
{

    public function init()
    {
        $this->setMethod('post');

        $this->addElement(
            'text', 'username', array(
            'label' => 'label.username',
            'required' => true,
            'filters'    => array('StringTrim'),
        ));

        $this->addElement('password', 'password', array(
            'label' => 'label.password',
            'required' => true,
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'label.sign_in',
        ));
    }


}

