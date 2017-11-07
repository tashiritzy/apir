<?php
namespace Prj\Controller;

use Prj\Model\PrjTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Prj\Form\PrjForm;
use Prj\Model\Prj;

class PrjController extends AbstractActionController
{
    
    private $table;

    //Prj table constructor
    public function __construct(PrjTable $table)
    {
        $this->table = $table;
    }
	
    public function indexAction()
    {
    	    // Grab the paginator from the PrjTable:
	    $paginator = $this->table->fetchAll(true);
	
	    // Set the current page to what has been passed in query string,
	    // or to 1 if none is set, or the page is invalid:
	    $page = (int) $this->params()->fromQuery('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $paginator->setCurrentPageNumber($page);
	
	    // Set the number of items per page to 10:
	    $paginator->setItemCountPerPage(3);
	
	    return new ViewModel(['paginator' => $paginator]);
    }

    public function addAction()
    {
    	    

	    if ($this->getRequest()->isXmlHttpRequest()) 
	    {
		if ($this->getRequest()->isPost()) 
		{
			//ajax request controller
			
			//$this->_helper->layout('homelayout')->disableLayout();
			$response = $this->getResponse();
			$response->setStatusCode(200);
           	    
			$form = new PrjForm();
			$form->get('submit')->setValue('Add');
		
			$request = $this->getRequest();
			$data = $request->getPost('prjname');
		
			if (! $request->isPost()) {
			    return ['form' => $form];
			}
			
			//pass form data
			$prj = new Prj();
			//$form->setInputFilter($prj->getInputFilter());
			//$form->setData($request->getPost());
		
			//check for invalid form elements
			//if (! $form->isValid()) {
			    //return ['form' => $form];
			//}
			
			//var_dump($form);
		
			//save and redirect
			//$prj->exchangeArray($form->getData());
			//$this->table->savePrj($prj);
			
			$response->setContent($data);
			return $response;
		}
	    } 
	    else 
	    {
		//regular controller logic goes here
		
		$form = new PrjForm();
		$form->get('submit')->setValue('Add');
	
		$request = $this->getRequest();
	
		if (! $request->isPost()) {
		    return ['form' => $form];
		}
		
		//pass form data
		$prj = new Prj();
		$form->setInputFilter($prj->getInputFilter());
		$form->setData($request->getPost());
	
		//check for invalid form elements
		if (! $form->isValid()) {
		    return ['form' => $form];
		}
	
		//save and redirect
		$prj->exchangeArray($form->getData());
		$this->table->savePrj($prj);
		return $this->redirect()->toRoute('prj');
		
	    }
		
    }

    public function editAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('prj', ['action' => 'add']);
        }

        // Retrieve the prj with the specified id. Doing so raises
        // an exception if the prj is not found, which should result
        // in redirecting to the landing page.
        try {
            $prj = $this->table->getPrj($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('prj', ['action' => 'index']);
        }

        $form = new PrjForm();
        $form->bind($prj);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($prj->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->savePrj($prj);

        // Redirect to prj list
        return $this->redirect()->toRoute('prj', ['action' => 'index']);
    }

    public function deleteAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('prj');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deletePrj($id);
            }

            // Redirect to list of projects
            return $this->redirect()->toRoute('prj');
        }

        return [
            'id'    => $id,
            'prj' => $this->table->getPrj($id),
        ];
    }
}

?>
