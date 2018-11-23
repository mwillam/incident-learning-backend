<?php

namespace App\Serializer;

use \Doctrine\Common\Util\ClassUtils;
use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Mapping\ClassMetadataInfo;

use \ReflectionProperty;

class Serializer
{
    private $entityManager;

    public function __construct(EntityManager $entityManager, $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function serialize($mainData)
    {
        if($mainData === null) {
            throw new \InvalidArgumentException('null must not be passed to serialize() because we can\'t know whether no entity or no list of entities was found.');
        }

        $response = new \stdClass();

        if(is_array($mainData)) {
            $data = array();
            foreach($mainData as $entity) {
                $data[] = $this->serializeEntity($entity);
            }
            $response->data = $data;
        } else {
            $response->data = $this->serializeEntity($mainData);
        }

        return json_encode($response, JSON_PRETTY_PRINT);
    }

    public function serializeEntity($entity)
    {
        $reader = function & ($object, $property) {
            $value = & \Closure::bind(function & () use ($property) {
                return $this->$property;
            }, $object, $object)->__invoke();

            return $value;
        };


        $entityClass = ClassUtils::getClass($entity);
        $entityMetadata = $this->entityManager->getClassMetadata($entityClass);

        // prepare data
        $attributes = $this->serializeEntityAttributes($entity, $entityMetadata);
        $id = $attributes['id'];
        unset($attributes['id']);

        $resourceType = $this->getResourceType($entityClass);
        $resourceNameForRoute = $this->getResourceNameForRoute($entityClass);


        $associationMappings = $entityMetadata->getAssociationMappings();

        $relationships = array();
        foreach($associationMappings as $mappingName => $mapping) {
            if($mapping['type'] == ClassMetadataInfo::MANY_TO_ONE) {
                $fieldName = $mapping['fieldName'];
                $relatedObject = & $reader($entity, $fieldName);


                $relationships[$this->dasherize($fieldName)] = array(
                    'data' => array(
                        // TODO: don't use public getter
                        'id' => $relatedObject->getId(),
                        'type' => $this->getResourceType($mapping['targetEntity'])
                    )
                );
            }
        }

        $data = array(
            'type' => $resourceType,
            'id' => $id,
            'attributes' => $attributes,
        );

        if (!empty($relationships)) {
            $data['relationships'] = $relationships;
        }


        $links = array();
        $selfRoute = 'get_'.$resourceNameForRoute;
        if($this->router->getRouteCollection()->get($selfRoute) !== null) {
            $links['self'] = $this->router->generate($selfRoute, array($resourceNameForRoute.'Id' => $id));
        }

        if(!empty($links)) {
            $data['links'] = $links;
        }

        return $data;

    }

    private function serializeEntityAttributes($entity, $entityMetadata)
    {
        $reader = function & ($object, $property) {
            $reflect = new \ReflectionClass($object);

            if($object instanceof \Doctrine\ORM\Proxy\Proxy) {
                $parentReflect = $reflect->getParentClass();
                $property = $parentReflect->getProperty($property);
            } else {
                $property = $reflect->getProperty($property);
            }
            $property->setAccessible(true);
            $value = $property->getValue($object);

            return $value;
        };

        $fieldNames = $entityMetadata->getFieldnames();
        $attributes = array();
        foreach($fieldNames as $fieldName) {
            $value = $reader($entity, $fieldName);
            if(!is_null($value)) {
                $attributes[$this->dasherize($fieldName)] = $value;
            }
        }

        return $attributes;
    }

    public function deserialize($content)
    {
        $decoded = json_decode($content);

        if(empty($decoded) || empty($decoded->data)) {
            throw new \Exception("Could not decode content");
        }

        $data = $decoded->data;
        if(is_array($data)) {
            $entities = array();
            foreach($data as $entityData) {
                $entities[] = $this->deserializeEntity($entityData);
            }

            return $entities;
        } else {
            return $this->deserializeEntity($data);
        }
    }

    private function deserializeEntity($entityData)
    {
        $writer = function ($object, $property, $value) {
            \Closure::bind(function () use ($property, $value) {
                $this->$property = $value;
            }, $object, $object)->__invoke();
        };

        if(empty($entityData->type)) {
                throw new \Exception("Resource type is empty");
            }
            $entityClass = $this->getEntityClass($entityData->type);
            $entityMetadata = $this->entityManager->getClassMetadata($entityClass);
            $fieldNames = $entityMetadata->getFieldnames();
            $associationMappings = $entityMetadata->getAssociationMappings();
            $entity = new $entityClass;

            foreach($fieldNames as $fieldName) {
                $dasherizedFieldname = $this->dasherize($fieldName);
                if(!empty($entityData->attributes->{$dasherizedFieldname})) {
                    // TODO: call setter if available?!
                    $writer($entity, $fieldName, $entityData->attributes->{$dasherizedFieldname});
                }
            }

            foreach($associationMappings as $mappingName => $mapping) {
                if($mapping['type'] == ClassMetadataInfo::MANY_TO_ONE) {
                    $fieldName = $mapping['fieldName'];

                    $dasherizedFieldname = $this->dasherize($fieldName);

                    // TODO: Make this more robust
                    $id = $entityData->relationships->$dasherizedFieldname;
                    // TODO: handle data->type too
                    if( is_object($id) && property_exists($id, 'data') && is_object($id->data) && property_exists($id->data, 'id') ) {
                        $id = $id->data->id;
                    }

                    $entityReference = $this->entityManager->getReference($mapping['targetEntity'], $id);
                    $writer($entity, $fieldName, $entityReference);
                }
            }

            return $entity;
    }

    // helpers
    public static function getResourceType($entityClass)
    {
        $offset = strrpos($entityClass, '\\') + 1;
        $resourceType = substr($entityClass, $offset);

        // TODO: proper plural
        return self::dasherize($resourceType).'s';
    }

    public static function getResourceNameForRoute($entityClass)
    {
        $offset = strrpos($entityClass, '\\') + 1;
        $resourceType = substr($entityClass, $offset);

        return self::lowdasherize($resourceType);
    }

    public static function getEntityClass($resourceType)
    {
        $entityClass = substr($resourceType, 0, -1);
        $entityClass = self::camelCase($entityClass);
        $entityClass = ucfirst($entityClass);

        $fullEntityClass = '\App\Entity\\' . $entityClass;

        return $fullEntityClass;
    }

    // TODO: use library for this
    public static function camelCase($input)
    {
        $str = str_replace('-', '', ucwords($input, '-'));
        $str = lcfirst($str);

        return $str;
    }

    public static function dasherize($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }

    public static function lowdasherize($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}
