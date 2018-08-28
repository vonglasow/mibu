<?php

namespace App\Controller;

use App\Component\Handler\FictionHandler;
use App\Component\IO\FictionIO;
use App\Form\FictionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class FictionController extends BaseController
{
    /**
     * @Rest\Get("fictions", name="get_fictions")
     */
    public function getFictions(Request $request): Response
    {
        return $this->createApiResponse(
            $this->getHandler()->getFictions($request),
            200,
            $this->getHandler()->generateUrl('get_fictions', [], $request->query->get('page', 1))
        );
    }

    /**
     * @Rest\Get("fictions/{id}", name="get_fiction")
     */
    public function getFiction($id): Response
    {
        return $this->createResponse(
            $this->getHandler()->getFiction($id)
        );
    }

    /**
     * @Rest\Post("fictions", name="post_fiction")
     *
     *
     * @param Request $request
     * @return JsonResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postFiction(Request $request)
    {
        $data = $this->getData($request);

        $fictionIO = new FictionIO();
        $form = $this->createForm(FictionType::class, $fictionIO);
        $form->submit($data);

        if($form->isSubmitted()) {  //remplacer par isValidate

            $fictionIO = $this->getHandler()->postFiction($data);

            $url = $this->createRouteGetFiction(json_decode($fictionIO)->id);
            $response = $this->createResponse($fictionIO, $url);

            return $response;
        }

        return new JsonResponse("Echec de l'insertion", 500);
    }

    /**
     * @Rest\Put("fictions/{fictionId}", name="put_fiction")
     */
    public function putFiction(Request $request, $fictionId)
    {
        $data = $this->getData($request);
        $fictionIO = $this->getHandler()->putFiction($fictionId, $data);
        $url = $this->createRouteGetFiction($fictionId);
        $response = $this->createResponse($fictionIO, $url);

        return $response;

    }

    /**
     * @Rest\Delete("/fictions/{fictionId}",name="delete_fiction")
     */
    public function deleteAction($fictionId)
    {
        return $this->getHandler()->deleteFiction($fictionId);
    }

    /**
     * @return FictionHandler
     */
    public function getHandler()
    {
        //get service
        $fictionHandler = new FictionHandler($this->getDoctrine()->getManager(), $this->get('router'));
        return $fictionHandler;
    }

    /**
     * @param $io
     * @param null $url
     * @return Response
     */
    public function createResponse($io, $url = null)
    {
        $response = new Response($io);
        $response->headers->set('Content-Type', 'application/json');

        if (isset($url)) {
            $response->headers->set('Location', $url);
        }

        return $response;
    }

    /**
     * @param $id
     * @return string
     */
    public function createRouteGetFiction($id)
    {
        $url = $this->generateUrl(
            'get_fiction', array(
            'id' => $id
        ));

        return $url;
    }

}