<?php

namespace Doto\SteamAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DotoSteamAuthBundle:Default:index.html.twig');
    }
}
