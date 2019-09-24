<?php

class Application_Model_ReportMapper
{
    protected $_dbTable;

    /**
     * @param string|Zend_Db_Table_Abstract $dbTable
     *
     * @return $this
     * @throws Exception
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Report');
        }
        return $this->_dbTable;
    }

    /**
     * @param Application_Model_Report $report
     */
    public function save(Application_Model_Report $report)
    {
        $data = array(
            'owner_id' => $report->getOwnerId(),
            'start_date' => $report->getStartDate()->format('Y-m-d'),
            'end_date' => $report->getEndDate()->format('Y-m-d'),
        );

        foreach (['year', 'number', 'occasion', 'destination', 'classification'] as $field) {
            $data[$field] = $report->{'get' . ucfirst($field)}();
        }

        if (null === ($id = $report->getId())) {
            unset($data['id']);
            $report->setId($this->getDbTable()->insert($data));
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    /**
     * @param int                      $id
     * @param Application_Model_Report $report
     */
    public function find($id, Application_Model_Report $report)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $this->mapRowToModel($result->current(), $report);
    }

    public function findByYearAndNumber($year, $number, Application_Model_Report $report)
    {
        $result = $this->getDbTable()->fetchRow(
            $this->getDbTable()
                ->select()
                ->where('year = ?', $year)
                ->where('number = ?', $number)
                ->where('owner_id = ?', TravelOrganizer_Auth_UserProvider::getInstance()->getCurrentUser()->getId())
        );

        if (!$result) {
            return;
        }
        $this->mapRowToModel($result, $report);
    }

    /**
     * @return Application_Model_Report[]
     *
     * @throws Exception
     */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Report();
            $this->mapRowToModel($row, $entry);
            $entries[] = $entry;
        }
        return $entries;
    }

    /**
     * @param int $year
     * @param int $owner
     *
     * @return Application_Model_Report[]
     */
    public function fetchByYearAndOwner($year, $owner)
    {
        $resultSet = $this->getDbTable()->fetchAll(['year = ?' => $year, 'owner_id = ?' => $owner]);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Report();
            $this->mapRowToModel($row, $entry);
            $entries[] = $entry;
        }
        return $entries;
    }

    private function mapRowToModel(Zend_Db_Table_Row_Abstract $row, Application_Model_Report $model)
    {
        $model
            ->setId(intval($row->id))
            ->setOwnerId(intval($row->owner_id))
            ->setYear(intval($row->year))
            ->setNumber(intval($row->number))
            ->setStartDate(DateTime::createFromFormat('Y-m-d', $row->start_date))
            ->setEndDate(DateTime::createFromFormat('Y-m-d', $row->end_date))
            ->setOccasion($row->occasion)
            ->setDestination($row->destination)
            ->setClassification($row->classification)
        ;
    }
}

