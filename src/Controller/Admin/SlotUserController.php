<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\SlotUser;
use App\Entity\User;
use App\Form\SlotUser1Type;
use App\Form\SlotUserForAdmin;
use App\Form\SlotUserType;
use App\Repository\SlotUserRepository;
use App\Repository\UserRepository;
use App\Service\SlotService;
use App\Service\SlotUserService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/slot/user')]
final class SlotUserController extends BaseController
{
    const BREAD_CRUMB = 'Organization';
    const ACTIVE_MENU = 'event';

    #[Route(name: 'app_slot_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $slotUsers = $entityManager
            ->getRepository(SlotUser::class)
            ->findAll();

        return $this->render('slot_user/index.html.twig', [
            'slot_users' => $slotUsers,
        ]);
    }

    #[Route('/new/{slotId}', name: 'app_slot_user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        int $slotId,
        SlotService $slotService,
        SlotUserService $slotUserService,
        UserService $userService,
        SlotUserRepository $slotUserRepository,
        UserRepository $userRepository
    ): Response
    {
        $slot = $slotService->getSlotOfId($slotId);
        $form = $this->createForm(SlotUserForAdmin::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()["email"];
            $fullName = $form->getData()["fullName"];

            /** @var User $user */
            $user = $userService->userOfEmail($email);

            if($user === null){
                $user = new User();
                $user->setFullName($fullName);
                $user->setEmail($email);
                $user->setRoles([User::ROLE_GUEST]);
                $user->setVerified(true);
                $userRepository->save($user);
            }

            $slotUserCheck = $slotUserService->getSlotUserBySlotAndUser($slot,$user);
            if($slotUserCheck === null){
                $slotUserService->createSlotUserForAdmin($slot,$user);
                return $this->redirectToRoute('app_event_show', ["id" => $slot->getEvent()->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->render('Admin/slot_user/new.html.twig', [
                'form' => $form,
                'error' => "User already exists.",
                'breadCrumb' => self::BREAD_CRUMB,
                "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
            ]);
        }

        return $this->render('Admin/slot_user/new.html.twig', [
            'form' => $form,
            'error' => "",
            'breadCrumb' => self::BREAD_CRUMB,
            "activeMenu" => $this->activeMenu(self::ACTIVE_MENU)
        ]);
    }

    #[Route('/{id}/edit', name: 'app_slot_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SlotUser $slotUser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SlotUser1Type::class, $slotUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_slot_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('slot_user/edit.html.twig', [
            'slot_user' => $slotUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_slot_user_delete', methods: ['GET'])]
    public function delete(
        Request $request,
        int $id,
        EntityManagerInterface $entityManager,
        SlotUserService $slotUserService
    ): Response
    {
        $slotUser = $slotUserService->getSlotUserById($id);
        $event = $slotUser->getSlot()->getEvent()->getId();

        $entityManager->remove($slotUser);
        $entityManager->flush();


        return $this->redirectToRoute('app_event_show', ["id" => $event], Response::HTTP_SEE_OTHER);
    }
}
