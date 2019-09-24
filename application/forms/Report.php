<?php

class Application_Form_Report extends TravelOrganizer_Form_AbstractForm
{
    public function init()
    {
        $this->setMethod('post');

        $this->addElement('text', 'year', array(
            'label'      => 'label.year',
            'required'   => false,
            'readonly'   => true,
        ));

        $this->addElement('text', 'number', array(
            'label'      => 'label.number',
            'required'   => true,
            'validators' => array('Int'),
        ));

        $this->addElement('text', 'start_date', array(
            'label'      => 'label.startDate',
            'required'   => true,
            'validators' => array(
                new Zend_Validate_Date(array('format' => 'yyyy-mm-dd')),
            ),
        ));

        $this->addElement('text', 'end_date', array(
            'label'      => 'label.endDate',
            'required'   => true,
            'validators' => array(
                new Zend_Validate_Date(array('format' => 'yyyy-mm-dd')),
            ),
        ));

        $this->addElement('text', 'occasion', array(
            'label'      => 'label.occasion',
            'required'   => true,
            'validators' => array(
                new Zend_Validate_StringLength(array('max' => 255))
            )
        ));

        $this->addElement('text', 'destination', array(
            'label'      => 'label.destination',
            'required'   => true,
            'validators' => array(
                new Zend_Validate_StringLength(array('max' => 255))
            )
        ));

        $this->addElement('text', 'classification', array(
            'label'      => 'label.classification',
            'required'   => false,
            'validators' => array(
                new Zend_Validate_StringLength(array('max' => 255))
            )
        ));

        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));

        $this->setDecorators(array(
            array('ViewScript', array(
                'viewScript' => 'report/_form.phtml'
            )),
            'Form',
        ));
    }
}

