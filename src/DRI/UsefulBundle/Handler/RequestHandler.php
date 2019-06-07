<?php

namespace DRI\UsefulBundle\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class RequestHandler
{
    public $entityManager;
    public $search;
    public $orderDir;
    public $orderBy = 'fullName';
    public $start = 0;
    public $length = 10;
    public $columns = array();
    public $joins = array();
    public $draw;

    /**
     * Class Constructor, holds EntityManager object.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * process current datatable server side operation.
     *
     * @param Request $request
     * @param Entity $entity Entity name
     * @param array $columns
     * @param array $joins
     *
     * @return array
     */
    public function process(Request $request, $entity, array $columns, array $joins)
    {
        $em = $this->entityManager;
        $allias = 'e';

        $this->columns = $columns;
        $this->joins = $joins;
        $this->buildParameter($request);

        $columnsList = '';
        $columnsCount = count($columns);
        $columnsCounter = 1;

        $joinsAllias = 'j';
        //$joinsCount = count($joins);
        $joinsCounter = 1;

        foreach ($this->columns as $column) {
            $columnsCounter++;
            if($columnsCounter <= $columnsCount){
                $columnsList .= $allias.'.'.$column.', ';
            }else{
                $columnsList .= $allias.'.'.$column;
            }
        }
        foreach ($this->joins as $join){
            $columnsList .= ', '.$joinsAllias.$joinsCounter;
            $joinsCounter++;
        }$joinsCounter = 1;

        $query = $em->getRepository($entity)->createQueryBuilder($allias);
            $query->select("$columnsList");

        foreach ($this->columns as $column) {
            $query->orWhere("$allias.$column LIKE :search");
            //throw new \Error("$allias.$column LIKE :search");
        }

        foreach ($this->joins as $join){
            $query->join("$allias.$join", $joinsAllias.$joinsCounter);
            $joinsCounter++;
        }


        $query->setParameter('search', '%'.$this->search.'%');
        $query->orderBy("$allias.$this->orderBy", $this->orderDir);

        $recordsFiltered = $query->getQuery()->getResult();

        $query->setFirstResult($this->start);
        $query->setMaxResults($this->length);

        $unNormalizedData = $query->getQuery()->getResult();

        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers);

        $normalizedData = $serializer->normalize($unNormalizedData);

        $response = array(
            'draw' => $this->draw,
            'data' => $unNormalizedData,
            'recordsTotal' => count($recordsFiltered),
            'recordsFiltered' => count($recordsFiltered),
        );

        return $response;
    }

    /**
     * Build parameters sent by Datatable Server Side.
     *
     * @param Request $request
     */
    private function buildParameter(Request $request)
    {
        $post = $request->request;
        $orderDirKey = $post->get('order')[0]['column'];

        $this->search = $post->get('search')['value'];
        $this->orderDir = $post->get('order')[0]['dir'];
        $this->orderBy = $this->columns[$orderDirKey];
        $this->start = $post->get('start');
        $this->length = $post->get('length');
        $this->draw = $post->get('draw');
    }
}
