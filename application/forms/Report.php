<?php

class Application_Form_Report extends TravelOrganizer_Form_AbstractForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'year', [
            'label' => 'label.year',
            'required' => false,
            'readonly' => true,
        ]);

        $this->addElement('text', 'number', [
            'label' => 'label.number',
            'required' => true,
            'validators' => ['Int'],
        ]);

        $this->addElement('text', 'start_date', [
            'label' => 'label.startDate',
            'required' => true,
            'validators' => [
                new Zend_Validate_Date(['format' => 'yyyy-mm-dd']),
            ],
        ]);

        $this->addElement('text', 'end_date', [
            'label' => 'label.endDate',
            'required' => true,
            'validators' => [
                new Zend_Validate_Date(['format' => 'yyyy-mm-dd']),
            ],
        ]);

        $this->addElement('text', 'occasion', [
            'label' => 'label.occasion',
            'required' => true,
            'validators' => [
                new Zend_Validate_StringLength(['max' => 255]),
            ],
        ]);

        $this->addElement('text', 'destination', [
            'label' => 'label.destination',
            'required' => true,
            'validators' => [
                new Zend_Validate_StringLength(['max' => 255]),
            ],
        ]);

        $this->addElement('text', 'classification', [
            'label' => 'label.classification',
            'required' => false,
            'validators' => [
                new Zend_Validate_StringLength(['max' => 255]),
            ],
        ]);

        $this->addElement('hash', 'csrf', [
            'ignore' => true,
        ]);

        $this->setDecorators([
            ['ViewScript', [
                'viewScript' => 'report/_form.phtml',
            ]],
            'Form',
        ]);
    }
}
