<?php

class Application_Model_ExpenseMapper
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
            $this->setDbTable('Application_Model_DbTable_Expense');
        }
        return $this->_dbTable;
    }

    /**
     * @param Application_Model_Expense $expense
     */
    public function save(Application_Model_Expense $expense)
    {
        $data = array(
            'report_id' => $expense->getReportId(),
            'type' => $expense->getType(),
            'date' => $expense->getDate()->format('Y-m-d'),
            'description' => $expense->getDescription(),
            'gross' => $expense->getGrossAmount(),
            'tax' => $expense->getTaxAmount()
        );

        if (null === ($id = $expense->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    /**
     * @param int                       $id
     * @param Application_Model_Expense $expense
     */
    public function find($id, Application_Model_Expense $expense)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $this->mapRowToModel($result->current(), $expense);
    }

    /**
     * @param int $reportId
     *
     * @return Application_Model_Expense[][]
     */
    public function fetchForReport($reportId)
    {
        $resultSet = $this->getDbTable()->fetchAll(['report_id = ?' => $reportId], ['date']);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Expense();
            $this->mapRowToModel($row, $entry);
            $entries[$row->type][] = $entry;
        }
        return $entries;
    }

    /**
     * @param int $reportId
     *
     * @return array
     */
    public function fetchTotalsForReport($reportId)
    {
        $result = $this->getDbTable()
            ->select(Zend_Db_Table_Abstract::SELECT_WITH_FROM_PART)
            ->columns(array(
                'type',
                'SUM(gross) as gross',
                'SUM(tax) as tax',
                'SUM(gross - tax) as net'
            ))
            ->where('report_id = ?', $reportId)
            ->group('type')
            ->query(PDO::FETCH_ASSOC)
        ;

        $indexedResult = [];
        foreach ($result as $row) {
            $type = $row['type'];
            unset($row['type']);
            $indexedResult[$type] = $row;
        }

        $indexedResult['total'] = $this->getDbTable()
            ->select(Zend_Db_Table_Abstract::SELECT_WITH_FROM_PART)
            ->columns(array(
                'SUM(gross) as gross',
                'SUM(tax) as tax',
                'SUM(gross - tax) as net'
            ))
            ->where('report_id = ?', $reportId)
            ->query()
            ->fetch(PDO::FETCH_ASSOC)
        ;

        return $indexedResult;
    }

    private function mapRowToModel(Zend_Db_Table_Row_Abstract $row, Application_Model_Expense $model)
    {
        $model
            ->setId(intval($row->id))
            ->setReportId(intval($row->report_id))
            ->setType($row->type)
            ->setDate(DateTime::createFromFormat('Y-m-d', $row->date))
            ->setDescription($row->description)
            ->setGrossAmount(floatval($row->gross))
            ->setTaxAmount(floatval($row->tax))
        ;
    }
}

