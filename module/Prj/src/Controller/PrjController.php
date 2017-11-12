<?php
namespace Prj\Controller;

use Prj\Model\PrjTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Prj\Form\PrjForm;
use Prj\Model\Prj;

use Zend\View\Model\JsonModel;

use Zend\Paginator\Adapter\DbSelect;

class PrjController extends AbstractActionController
{
    
    private $table;

    //Prj table constructor
    public function __construct(PrjTable $table)
    {
        $this->table = $table;
        
        //$this->tableGateway = $tableGateway;
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
	    $paginator->setItemCountPerPage(10);
	
	    return new ViewModel(['paginator' => $paginator]);
    }
    
    //read data for after ajax view
    public function readrecordAction()
    {
    	 
    	    // Grab the paginator from the PrjTable:
	    $paginator = $this->table->fetchAll(true);
	
	    // Set the current page to what has been passed in query string,
	    // or to 1 if none is set, or the page is invalid:
	    $page = (int) $this->params()->fromQuery('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $paginator->setCurrentPageNumber($page);
	
	    // Set the number of items per page to 10:
	    $paginator->setItemCountPerPage(10);
	
	    return new ViewModel(['paginator' => $paginator]);
    }
    
    //read data to populate edit modal
    public function readprjdetailsAction()
    {
    	if ($this->getRequest()->isXmlHttpRequest()) 
	{
		ob_start();
    	    
		//$id = (int) $this->params()->fromRoute('id', 0);

    	    	$request = $this->getRequest();
    	    	$response = $this->getResponse();
    	    	
		$id = (int) $request->getPost('id');
		
		// Retrieve the prj with the specified id. Doing so raises
		// an exception if the prj is not found, which should result
		// in redirecting to the landing page.
		try {
		    $data = $this->table->getPrj($id);
		} catch (\Exception $e) {
		    //return $this->redirect()->toRoute('prj', ['action' => 'index']);
		    $data = "Couldnt retrieve data";
		    
		}
		ob_end_clean();
		//echo json_encode($data);
		
		$response->setContent(\Zend\Json\Json::encode(array('response' => $data)));
		return $response;
    	}    
    }

    public function addAction()
    {
	    if ($this->getRequest()->isXmlHttpRequest()) 
	    {
		if ($this->getRequest()->isPost()) 
		{
			ob_start();

			$request = $this->getRequest();
			$response = $this->getResponse();
		
			//if (! $request->isPost()) {
			   // return ['form' => $form];
			//}
			
			//pass form data
			$prj = new Prj();
			
			//receive form data
			$data = array('prjname' => $request->getPost('prjname'),
			    'clientname' => $request->getPost('clientname'),
			    'prjmanagername' => $request->getPost('prjmanagername'),
			    'prjstartdate' => $request->getPost('prjstartdate'));
			
			//save record
			$prj->exchangeArray($data);
			$this->table->savePrj($prj);
			
			//var_dump($this);
			
			// right before outputting the JSON, clear the buffer.
			ob_end_clean();
			
			$response->setContent(\Zend\Json\Json::encode(array('response' => $data)));
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
    	    
    	    if ($this->getRequest()->isXmlHttpRequest()) 
	    {
		if ($this->getRequest()->isPost()) 
		{
			ob_start();
			
			$request = $this->getRequest();
			$response = $this->getResponse();
			
			$id = (int) $request->getPost('hidden_id');
			
			$prj = $this->table->getPrj($id);
			
			//receive form data
			$data = array('prjname' => $request->getPost('eprjname'),
			    'clientname' => $request->getPost('eclientname'),
			    'prjmanagername' => $request->getPost('eprjmanagername'),
			    'prjstartdate' => $request->getPost('eprjstartdate'),
			    'id' => $request->getPost('hidden_id'));
					
			//save record
			$prj->exchangeArray($data);
			$this->table->savePrj($prj);
			
			// right before outputting the JSON, clear the buffer.
			ob_end_clean();
			
			$response->setContent(\Zend\Json\Json::encode(array('response' => $data)));
			return $response;
		}
	
	    }
	    else
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
    }

    public function deleteAction()
    {
	if ($this->getRequest()->isXmlHttpRequest()) 
	{
		//if ($this->getRequest()->isPost()) 
		//{
			$request = $this->getRequest();
			$id = (int) $request->getPost('id');
			
			$this->table->deletePrj($id);
			
			
		//}
	}
	else
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
}

?>
