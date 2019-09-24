<?php

class Application_Form_Expense extends TravelOrganizer_Form_AbstractForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('hidden', 'reportId', array(
            'ignore' => true
        ));

        $this->addElement('text', 'date', array(
            'label'      => 'label.date',
            'required'   => true,
            'validators' => array(
                new Zend_Validate_Date(array('format' => 'yyyy-mm-dd')),
            ),
        ));

        $this->addElement('text', 'description', array(
            'label'      => 'label.description',
            'required'   => true,
            'validators' => array(
                new Zend_Validate_StringLength(array('max' => 255))
            )
        ));

        $this->addElement('text', 'gross', array(
            'label'      => 'label.grossAmount',
            'required'   => true,
            'validators' => array('Float'),
        ));

        $this->addElement('text', 'tax', array(
            'label'      => 'label.taxAmount',
            'required'   => false,
            'validators' => array('Float'),
        ));

        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

        $this->setDecorators(array(
            array('ViewScript', array(
                'viewScript' => 'expense/_form.phtml'
            )),
            'Form',
        ));
    }
}

