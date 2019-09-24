<?php

use Symfony\Component\Security\Core\User\UserInterface;

class Application_Model_User implements UserInterface
{
    /** @var int */
    protected $_id;
    /** @var string */
    protected $_username;
    /** @var string */
    protected $_password;
    /** @var string */
    protected $_password_salt;
    /** @var string */
    protected $_full_name;
    /** @var string */
    protected $_locale;

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
     * @return Application_Model_User
     */
    public function setId($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param string $username
     *
     * @return Application_Model_User
     */
    public function setUsername($username)
    {
        $this->_username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param string $password
     *
     * @return Application_Model_User
     */
    public function setPassword($password)
    {
        $this->_password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->_password_salt;
    }

    /**
     * @param string $password_salt
     *
     * @return Application_Model_User
     */
    public function setPasswordSalt($password_salt)
    {
        $this->_password_salt = $password_salt;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->_full_name;
    }

    /**
     * @param string $full_name
     *
     * @return Application_Model_User
     */
    public function setFullName($full_name)
    {
        $this->_full_name = $full_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     * @param string $locale
     *
     * @return Application_Model_User
     */
    public function setLocale($locale)
    {
        $this->_locale = $locale;

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return $this->getPasswordSalt();
    }

    public function eraseCredentials()
    {
    }
}
