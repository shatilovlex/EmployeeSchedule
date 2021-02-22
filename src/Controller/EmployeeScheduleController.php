<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/employee-schedule")
 */
class EmployeeScheduleController extends AbstractController
{
    /**
     * @Route("")
     */
    public function getWorkSchedule(): JsonResponse
    {
        return new JsonResponse(['Endpoint not yet implemented.'], JsonResponse::HTTP_NOT_IMPLEMENTED);
    }
}
