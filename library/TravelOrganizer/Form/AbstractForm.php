<?php

abstract class TravelOrganizer_Form_AbstractForm extends Zend_Form
{
    public function createElement($type, $name, $options = null)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        switch ($type) {
            case 'text':
            case 'password':
                if (!isset($options['decorators'])) {
                    $options['decorators'] = array(
                        'ViewHelper',
                        array('ViewScript', array('viewScript' => '_form_label.phtml', 'placement' => false)),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'form-group')),
                    );
                }

                if (!isset($options['filters'])) {
                    $options['filters'] = array('StringTrim');
                }

                if (!isset($options['attribs']['class'])) {
                    $options['attribs']['class'] = 'form-control';
                }

                break;

            case 'submit':
                if (!isset($options['attribs']['class'])) {
                    $options['attribs']['class'] = 'btn btn-primary';
                }

                break;
        }

        return parent::createElement($type, $name, $options);
    }
}
