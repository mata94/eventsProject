<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Event;
use App\Entity\Slot;
use App\Form\EventType;
use App\Model\FilterRequest;
use App\Service\EventService;
use App\Service\ImageService;
use App\Service\SlotService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/event')]
final class EventController extends BaseController
{
    const BREAD_CRUMB = 'Organization';
    const ACTIVE_MENU = 'event';

    public function __construct(
        private FilterRequest $filterRequest
    ){}

    #[Route(name: 'app_event_index', methods: ['GET'])]
    public function index(
        PaginatorInterface $paginator,
        EventService $eventService
    ): Response
    {
        $this->filterRequest->setActive(1);
        $events = $eventService->getAllEvents();

        $pagination = $paginator->paginate(
            $events,
            $this->filterRequest->getPage(),
            $this->filterRequest->getLimit()
        );

        return $this->render('Admin/event/index.html.twig', [
            'events' => $pagination,
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ImageService $imageService
    ): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            if ($file !== null) {
                $imagePath = $imageService->uploadImage($file);
                $event->setImagePath($imagePath);
            }
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Admin/event/new.html.twig', [
            'event' => $event,
            'form' => $form,
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(
        int $id,
        EventService $eventService,
        PaginatorInterface $paginator,
    ): Response
    {
        /** @var Event $event */
        $event = $eventService->getEventById($id);
        /** @var Slot[] $slots */
        $slots = $eventService->getAllSlotsByEvent($event);

        $pagination = $paginator->paginate(
            $slots,
            $this->filterRequest->getPage(),
            $this->filterRequest->getLimit()
        );

        return $this->render('Admin/event/show.html.twig', [
            'event' => $event,
            'slots' => $pagination,
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Event $event,
        EntityManagerInterface $entityManager,
        ImageService $imageService
    ): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            if ($file !== null) {
                if($event->getImagePath() !== null){
                    $imageService->removeImage($event->getImagePath());
                }
                $imagePath = $imageService->uploadImage($file);
                $event->setImagePath($imagePath);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Admin/event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
