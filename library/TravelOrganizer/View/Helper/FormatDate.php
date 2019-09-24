<?php

class TravelOrganizer_View_Helper_FormatDate extends Zend_View_Helper_Abstract
{
    public function formatDate($date = null)
    {
        if ($date) {
            return $this->medium($date);
        }

        return $this;
    }

    /**
     * @param DateTime $date
     *
     * @return string
     */
    public function short($date)
    {
        return $this->createFormatter(IntlDateFormatter::SHORT)->format($date);
    }

    /**
     * @param DateTime $date
     *
     * @return string
     */
    public function medium($date)
    {
        return $this->createFormatter(IntlDateFormatter::MEDIUM)->format($date);
    }

    /**
     * @param DateTime $date
     *
     * @return string
     */
    public function long($date)
    {
        return $this->createFormatter(IntlDateFormatter::LONG)->format($date);
    }

    /**
     * @param DateTime $date
     *
     * @return string
     */
    public function full($date)
    {
        return $this->createFormatter(IntlDateFormatter::FULL)->format($date);
    }

    protected function getLocale()
    {
        return $this->view->translate()->getLocale();
    }

    /**
     * @param int $datetype
     *
     * @return IntlDateFormatter
     */
    protected function createFormatter($datetype)
    {
        return new IntlDateFormatter($this->getLocale(), $datetype, IntlDateFormatter::NONE);
    }
}
