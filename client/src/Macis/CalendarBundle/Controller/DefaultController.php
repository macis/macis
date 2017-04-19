<?php

namespace Macis\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MacisCalendarBundle:Default:index.html.twig', array(
            // 'number' => $number,
            'PageTitle' => "Calendar",
        ));
    }
}
