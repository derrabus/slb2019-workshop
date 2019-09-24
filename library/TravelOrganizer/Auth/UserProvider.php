<?php

final class TravelOrganizer_Auth_UserProvider
{
    /** @var self */
    private static $instance;

    /** @var Application_Model_User|null */
    private $user;

    public static function init()
    {
        self::$instance = new self();

        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            return;
        }

        $user = new Application_Model_User();
        $mapper = new Application_Model_UserMapper();
        $mapper->findByUsername($auth->getIdentity(), $user);

        if ($user->getId()) {
            self::$instance->user = $user;
        } else {
            $auth->clearIdentity();
        }
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    public function getCurrentUser(): ?Application_Model_User
    {
        return $this->user;
    }

    private function __construct()
    {
    }
}
