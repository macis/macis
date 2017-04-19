<?php

namespace Macis\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MacisClientBundle:Default:index.html.twig', Array(
            'PageTitle' => "Client"
        ));
    }

    public function fileAction()
    {
        return $this->render('MacisClientBundle:Default:file.html.twig', Array(
            'PageTitle' => "Client"
        ));
    }
}
