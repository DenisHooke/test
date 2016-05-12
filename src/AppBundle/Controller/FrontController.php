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
     * Стартовый роут
     *
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        return $this->render('default/index.html.twig', array(
                'itemsOnPage' => $this->container->getParameter('items_on_page'),
                'defaultSort' => $this->container->getParameter('default_sort'),
                'defaultSortOrder' => $this->container->getParameter('default_sort_order'),
            )
        );
    }


    /**
     * Роут загрузки/ дозагрузки треков
     *
     * @Route("/load/tracks", name="load/tracks")
     */
    public function loadTracksAction(Request $request)
    {

        $page = $request->get("page");
        $pageFlag = $request->get("pageFlag");
        $sort = $request->get("sort");
        $order = $request->get("order");
        $filter       = $request->get("filter");
        $itemsOnPage = $this->container->getParameter('items_on_page');

        $trackRepo = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Track');

        $filterForm = new Filter(
            array(
                "genre" => "Жанр",
                "year" => "Год",
                "singer" => "Исполнитель"
            ),
            $trackRepo
        );


        if ($pageFlag) {
            $offset = ($page - 1) * $itemsOnPage;
            $limit =  $itemsOnPage;
        } else {
            $offset = 0;
            $limit = $itemsOnPage * $page;
        }

        $tracks = $trackRepo->findBy($filterForm->convertValue($filter), array($sort => $order), $limit, $offset);



        $data = $this->get('jms_serializer')->serialize(array(
            "tracks" => $tracks,
            "filter" => $filterForm->create()
        ), 'json');

        return new Response($data);
    }


}
