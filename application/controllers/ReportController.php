<?php

class ReportController extends Zend_Controller_Action
{
    /** @var Application_Model_ReportMapper */
    private $_reportMapper;

    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('login', 'auth');
        }

        $this->_reportMapper = new Application_Model_ReportMapper();
    }

    public function indexAction()
    {
        $year = intval($this->getParam('year', date('Y')));

        $this->view->year = $year;
        $this->view->reports = $this->_reportMapper->fetchByYearAndOwner($year, TravelOrganizer_Auth_UserProvider::getInstance()->getCurrentUser()->getId());
    }

    public function createAction()
    {
        $currentYear = intval(date('Y'));
        $year = intval($this->getParam('year', $currentYear));

        if ($year < $currentYear - 4 || $year > $currentYear) {
            throw new Zend_Controller_Action_Exception('Year out of range', 404);
        }

        $defaultDate = new DateTime();
        $defaultDate->setDate($year, $defaultDate->format('m'), $defaultDate->format('d'));

        $form = new Application_Form_Report();
        $form->setDefaults(array(
            'year' => $year,
            'start_date' => $defaultDate->format('Y-m-d'),
            'end_date' => $defaultDate->format('Y-m-d'),
        ));

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $report = new Application_Model_Report();
            $report
                ->setYear($year)
                ->setOwnerId(TravelOrganizer_Auth_UserProvider::getInstance()->getCurrentUser()->getId())
            ;

            $this->mapFormToModel($form, $report);

            $this->_reportMapper->save($report);
            $this->_helper->redirector('view', 'report', null, array('id' => $report->getId()));
        } else {
            $this->view->form = $form;
        }
    }

    public function viewAction()
    {
        $report = $this->findReport();

        $this->view->report = $report;

        $expenseMapper = new Application_Model_ExpenseMapper();
        $this->view->expenses = $expenseMapper->fetchForReport($report->getId());
        $this->view->totals = $expenseMapper->fetchTotalsForReport($report->getId());
    }

    public function exportAction()
    {
        $report = $this->findReport();

        $this->view->report = $report;

        $expenseMapper = new Application_Model_ExpenseMapper();
        $this->view->expenses = $expenseMapper->fetchForReport($report->getId());
        $this->view->totals = $expenseMapper->fetchTotalsForReport($report->getId());

        $filename = sprintf('report-%s.pdf', $report->getShortcut());

        $renderer = new TravelOrganizer_Export_PdfRenderer();

        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', sprintf('inline; filename=%s', $filename));
        $response->appendBody($renderer->renderDocument(
            $this->view->translate('caption.report', array($report->getShortcut())),
            $filename,
            $this->view->render('report/export.phtml')
        ));
    }

    public function editAction()
    {
        $report = $this->findReport();
        $this->view->report = $report;

        $form = new Application_Form_Report();
        $form->setDefaults(array(
            'year' => $report->getYear(),
            'number' => $report->getNumber(),
            'start_date' => $report->getStartDate()->format('Y-m-d'),
            'end_date' => $report->getEndDate()->format('Y-m-d'),
            'occasion' => $report->getOccasion(),
            'destination' => $report->getDestination(),
            'classification' => $report->getClassification(),
        ));

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $this->mapFormToModel($form, $report);

            $this->_reportMapper->save($report);
            $this->_helper->redirector('view', 'report', null, array('id' => $report->getId()));
        } else {
            $this->view->form = $form;
        }
    }

    /**
     * @return Application_Model_Report
     */
    protected function findReport()
    {
        $report = new Application_Model_Report();
        if ($this->hasParam('shortcut')) {
            if (ereg('([0-9]{4})-([0-9]{3})', $this->getParam('shortcut'), $regs)) {
                $this->_reportMapper->findByYearAndNumber($regs[1], $regs[2], $report);
                if ($report->getId()) {
                    if ($report->getOwnerId() != TravelOrganizer_Auth_UserProvider::getInstance()->getCurrentUser()->getId()) {
                        throw new Zend_Controller_Action_Exception('Access denied', 404);
                    }
                } else {
                    throw new Zend_Controller_Action_Exception('Report not found', 404);
                }
            } else {
                throw new Zend_Controller_Action_Exception('Invalid Shortcut', 404);
            }
        } elseif ($this->hasParam('id')) {
            $this->_reportMapper->find(intval($this->getRequest()->getParam('id')), $report);

            if ($report->getId()) {
                if ($report->getOwnerId() != TravelOrganizer_Auth_UserProvider::getInstance()->getCurrentUser()->getId()) {
                    throw new Zend_Controller_Action_Exception('Access denied', 404);
                }
            } else {
                throw new Zend_Controller_Action_Exception('Report not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Missing ID', 404);
        }

        return $report;
    }

    /**
     * @param Zend_Form $form
     * @param Application_Model_Report $report
     */
    protected function mapFormToModel(Zend_Form $form, Application_Model_Report $report)
    {
        $data = $form->getValues();
        $report
            ->setStartDate(DateTime::createFromFormat('Y-m-d', $data['start_date']))
            ->setEndDate(DateTime::createFromFormat('Y-m-d', $data['end_date']))
        ;

        foreach (array('number', 'occasion', 'destination', 'classification') as $field) {
            call_user_func(array($report, 'set'.ucfirst($field)), $data[$field]);
        }
    }


}




