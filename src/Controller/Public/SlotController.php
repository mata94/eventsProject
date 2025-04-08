<?php

namespace App\Controller\Public;

use App\Controller\BaseController;
use App\Entity\SlotUser;
use App\Service\EventService;
use App\Service\SlotUserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/public")]
class SlotController extends BaseController
{
    public function __construct(
        private EventService $eventService
    ){}

    #[Route("/event/{eventId}/slots",name:"allEventSlots")]
    public function getAllEventSlots(
        int $eventId
    ): Response
    {
        $event = $this->eventService->eventOfId($eventId);
        $slots = $this->eventService->getAllSlotsByEvent($event);

        return $this->render("App/slot/index.html.twig",[
           "slots" => $slots,
            "event" => $event
        ]);
    }

    #[Route("/slot/{qrCode}/checked",name:"checkSlotWith",methods: ["GET"])]
    public function checkSlotWithQrCode(
        string $qrCode,
        SlotUserService $slotUserService
    ): Response
    {
        /** @var SlotUser $slotUser */
        $slotUser = $slotUserService->getSlotUserByQrCOde($qrCode);
        $cloned = clone($slotUser);

        $slotUserService->check($slotUser);

        return $this->render("App/slot/qrCodeResponse.html.twig",[
            "slotUser" => $cloned,
        ]);
    }
}
