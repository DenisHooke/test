<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Track;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\VarDumper\VarDumper;

class TrackRepository extends EntityRepository
{

    public function getGroupValues($field)
    {

        $sql = "SELECT $field FROM tracks GROUP BY $field";

        $resultMapping = new ResultSetMapping();
        $resultMapping->addScalarResult($field, $field);

        return $this->getEntityManager()->createNativeQuery($sql, $resultMapping)->getResult();
    }
}