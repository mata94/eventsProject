<?php

namespace App\Controller\Api\Public;

use App\Application\User\Command\RegisterUserCommand;
use App\Application\User\Command\RegisterUserHandler;
use App\Controller\BaseController;
use App\Entity\User;
use App\Form\RegisterFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api/public")]
class RegisterController extends BaseController
{/*
    public function __construct(
        protected ValidatorInterface $validator
    ){}

    #[Route("/register",name: "registerUser",methods: ["GET","POST"])]
    public function register(
        Request $request,
        RegisterUserHandler $handler
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new RegisterUserCommand();
            $command->setEmail($form->getData()->getEmail());
            $command->setPassword($form->getData()->getPassword());

            try{
                $handler->execute($command);
            }catch (\Exception $exception) {
                return $this->render('Admin/user/register.html.twig',[
                    "form" => $form->createView(),
                    "error" => $exception->getMessage()
                ]);
            }
        }

        return $this->render('Admin/user/register.html.twig',[
            "form" => $form->createView(),
            "error" => ""
        ]);
    }*/
}