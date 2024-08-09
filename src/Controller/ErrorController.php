<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Debug\DebugLoggerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    public function show(Request $request, NotFoundHttpException $exception, DebugLoggerInterface $logger = null): Response
    {
        $statusCode = $exception->getStatusCode();

        if ($statusCode == 404) {
            return $this->render('error/error.html.twig', [
                'status_code' => $statusCode,
                'status_text' => Response::$statusTexts[$statusCode] ?? 'Unknown Error',
            ]);
        }

        // For other errors, you might want to render a generic error page
        return $this->render('error/error.html.twig', [
            'status_code' => $statusCode,
            'status_text' => Response::$statusTexts[$statusCode] ?? 'Unknown Error',
        ]);
    }
}
