<?php

class Application_Model_Expense
{
    const TYPE_TRANSPORTATION = 'transportation';
    const TYPE_PER_DIEM_ALLOWANCE = 'per_diem';
    const TYPE_ACCOMMODATION = 'accommodation';
    const TYPE_ANCILLARY = 'ancillary';

    public static $VALID_TYPES = [
        self::TYPE_TRANSPORTATION,
        self::TYPE_PER_DIEM_ALLOWANCE,
        self::TYPE_ACCOMMODATION,
        self::TYPE_ANCILLARY,
    ];

    /** @var int */
    protected $_id;
    /** @var int */
    protected $_report_id;
    /** @var string */
    protected $_type;
    /** @var DateTime */
    protected $_date;
    /** @var string */
    protected $_description;
    /** @var float */
    protected $_grossAmount;
    /** @var float */
    protected $_taxAmount;

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
     * @return Application_Model_Expense
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getReportId()
    {
        return $this->_report_id;
    }

    /**
     * @param int $report_id
     *
     * @return Application_Model_Expense
     */
    public function setReportId($report_id)
    {
        $this->_report_id = $report_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     *
     * @return Application_Model_Expense
     */
    public function setType($type)
    {
        $this->_type = $type;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param DateTime $date
     *
     * @return Application_Model_Expense
     */
    public function setDate($date)
    {
        $this->_date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $description
     *
     * @return Application_Model_Expense
     */
    public function setDescription($description)
    {
        $this->_description = $description;

        return $this;
    }

    /**
     * @return float
     */
    public function getGrossAmount()
    {
        return $this->_grossAmount;
    }

    /**
     * @param float $gross
     *
     * @return Application_Model_Expense
     */
    public function setGrossAmount($gross)
    {
        $this->_grossAmount = $gross;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->_taxAmount;
    }

    /**
     * @param float $tax
     *
     * @return Application_Model_Expense
     */
    public function setTaxAmount($tax)
    {
        $this->_taxAmount = $tax;

        return $this;
    }

    public function getNetAmount()
    {
        return $this->_grossAmount - $this->_taxAmount;
    }
}

