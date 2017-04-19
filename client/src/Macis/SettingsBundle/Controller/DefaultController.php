<?php

namespace Macis\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MacisSettingsBundle:Default:index.html.twig', Array(
            'PageTitle' => "Settings"
        ));
    }
}
