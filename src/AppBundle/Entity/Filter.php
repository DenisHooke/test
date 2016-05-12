<?php
namespace AppBundle\Entity;


use Symfony\Component\VarDumper\VarDumper;

class Filter
{

    private $repo;
    private $fields;

    public function __construct($fields, $repo)
    {
        $this->fields = $fields;
        $this->repo   = $repo;
    }

    public function create()
    {

        $values = array();

       foreach($this->fields as $code => $filedName) {

           $items = $this->repo->getGroupValues($code);
           $values[$code] = array(
               "name" => $filedName,
               "values" =>  $items
           );
       }

        return $values;
    }

    public function convertValue($value)
    {
        $value = (array)json_decode($value);

        foreach($value as $fieldCode => $criteria) {

            if (sizeof($value[$fieldCode]) == 0) {
                unset($value[$fieldCode]);
            }

        }

        return $value;
    }

}