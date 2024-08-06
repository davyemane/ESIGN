<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CandidateController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }

    #[Route('/candidate/register', name: 'app_candidate_register')]
    #[Route('/candidate/update', name: 'app_candidate_update')]
    public function registerOrUpdate(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $candidate = $user->getCandidate();
        if (!$candidate) {
            $candidate = new Candidate();
            $candidate->setUser($user);
        }

        $form = $this->createForm(CandidateFormType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Convertir le nom en majuscules
            $candidate->setName(strtoupper($candidate->getName()));

            $this->handleFileUpload($form, $candidate, $slugger, 'certificate', 'certificates_directory');
            $this->handleFileUpload($form, $candidate, $slugger, 'payementReceipt', 'payment_receipts_directory');
            $this->handleFileUpload($form, $candidate, $slugger, 'photo', 'photos_directory');

            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été enregistrées avec succès.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('candidate/register_update.html.twig', [
            'form' => $form->createView(),
            'candidate' => $candidate,
        ]);
    }

    private function handleFileUpload($form, $candidate, $slugger, $fieldName, $directoryParameter): void
    {
        /** @var UploadedFile $file */
        $file = $form->get($fieldName)->getData();

        if ($file) {
            $candidateName = $slugger->slug($candidate->getName());
            $newFilename = strtolower($candidateName) . '_' . $fieldName . '_' . uniqid() . '.' . $file->guessExtension();

            try {
                $file->move(
                    $this->getParameter($directoryParameter),
                    $newFilename
                );

                $setterMethod = 'set' . ucfirst($fieldName);
                if (method_exists($candidate, $setterMethod)) {
                    $candidate->$setterMethod($newFilename);
                }
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier ' . $fieldName);
            }
        }
    }

    #[Route('/candidate/profile', name: 'app_candidate_profile')]
    public function profile(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $candidate = $user->getCandidate();
        if (!$candidate) {
            return $this->redirectToRoute('app_candidate_register');
        }

        return $this->render('candidate/profile.html.twig', [
            'candidate' => $candidate,
        ]);
    }


    #[Route('/candidate/save-progress', name: 'app_candidate_save_progress', methods: ['POST'])]
    public function saveProgress(Request $request, SessionInterface $session): JsonResponse
    {
        $formData = $request->request->all();
        $currentStep = $request->request->get('currentStep', 0);
        
        $session->set('candidate_form_progress', [
            'formData' => $formData,
            'currentStep' => $currentStep
        ]);

        return new JsonResponse(['success' => true]);
    }

    #[Route('/candidate/load-progress', name: 'app_candidate_load_progress', methods: ['GET'])]
    public function loadProgress(SessionInterface $session): JsonResponse
    {
        $progress = $session->get('candidate_form_progress', ['formData' => [], 'currentStep' => 0]);
        return new JsonResponse($progress);
    }

}
