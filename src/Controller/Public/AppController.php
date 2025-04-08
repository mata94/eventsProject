<?php

namespace App\Controller\Public;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends BaseController
{
    #[Route("/",name: "home",methods: ["GET"])]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute("getEvents");
    }
}