<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Map;
use Wzc\MainBundle\Geo\SxGeo;

class MapController extends Controller
{
    /**
     * @Route("/map/{thisMetro}", name="map", defaults={"thisMetro" = 0}, options={"expose" = true})
     * @Template()
     */
    public function indexAction(Request $request, $thisMetro = 0)
    {

        $session = new Session();

        if ( $session->get('city') == null){

        }
        $dir = __DIR__.'/../Geo/SxGeoCity.dat';
        $SxGeo = new SxGeo($dir);
// $SxGeo->getCity($ip); (возвращает с краткой информацией, без названия региона и временной зоны)

        $ip = $_SERVER["REMOTE_ADDR"];
        if ($ip == '127.0.0.1'){
            $ip = '84.253.73.126';
        }

        $info = $SxGeo->getCityFull($ip);
        unset($SxGeo);


        if (isset($thisMetro) && $thisMetro != 0){
            $thisMetro = $this->getDoctrine()->getRepository('WzcMainBundle:Metro')->findOneById($thisMetro);
        }else{
            $thisMetro = null;
        }

        if ($request->query->get('s')){
            $coords = $this->getDoctrine()->getRepository('WzcMainBundle:Map')->search($request->query->get('s'));
        }else{
            $coords = $this->getDoctrine()->getRepository('WzcMainBundle:Map')->findByEnabled(1);
        }
        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('map');
        $metro = $this->getDoctrine()->getRepository('WzcMainBundle:Metro')->findByEnabled(true);

        $ipCity = $info['city'];
        $ipRegion = $info['region'];



        return array('coords' => $coords, 'page' => $page, 'metro' => $metro, 'thisMetro' => $thisMetro, 'ipCity' => $ipCity, 'ipRegion' => $ipRegion );
    }

    /**
     * @Route("/city", name="city")
     * @Template()
     */
    public function cityAction(Request $request){
        $session = new Session();
        if ($request->getMethod() == 'POST'){
            $session->set('city',$request->request->get('city_name_ru'));
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }else{
            if ( $session->get('city') == null ){
                return $this->render('WzcMainBundle::popup_city.html.twig');
            }else{
                return new Response('');
            }
        }
    }


}