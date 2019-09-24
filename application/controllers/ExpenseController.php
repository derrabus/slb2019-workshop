<?php

class ExpenseController extends Zend_Controller_Action
{
    /** @var Application_Model_ReportMapper */
    protected $_reportMapper;
    /** @var Application_Model_ExpenseMapper */
    protected $_expenseMapper;

    public function init()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('login', 'auth');
        }

        $this->_reportMapper = new Application_Model_ReportMapper();
        $this->_expenseMapper = new Application_Model_ExpenseMapper();
    }

    public function createAction()
    {
        if ($this->hasParam('report')) {
            $report = $this->findReport(intval($this->getRequest()->getParam('report')));

            if ($report->getId()) {
                $this->view->report = $report;

                $expenseType = $this->getParam('type');
                if (is_string($expenseType) && in_array($expenseType, Application_Model_Expense::$VALID_TYPES)) {
                    $this->view->expenseType = $expenseType;
                    $form = new Application_Form_Expense();
                    $form->setDefaults([
                        'reportId' => $report->getId(),
                        'date' => date('Y-m-d'),
                        'gross' => .0,
                        'tax' => .0,
                    ]);

                    $this->view->form = $form;

                    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
                        $expense = new Application_Model_Expense();
                        $expense
                            ->setReportId($report->getId())
                            ->setType($expenseType)
                        ;

                        $this->mapFormToModel($form, $expense);

                        $this->_expenseMapper->save($expense);

                        $this->_helper->redirector('view', 'report', null, [
                            'id' => $report->getId(),
                        ]);
                    }
                } else {
                    throw new Zend_Controller_Action_Exception('Invalid expense type', 404);
                }
            } else {
                throw new Zend_Controller_Action_Exception('Report not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Missing report ID', 404);
        }
    }

    public function editAction()
    {
        if ($this->hasParam('id')) {
            $expenseId = intval($this->getParam('id'));
            $expense = new Application_Model_Expense();

            $this->_expenseMapper->find($expenseId, $expense);
            if ($expense->getId()) {
                $report = $this->findReport($expense->getReportId());
                $this->view->report = $report;
                $this->view->expenseType = $expense->getType();
                $numberFilter = new Zend_Filter_NormalizedToLocalized(['precision' => 2]);

                $form = new Application_Form_Expense();
                $form->setDefaults([
                    'reportId' => $expense->getReportId(),
                    'date' => $expense->getDate()->format('Y-m-d'),
                    'description' => $expense->getDescription(),
                    'gross' => $numberFilter->filter($expense->getGrossAmount()),
                    'tax' => $numberFilter->filter($expense->getTaxAmount()),
                ]);

                $this->view->form = $form;

                if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
                    $this->mapFormToModel($form, $expense);
                    $this->_expenseMapper->save($expense);

                    $this->_helper->redirector('view', 'report', null, [
                        'id' => $report->getId(),
                    ]);
                }
            } else {
                throw new Zend_Controller_Action_Exception('Expense not found', 404);
            }
        } else {
            throw new Zend_Controller_Action_Exception('Missing expense ID', 404);
        }
    }

    /**
     * @param int $reportId
     *
     * @return Application_Model_Report
     */
    protected function findReport($reportId)
    {
        $report = new Application_Model_Report();
        $this->_reportMapper->find($reportId, $report);

        return $report;
    }

    /**
     * @param Zend_Form                 $form
     * @param Application_Model_Expense $expense
     */
    protected function mapFormToModel(Zend_Form $form, Application_Model_Expense $expense)
    {
        $numberFilter = new Zend_Filter_LocalizedToNormalized();
        $data = $form->getValues();

        $expense
            ->setDate(DateTime::createFromFormat('Y-m-d', $data['date']))
            ->setDescription($data['description'])
            ->setGrossAmount($numberFilter->filter($data['gross']))
            ->setTaxAmount($numberFilter->filter($data['tax']))
        ;
    }
}
