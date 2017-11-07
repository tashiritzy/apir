<?php
namespace Prj\Model;

//validation
use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Prj implements InputFilterAwareInterface
{
    public $id;
    public $prjname;
    public $clientname;

    
    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->prjname = !empty($data['prjname']) ? $data['prjname'] : null;
        $this->clientname  = !empty($data['clientname']) ? $data['clientname'] : null;
        $this->prjmanagername  = !empty($data['prjmanagername']) ? $data['prjmanagername'] : null;
        $this->prjstartdate  = !empty($data['prjstartdate']) ? $data['prjstartdate'] : null;
    }
    
    // Get Array Hydrator
    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'prjname' => $this->prjname,
            'clientname'  => $this->clientname,
            'prjmanagername'  => $this->prjmanagername,
            'prjstartdate'  => $this->prjstartdate,
        ];
    }

    

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        // adding a required filter
        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        //filters- StripTags and StringTrim, to remove unwanted HTML and unnecessary white space
        $inputFilter->add([
            'name' => 'prjname',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'clientname',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
?>
