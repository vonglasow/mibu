<?php

namespace App\Component\Handler;

use App\Component\Serializer\CustomSerializer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class BaseHandler
{
    protected $em;
    protected $router;

    /**
     * BaseHandler constructor.
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param $route
     * @param array $params
     * @param $targetPage
     * @return string
     */
    public function generateUrl($route, array $params, $targetPage)
    {
        return $this->router->generate(
            $route,
            array_merge(
                $params,
                array('page' => $targetPage)
            )
        );
    }

    public function generateSimpleUrl($route, array $params)
    {
        return $this->router->generate(
            $route,
            array_merge(
                $params
            )
        );
    }

    /**
     * @param $entity
     * @return bool
     */
    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return true;
    }

    /**
     * @return CustomSerializer
     */
    public function getSerializer(): CustomSerializer
    {
        return new CustomSerializer();
    }
}