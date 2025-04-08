<?php

namespace App\Controller\Public;

use App\Controller\BaseController;
use App\Service\EventService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/public")]
class EventController extends BaseController
{
    public function __construct(
        private EventService $eventService
    )
    {}

    #[Route("/events",name: "getEvents",methods: ["GET"])]
    public function getAllEvents():Response
    {
        $events = $this->eventService->getAllActiveEvents();

        return $this->render("App/event/index.html.twig",[
           "events" => $events
        ]);
    }
}
