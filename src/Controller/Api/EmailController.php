<?php

namespace App\Controller\Api;

use App\DTO\Email\EmailDTO;
use App\Service\EmailService;
use App\RequestValidator\RequestValidator;
use App\Exception\FailedOperationException;
use Symfony\Component\HttpFoundation\Request;
use App\RequestValidator\Email\EmailValidator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
    /**
     * @Route("/emails", methods={"GET"}, name="api.emails.list")
     *
     * @param Request          $request
     * @param RequestValidator $requestValidator
     * @param EmailService     $emailService
     *
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function listEmails(Request $request, RequestValidator $requestValidator, EmailService $emailService): JsonResponse
    {
        $emailDTO = new EmailDTO();

        $requestValidator->build(EmailValidator::class, $emailDTO);

        $data = (array) json_decode($request->getContent(), true);

        $requestValidator->handleData($data);

        $emails = $emailService->getEmails($emailDTO, $data);

        if (empty($emails)) {
            throw new FailedOperationException('Listeleme sırasında hata oluştu');
        }

        return $this->json([
            'emails' => $emails,
        ]);
    }
}