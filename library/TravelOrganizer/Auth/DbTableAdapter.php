<?php

class TravelOrganizer_Auth_DbTableAdapter implements Zend_Auth_Adapter_Interface
{
    protected $_username;
    protected $_password;

    public function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $code = $this->_doAuthenticate();

        return new Zend_Auth_Result($code, $this->_username, $code > Zend_Auth_Result::FAILURE ? array() : array('message.invalid_credentials'));
    }

    /**
     * @return int
     */
    protected function _doAuthenticate()
    {
        $dbtable = new Application_Model_DbTable_User();

        $row = $dbtable
            ->select(Zend_Db_Table_Abstract::SELECT_WITH_FROM_PART)
            ->columns(array('username', 'password', 'password_salt'))
            ->where('username = ?', $this->_username)
            ->query()
            ->fetch(PDO::FETCH_ASSOC)
        ;

        if (!$row) {
            return Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
        }

        if (md5($this->_password . $row['password_salt']) != $row['password']) {
            return Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
        }

        return Zend_Auth_Result::SUCCESS;
    }
}
