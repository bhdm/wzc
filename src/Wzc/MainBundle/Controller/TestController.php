<?php

namespace Wzc\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Wzc\MainBundle\Entity\Map;
use Wzc\MainBundle\Entity\Stats;
use Wzc\MainBundle\Entity\TestStatistic;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('testSum', 0);
        $session->save();

        $page = $this->getDoctrine()->getRepository('WzcMainBundle:Page')->findOneByUrl('test');
        $question = $this->getDoctrine()->getRepository('WzcMainBundle:TestQuestion')->findByEnabled(1);
        if ($question){
            $question = $question[0];
        }

        return array('question' => $question, 'page' => $page, );
    }

    /**
     * @Route("/test/next/{questionId}", name="test_next", options={"expose"=true})
     * @Template()
     */
    public function nextQuestionAction($questionId, Request $request){
        $session = $request->getSession();
        $sum = $session->get('testSum');

        if ($request->getMethod() == 'POST'){
            $sum = $sum + $request->request->get('answer');
            $session->set('testSum',$sum);
            $session->save();
        }
        $question = $this->getDoctrine()->getRepository('WzcMainBundle:TestQuestion')->findNext($questionId);

        if ($question != null){
            return array('question' => $question);
        }else{
            if ( $this->getUser() != null ){
                $user = $this->getDoctrine()->getRepository('WzcMainBundle')->find($this->getUser());
            }else{
                $user = null;
            }
            $testStatistic = new TestStatistic();
            $testStatistic->setUser($user);
            $testStatistic->setBalls($sum);
            $this->getDoctrine()->getManager()->persist($testStatistic);
            $this->getDoctrine()->getManager()->flush($testStatistic);

            if ( $sum >= 6 ){

                $dir = __DIR__.'/../Geo/SxGeoCity.dat';
                $SxGeo = new SxGeo($dir);

                $ip = $_SERVER["REMOTE_ADDR"];
                if ($ip == '127.0.0.1'){
                    $ip = '84.253.73.126';
                }
                $info = $SxGeo->getCityFull($ip);
                $ipCity = $info['city'];
                unset($SxGeo);

                $stat = new Stats();
                $stat->setType('test');
                $stat->setIp($ip);
                $stat->setCity($ipCity['name_ru']);
                $this->getDoctrine()->getManager()->persist($stat);
                $this->getDoctrine()->getManager()->flush($stat);

                $url = $this->generateUrl('map');
                $page['title'] = '';
                $page['body'] = '
                <p>Ваш показатель  <b>'.$sum.'</b> баллов. <br />
                При сумме 6 баллов и более наличие у Вас болезни Крона&nbsp;<strong>высоковероятно</strong>.<br />
                Пожалуйста, обратитесь к врачу-гастроэнтерологу.<br />
                <br />
                Вы можете воспользоваться <a href="'.$url.'" style="font-weight: bold; color: #991199;">нашей картой</a> для поиска клиники
                </p>';
            }else{
                $page['title'] = '';
                $page['body'] = '<p>Ваш показатель <b>'.$sum.'</b> баллов. При сумме менее 6 баллов наличие у Вас болезни Крона&nbsp;<strong>маловероятно</strong>.</p>';
            }
            return array('answer' => $page);
        }

    }



}