<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Slot;
use App\Form\SlotType;
use App\Model\FilterRequest;
use App\Repository\SlotRepository;
use App\Service\EventService;
use App\Service\SlotService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/slot')]
final class SlotController extends BaseController
{
    const BREAD_CRUMB = 'Organization';
    const ACTIVE_MENU = 'event';

    public function __construct(
        private FilterRequest $filterRequest
    ){}

    #[Route(name: 'app_slot_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $slots = $entityManager
            ->getRepository(Slot::class)
            ->findAll();

        return $this->render('Admin/slot/index.html.twig', [
            'slots' => $slots,
        ]);
    }

    #[Route('/new/{eventId}', name: 'app_slot_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $eventId,
        EntityManagerInterface $entityManager,
        EventService $eventService
    ): Response
    {
        $event = $eventService->getEventById($eventId);

        $slot = new Slot();
        $slot->setEvent($event);
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($slot);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_show', ["id" => $eventId], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Admin/slot/new.html.twig', [
            'slot' => $slot,
            'form' => $form,
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/{slotId}/users', name: 'app_slot_users_show', methods: ['GET'])]
    public function slotUsers(
        int $slotId,
        SlotService $slotService,
        PaginatorInterface $paginator,
    ): Response
    {
        $slot = $slotService->getSlotOfId($slotId);
        $pagination = $paginator->paginate(
            $slot->getSlotUsers()->toArray(),
            $this->filterRequest->getPage(),
            $this->filterRequest->getLimit()
        );

        return $this->render('Admin/slot_user/index.html.twig', [
            'slotUsers' => $pagination,
            'slot' => $slot,
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/{id}', name: 'app_slot_show', methods: ['GET'])]
    public function show(Slot $slot): Response
    {
        return $this->render('slot/show.html.twig', [
            'slot' => $slot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_slot_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        int $id,
        SlotService $slotService,
        SlotRepository $slotRepository
    ): Response
    {
        $slot = $slotService->getSlotOfId($id);
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slotRepository->flush();

            return $this->redirectToRoute(
                'app_event_show',
                ["id" => $slot->getEvent()->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('Admin/slot/edit.html.twig', [
            'slot' => $slot,
            'form' => $form,
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/{id}', name: 'app_slot_delete', methods: ['POST'])]
    public function delete(Request $request, Slot $slot, EntityManagerInterface $entityManager): Response
    {
        $eventId = $slot->getEvent()->getId();
        if ($this->isCsrfTokenValid('delete'.$slot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($slot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_show', ["id" => $eventId], Response::HTTP_SEE_OTHER);
    }
}
