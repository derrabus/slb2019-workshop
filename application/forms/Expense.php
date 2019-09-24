<?php

class Application_Form_Expense extends TravelOrganizer_Form_AbstractForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('hidden', 'reportId', [
            'ignore' => true,
        ]);

        $this->addElement('text', 'date', [
            'label' => 'label.date',
            'required' => true,
            'validators' => [
                new Zend_Validate_Date(['format' => 'yyyy-mm-dd']),
            ],
        ]);

        $this->addElement('text', 'description', [
            'label' => 'label.description',
            'required' => true,
            'validators' => [
                new Zend_Validate_StringLength(['max' => 255]),
            ],
        ]);

        $this->addElement('text', 'gross', [
            'label' => 'label.grossAmount',
            'required' => true,
            'validators' => ['Float'],
        ]);

        $this->addElement('text', 'tax', [
            'label' => 'label.taxAmount',
            'required' => false,
            'validators' => ['Float'],
        ]);

        $this->addElement('hash', 'csrf', [
            'ignore' => true,
        ]);

        $this->setDecorators([
            ['ViewScript', [
                'viewScript' => 'expense/_form.phtml',
            ]],
            'Form',
        ]);
    }
}
