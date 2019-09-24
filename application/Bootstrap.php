<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initErrorHandler()
    {
        set_error_handler(static function($severity, $message, $file, $line) {
            if (error_reporting() & $severity) {
                throw new ErrorException($message, 0, $severity, $file, $line);
            }
        });
    }

    protected function _initAutoload()
    {
        Zend_Loader_Autoloader::getInstance()->registerNamespace('TravelOrganizer_');
    }

    protected function _initTranslate()
    {
        Zend_Registry::set('Zend_Locale', new Zend_Locale('de_DE'));

        Zend_Registry::set('Zend_Translate', new Zend_Translate(array(
            'adapter' => 'xliff',
            'content' => realpath(dirname(__FILE__).'/../language/'),
        )));
    }

    protected function _bootstrap($resource = null)
    {
        parent::_bootstrap($resource);

        if (null === $resource) {
            TravelOrganizer_Auth_UserProvider::init();
        }
    }
}
