<?php

namespace App\Controller;

use App\Model\Employee\useCase\EmployeeSchedule;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/employee-schedule")
 */
class EmployeeScheduleController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @Route("", name="index", methods={"GET"})
     * @param Request $request
     * @param EmployeeSchedule\Handler $handler
     * @return JsonResponse
     */
    public function getWorkSchedule(Request $request, EmployeeSchedule\Handler $handler): JsonResponse
    {
        try {
            $command = new EmployeeSchedule\Command(
                $request->get('startDate'),
                $request->get('endDate'),
                $request->get('employeeId'),
            );
            $violations = $this->validator->validate($command);
            if (count($violations)) {
                $errorList = [];
                foreach ($violations as $key => $error) {
                    $errorList[] = $error->getMessage();
                }
                return new JsonResponse(["errors" => $errorList], JsonResponse::HTTP_BAD_REQUEST);
            }
            return new JsonResponse($handler->handle($command), JsonResponse::HTTP_OK);
        } catch (DomainException $exception) {
            return new JsonResponse(["errors" => [$exception->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
