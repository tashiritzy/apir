<?php
namespace Prj\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PrjTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
    	    if ($paginated) {
            return $this->fetchPaginatedResults();
        } 
    	    
    	    return $this->tableGateway->select();
    }
    
    private function fetchPaginatedResults()
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());
        $select->order('id DESC');

        // Create a new result set based on the Prj entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Prj());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
            // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function getPrj($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function savePrj(Prj $prj)
    {
        $data = [
            'prjname' => $prj->prjname,
            'clientname'  => $prj->clientname,
            'prjmanagername' => $prj->prjmanagername,
            'prjstartdate' => $prj->prjstartdate,
        ];

        $id = (int) $prj->id;

        if ($id === 0) {
            $data['created_at'] = date("Y-m-d H:i:s");
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getPrj($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }
        
        $data['updated_at'] = date("Y-m-d H:i:s");
        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deletePrj($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}

?>
