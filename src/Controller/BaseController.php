<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

abstract class BaseController extends AbstractController
{
    protected ValidatorInterface $validator;

    protected function activeMenu($key = null, $asArray = false)
    {
        $menu = [
            'dashboard'   => '',
            'user'        => '',
            'profile'     => '',
            'organization'=> '',
            'home'        => '',
            'event'       => '',
        ];

        if(array_key_exists($key, $menu)){
            $menu[$key] = 'active';
        }

        if(!$asArray){
            return (object)$menu;
        }

        return $menu;
    }

    /**
     * @return int
     * @throws \LogicException if the user is not logged in
     */
    protected function getUserId(): int
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if ($user === null) {
            throw new \LogicException('User is not logged in.');
        }

        return $user->getId();
    }

    /**
     * @throws \Exception
     */
    protected function checkErrors(ConstraintViolationListInterface $validationErrors): void
    {
        if (count($validationErrors) > 0) {
            /** @var ConstraintViolationInterface $firstError */
            $firstError = $validationErrors[0];
            $invalidValue = var_export($firstError->getInvalidValue(), true);
            throw new \RuntimeException("{$firstError->getMessage()} {$invalidValue}");
        }
    }

    /**
     * @param object $command
     * @return void
     * @throws \Exception
     */
    protected function validateCommand($command): void
    {
        $errors = $this->validator->validate($command);

        if(count($errors) > 0) {
            $this->checkErrors($errors);
        }
    }

    /**
     * @param \Exception $exception
     * @return array<string, string>
     */
    protected function makeReturnErrorMessage(\Exception $exception): array
    {
        return [
            'error' => $exception->getMessage()
        ];
    }

    /**
     * @param string $message
     * @return string[]
     */
    protected function makeReturnMessage(string $message): array
    {
        return [
            'message' => $message
        ];
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected function jsonWithMessage(string $message): JsonResponse
    {
        return $this->json(
            $this->makeReturnMessage($message)
        );
    }
}
