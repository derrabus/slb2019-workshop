<?php

class Application_Model_Report
{
    /** @var int */
    protected $_id;
    /** @var int */
    protected $_owner_id;
    /** @var int */
    protected $_year;
    /** @var int */
    protected $_number;
    /** @var DateTime */
    protected $_start_date;
    /** @var DateTime */
    protected $_end_date;
    /** @var string */
    protected $_occasion;
    /** @var string */
    protected $_destination;
    /** @var string */
    protected $_classification;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     *
     * @return Application_Model_Report
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->_owner_id;
    }

    /**
     * @param int $owner_id
     *
     * @return Application_Model_Report
     */
    public function setOwnerId($owner_id)
    {
        $this->_owner_id = $owner_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->_year;
    }

    /**
     * @param int $year
     *
     * @return Application_Model_Report
     */
    public function setYear($year)
    {
        $this->_year = $year;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->_number;
    }

    /**
     * @param int $number
     *
     * @return Application_Model_Report
     */
    public function setNumber($number)
    {
        $this->_number = $number;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->_start_date;
    }

    /**
     * @param DateTime $start_date
     *
     * @return Application_Model_Report
     */
    public function setStartDate($start_date)
    {
        $this->_start_date = $start_date;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->_end_date;
    }

    /**
     * @param DateTime $end_date
     *
     * @return Application_Model_Report
     */
    public function setEndDate($end_date)
    {
        $this->_end_date = $end_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getOccasion()
    {
        return $this->_occasion;
    }

    /**
     * @param string $occasion
     *
     * @return Application_Model_Report
     */
    public function setOccasion($occasion)
    {
        $this->_occasion = $occasion;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->_destination;
    }

    /**
     * @param string $destination
     *
     * @return Application_Model_Report
     */
    public function setDestination($destination)
    {
        $this->_destination = $destination;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassification()
    {
        return $this->_classification;
    }

    /**
     * @param string $classification
     *
     * @return Application_Model_Report
     */
    public function setClassification($classification)
    {
        $this->_classification = $classification;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortcut()
    {
        return sprintf('%d-%\'.03d', $this->_year, $this->_number);
    }
}
