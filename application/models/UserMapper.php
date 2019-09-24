<?php

class Application_Model_UserMapper
{
    protected $_dbTable;

    /**
     * @param string|Zend_Db_Table_Abstract $dbTable
     *
     * @return $this
     *
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
            $this->setDbTable('Application_Model_DbTable_User');
        }

        return $this->_dbTable;
    }

    /**
     * @param string                 $username
     * @param Application_Model_User $user
     *
     * @throws Zend_Db_Table_Exception
     */
    public function findByUsername($username, Application_Model_User $user)
    {
        $result = $this->getDbTable()->fetchRow($this->getDbTable()->select()->where('username = ?', $username));
        if (!$result) {
            return;
        }
        $this->mapRowToModel($result, $user);
    }

    protected function mapRowToModel(Zend_Db_Table_Row_Abstract $row, Application_Model_User $user)
    {
        $user
            ->setId($row->id)
            ->setUsername($row->username)
            ->setPassword($row->password)
            ->setPasswordSalt($row->password_salt)
            ->setFullName($row->full_name)
            ->setLocale($row->locale)
        ;
    }
}
