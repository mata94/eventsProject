<?php

namespace App\Controller\Public;

use App\Application\SlotUser\Command\SlotConfirmArrivalCommand;
use App\Application\SlotUser\Command\SlotConfirmArrivalHandler;
use App\Controller\BaseController;
use App\Form\SlotUserType;
use App\Service\SlotService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/public")]
class SlotUserController extends BaseController
{
    #[Route("/slot/{slotId}/confirm_arrival", name:"confirmSlotArrival", methods: ["GET","POST"])]
    public function confirmArrival(
        int $slotId,
        Request $request,
        SlotConfirmArrivalHandler $handler,
        SlotService $slotService
    ): Response
    {
        $form = $this->createForm(SlotUserType::class);
        $form->handleRequest($request);

        $slot = $slotService->getSlotOfId($slotId);

        if($form->isSubmitted() && $form->isValid()){
            $fullName = $form->getData()["fullName"];
            $email = $form->getData()["email"];

            $command = new SlotConfirmArrivalCommand(
                $fullName,
                $email,
                $slotId
            );

            try{
               $handler->execute($command);
            }catch (\Exception $exception){
                return $this->render("App/slotUser/new.html.twig",
                    [
                        "form" => $form->createView(),
                        "error" => $exception->getMessage(),
                        "success" => "",
                        "event" => $slot->getEvent()
                    ]
                );
            }
            return $this->render("App/slotUser/new.html.twig",[
                "form" => $form->createView(),
                "error" => "",
                "success" => "Success!",
                "event" => $slot->getEvent()
            ]);
        }

        return $this->render("App/slotUser/new.html.twig",[
            "form" => $form->createView(),
            "error" => "",
            "success" => "",
            "event" => $slot->getEvent()
        ]);
    }
}
