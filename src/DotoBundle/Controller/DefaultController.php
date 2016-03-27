<?php

namespace DotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $apikey = $this->container->getParameter('apikey');
        $user = $this->getUser();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$apikey.'&steamids='.$user->getSteamid().'');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec($ch));

        return $this->render('DotoBundle:Default:index.html.twig',[
            'response' => $response
        ]);
    }
    public function emptyAction(){
        return $this->render('DotoBundle:Default:empty.html.twig');
    }
}
