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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Form\FormError;

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
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $candidate = $user->getCandidate();
        $isNewCandidate = false;
        if (!$candidate) {
            $candidate = new Candidate();
            $candidate->setUser($user);
            $candidate->generateCandidateID(); // Génère l'identifiant unique ici
            $isNewCandidate = true;
        }

        $form = $this->createForm(CandidateFormType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Convertir le nom en majuscules
            $candidate->setName(strtoupper($candidate->getName()));

            $this->handleFileUpload($form, $candidate, $slugger, 'certificate', 'certificates_directory');
            $this->handleFileUpload($form, $candidate, $slugger, 'payementReceipt', 'payment_receipts_directory');
            $this->handleFileUpload($form, $candidate, $slugger, 'photo', 'photos_directory');

            if ($isNewCandidate) {
                $entityManager->persist($candidate);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été enregistrées avec succès.');
            return $this->redirectToRoute('app_candidate_preview', ['id' => $candidate->getId()]);
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



    #[Route('/candidate/preview/{id}', name: 'app_candidate_preview')]
    public function preview(EntityManagerInterface $entityManager, int $id): Response
    {
        $candidate = $entityManager->getRepository(Candidate::class)->find($id);
        if (!$candidate) {
            throw $this->createNotFoundException('Candidat non trouvé.');
        }

        return $this->render('candidate/preview.html.twig', [
            'candidate' => $candidate,
        ]);
    }

    #[Route('/candidate/generate-pdf/{id}', name: 'app_candidate_generate_pdf')]
    public function generatePdf(EntityManagerInterface $entityManager, int $id): Response
    {
        $candidate = $entityManager->getRepository(Candidate::class)->find($id);
        if (!$candidate) {
            throw $this->createNotFoundException('Candidat non trouvé.');
        }

        $options = new Options();
        $options->set('defaultFont', 'Montserrat');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $html = $this->renderView('candidate/pdf.html.twig', [
            'candidate' => $candidate,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="fiche_inscription.pdf"',
        ]);
    }
}
