<?php

namespace AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadTrackData;
use AppBundle\Entity\Filter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;
use AppBundle\Repository\TrackRepository;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', array(
                'itemsOnPage' => $this->container->getParameter('items_on_page')
            )
        );
    }

    /**
     * @Route("/load/filter", name="load/filter")
     */
    /*public function filterAction(Request $request)
    {

        $data = $this->get('jms_serializer')->serialize(array("filter" => $filter->create()), 'json');

        return new Response($data);
    }*/

    /**
     * @Route("/load/tracks", name="load/tracks")
     */
    public function loadTracksAction(Request $request)
    {

        $page        = $request->get("page");
        $sort        = $request->get("sort");
        $order       = $request->get("order");
        //$filter       = $request->get("filter");
        $itemsOnPage = $this->container->getParameter('items_on_page');

        $trackRepo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Track')
        ;

        $filter = new Filter(
            array( "genre", "year", "singer"),
            $trackRepo
        );

        $tracks = $trackRepo->findBy(array(), array($sort => $order), $itemsOnPage, ($page - 1) * $itemsOnPage);


        $data = $this->get('jms_serializer')->serialize(array(
            "tracks" => $tracks,
            "filter" => $filter->create()
        ), 'json');

        return new Response($data);
    }

    /**
     * @Route("/sort/tracks", name="sort/tracks")
     */
    public function sortTracksAction(Request $request)
    {

        $page        = $request->get("page");
        $sort        = $request->get("sort");
        $order       = $request->get("order");
        $itemsOnPage = $this->container->getParameter('items_on_page');

        $trackRepo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Track')
        ;

        $tracks = $trackRepo->findBy(array(), array($sort => $order), $itemsOnPage * $page);


        $data = $this->get('jms_serializer')->serialize(array("tracks" => $tracks), 'json');

        return new Response($data);
    }
}
