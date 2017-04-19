<?php

namespace Macis\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MacisDashboardBundle:Default:index.html.twig', Array(
            'PageTitle' => "Dashboard"
        ));
    }
}
