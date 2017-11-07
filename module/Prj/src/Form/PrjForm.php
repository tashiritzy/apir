<?php
namespace Prj\Form;

use Zend\Form\Form;

class PrjForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('prj');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'prjname',
            'type' => 'text',
            'options' => [
                'label' => 'Project Name',
            ],
        ]);
        $this->add([
            'name' => 'clientname',
            'type' => 'text',
            'options' => [
                'label' => 'Client Name',
            ],
        ]);
        $this->add([
            'name' => 'prjmanagername',
            'type' => 'text',
            'options' => [
                'label' => 'Project Manager Name',
            ],
        ]);
        $this->add([
            'name' => 'prjstartdate',
            'type' => 'text',
            'options' => [
                'label' => 'Project Start',
                'id'    => 'datepicker',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
?>
