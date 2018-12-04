<?php

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class IrEntityRepository extends EntityRepository
{
    public function findByAttributes($entityAttributes, $queryParameters)
    {
        $queryBuilder = $this->makeQueryBuilderAttributes($entityAttributes, $queryParameters);

        $query = $queryBuilder->getQuery();

        return $query->execute();
    }

    public function makeQueryBuilderAttributes($entityAttributes, $queryParameters)
    {
        $limit = $queryParameters['limit'];
        $offset = $queryParameters['offset'];
        $orderBy = $queryParameters['orderBy'];
        $direction = $queryParameters['direction'];

        if ($direction === null) $direction = 'DESC';

        $queryBuilder = $this->createQueryBuilder('ir_entity');

        $i = 1;
        foreach ($entityAttributes as $attribute => $value) {
            $queryBuilder
                ->andWhere('ir_entity.'.$attribute.' = ?'.$i)
                ->setParameter($i, $value);
            $i++;
        }
        if (!($orderBy === null)) {
            $queryBuilder->orderBy('ir_entity.'.$orderBy, $direction);
        }
        if (!($limit === null)) {
            $queryBuilder->setMaxResults($limit);
        }
        if (!($offset === null)) {
            $queryBuilder->setFirstResult($offset);
        }

        return $queryBuilder;
    }

}
