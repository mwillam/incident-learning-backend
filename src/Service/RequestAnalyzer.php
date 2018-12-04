<?php

namespace App\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\HttpFoundation\Request;

class RequestAnalyzer
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This function filters query parameters of the request for attributes of
     * given entity. Query parameters which are not an entity attribute are
     * discarded. Returns filtered query item list.
     */
    public function filterEntityAttributes($entityName, Request $request)
    {
        $keys = $request->query->all();
        $queryItems = array();

        $entityMetadata = $this->entityManager->getClassMetadata($entityName);
        $fieldNames = $entityMetadata->getFieldnames();
        $associationMappings = $entityMetadata->getAssociationMappings();

        foreach ($fieldNames as $fieldName) {
            if (array_key_exists($fieldName, $keys)) {
                $queryItems[$fieldName] = $keys[$fieldName];
            }
        }

        foreach($associationMappings as $mappingName => $mapping) {
            if($mapping['type'] == ClassMetadataInfo::MANY_TO_ONE) {
                $fieldName = $mapping['fieldName'];

                if (array_key_exists($fieldName, $keys)) {
                    $queryItems[$fieldName] = $keys[$fieldName];
                }
            }
        }

        return $queryItems;
    }

    /**
     * Filters request parameters for known query keys, returns only recognized fieldnames
     * with valid value. Otherwise returns fieldnames with null value.
     */
    public function filterQueryParameters($entityName, Request $request)
    {
        $queryParameters = array();

        $limit = $request->query->get('limit');
        $offset = $request->query->get('startAt');
        $orderBy = $request->query->get('orderBy');
        $direction = $request->query->get('direction');

        $queryParameters['limit'] = (is_int($limit) && $limit>=0) ? $limit : null;
        $queryParameters['offset'] = (is_int($offset) && $offset>=0) ? $offset : null;
        $queryParameters['orderBy'] = (is_string($orderBy) && $this->isEntityAttribute($entityName, $orderBy)) ? $orderBy : null;
        $queryParameters['direction'] = (is_string($direction) && (strcasecmp($direction, 'asc') || strcasecmp($direction, 'desc'))) ? $direction : 'desc';

        return $queryParameters;
    }

    public function isEntityAttribute($entityName, $attribute)
    {
        $entityMetadata = $this->entityManager->getClassMetadata($entityName);
        $fieldNames = $entityMetadata->getFieldnames();

        return in_array($attribute, $fieldNames);
    }
}
