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

       foreach($this->fields as $filed) {

           $items = $this->repo->getGroupValues($filed);
           $values[$filed] = $items;
       }

        return $values;
    }

}