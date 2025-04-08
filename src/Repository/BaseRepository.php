<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Internal\Hydration\IterableResult;
use \Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Statement;

abstract class BaseRepository
{
    /** @var EntityManagerInterface  */
    protected $em;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->em = $entityManager;
        $this->addRepository();
    }

    /**
     * Instantiates repository
     * @throws \InvalidArgumentException
     */
    private function addRepository()
    {
        try {
            $this->repository = $this->em->getRepository($this->getEntityName());
        } catch (\Exception $exception) {
            $msg = sprintf(
                "Valid entity name must be defined in getEntity function. Entity name %s provided is NOT valid. Exception: %s",
                $this->getEntityName(),
                $exception->getMessage()
            );
            throw new \InvalidArgumentException($msg, $exception->getCode(), $exception);
        }
    }

    /*/**
     * @param IterableResult $results
     * @return \Generator
     */
   /* protected function iterate(IterableResult $results)
    {
        foreach ($results as $object) {
            $object = current($object);
            yield $object;
            $this->em->clear();
        }
    }*/

    /**
     * returns entity name associated with repository
     * @return string
     */
    abstract protected function getEntityName();

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->getEntityName());
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->em->getConnection();
    }


    /**
     * Saves Entity
     * @param object $entity
     * @return object $entity
     */
    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

   /* /**
     * @param object $entity
     * @return object
     */
   /* public function saveSingle($entity): object
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }*/

   /* /**
     * Update Entity
     * @param object $entity
     * @return object $entity
     */
   /* public function update($entity)
    {
        $this->em->merge($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    /*public function batchSave($entities)
    {
        foreach ($entities as $entity) {
            $this->em->persist($entity);
        }
        $this->em->flush();

        return $entities;
    }

    /**
     * {@inheritdoc}
     */
    /*public function batchDelete($entities)
    {
        foreach ($entities as $entity) {
            $this->em->remove($entity);
        }
        $this->em->flush();

        return $entities;
    }

    /**
     * {@inheritdoc}
     */
    /*public function batchUpdate($entities)
    {
        foreach ($entities as $entity) {
            $this->em->merge($entity);
        }
        $this->em->flush();

        return $entities;
    }

    /**
     * @param object $entity
     * @return mixed
     */
  /*  public function flushSingleEntity($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }*/

    /**
     * Find all from repository
     * @param int $limit limit of the result
     * @param int $offset starting from th offset
     * @return array
     */
    public function findAll($limit = null, $offset = null, $orderBy = null)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    /**
     * findBy
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return object[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * Removes entity
     * @param $entity
     * @return $entity
     */
    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * Removes entity
     * @param $entity
     * @return
     */
    public function remove($entity)
    {
        return $this->delete($entity);
    }

   /* /**
     * detaches entity
     * @param $entity
     * @return
     */
   /* public function detach($entity)
    {
        $this->em->detach($entity);
        return $entity;
    }*/

    /**
     * @param $entity
     */
    public function persist($entity)
    {
        $this->em->persist($entity);
        return $entity;
    }

    /**
     * @param $entity
     */
    public function flush()
    {
        $this->em->flush();
    }

   /* /**
     * merges entity
     * @param $entity
     * @return
     */
  /*  public function merge($entity)
    {
        $this->em->merge($entity);
        return $entity;
    }*/

   /* /**
     * @param string|null $entityName
     */
   /* public function clearEntityManager($entityName = null)
    {
        $this->em->clear($entityName);
    }*/

    /*/**
     * {@inheritDoc}
     */
  /*  public function getOrThrowException($id)
    {
        if (!($resource = $this->findById($id))) {
            throw new \Exception(sprintf("The resource %s was not found for %s.", $id, $this->getEntityName()), 404);
        }

        return $resource;
    }*/

   /* /**
     * {@inheritDoc}
     */
   /* public function getByUuidOrThrowException($uuid)
    {
        if (!($resource = $this->findByUuid($uuid))) {
            throw new \Exception(sprintf("The resource %s was not found for %s.", $uuid, $this->getEntityName()), 404);
        }

        return $resource;
    }*/

    /**
     * Get entity by id
     * @return object
     */
    public function findById($id)
    {
        return $this->repository->find($id);
    }


    /**
     * Get entity by id
     * @return object
     */
    public function findByUuid(string $uuid)
    {
        return $this->findOneBy(
            [
                'uuid' => $uuid
            ]
        );
    }

    public function rawSql($sql, array $params = [], $fetchAll = true)
    {
        /** @var Statement $stmt */
       $stmt = $this->getConnection()
            ->prepare($sql);

        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $result = $stmt->executeQuery();

        if($fetchAll) {
            return $result->fetchAllAssociative();
        }
    }

    public function bindParamArray(string $prefix, array $values, &$bindArray)
    {
        $str = "";
        foreach($values as $index => $value) {
            $str .= ":".$prefix.$index.",";
            $bindArray[$prefix.$index] = $value;
        }
        return rtrim($str, ",");
    }
}
