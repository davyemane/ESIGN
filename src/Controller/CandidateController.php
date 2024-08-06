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
            // Validation supplémentaire
            // if ($this->validateCandidateData($candidate, $form)) {
            //     return $this->render('candidate/register_update.html.twig', [
            //         'form' => $form->createView(),
            //         'candidate' => $candidate,
            //     ]);
            // }

            // Convertir le nom en majuscules
            $candidate->setName(strtoupper($candidate->getName()));

            $this->handleFileUpload($form, $candidate, $slugger, 'certificate', 'certificates_directory');
            $this->handleFileUpload($form, $candidate, $slugger, 'payementReceipt', 'payment_receipts_directory');
            $this->handleFileUpload($form, $candidate, $slugger, 'photo', 'photos_directory');

            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Vos informations ont été enregistrées avec succès.');
            return $this->redirectToRoute('app_candidate_preview', ['id' => $candidate->getId()]);
        }

        return $this->render('candidate/register_update.html.twig', [
            'form' => $form->createView(),
            'candidate' => $candidate,
        ]);
    }

    private function validateCandidateData(Candidate $candidate, $form): bool
    {
        $isValid = true;

        if (empty($candidate->getName()) || strlen($candidate->getName()) < 2) {
            $form->get('name')->addError(new FormError("Le nom doit contenir au moins 2 caractères."));
            $isValid = false;
        }

        // if (!in_array($candidate->getSexe(), ['M', 'F'])) {
        //     $form->get('sexe')->addError(new FormError("Le sexe doit être 'M' ou 'F'."));
        //     $isValid = false;
        // }

        // // $now = new \DateTime();
        // // $age = $candidate->getDateOfBirth()->diff($now)->y;
        // // if ($age < 18) {
        // //     $form->get('dateOfBirth')->addError(new FormError("Le candidat doit avoir au moins 18 ans."));
        // //     $isValid = false;
        // // }

        // if (empty($candidate->getPlaceOfBirth())) {
        //     $form->get('placeOfBirth')->addError(new FormError("Le lieu de naissance est requis."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getNationality())) {
        //     $form->get('nationality')->addError(new FormError("La nationalité est requise."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getCni()) || !preg_match('/^\d{9}$/', $candidate->getCni())) {
        //     $form->get('cni')->addError(new FormError("Le numéro CNI doit contenir 9 chiffres."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getPhoneNumber()) || !preg_match('/^\d{9}$/', $candidate->getPhoneNumber())) {
        //     $form->get('phoneNumber')->addError(new FormError("Le numéro de téléphone doit contenir 9 chiffres."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getEmail()) || !filter_var($candidate->getEmail(), FILTER_VALIDATE_EMAIL)) {
        //     $form->get('email')->addError(new FormError("L'adresse email n'est pas valide."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getRegion())) {
        //     $form->get('region')->addError(new FormError("La région est requise."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getDepertement())) {
        //     $form->get('depertement')->addError(new FormError("Le département est requis."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getField())) {
        //     $form->get('field')->addError(new FormError("Le domaine est requis."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getExaminationCenter())) {
        //     $form->get('examinationCenter')->addError(new FormError("Le centre d'examen est requis."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getCertificate())) {
        //     $form->get('certificate')->addError(new FormError("Le certificat est requis."));
        //     $isValid = false;
        // }

        // $currentYear = (int) date('Y');
        // $certYear = (int) $candidate->getCertificateYear();
        // if ($certYear < 1900 || $certYear > $currentYear) {
        //     $form->get('certificateYear')->addError(new FormError("L'année du certificat n'est pas valide."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getLanguage())) {
        //     $form->get('language')->addError(new FormError("La langue est requise."));
        //     $isValid = false;
        // }

        // if (empty($candidate->getTransactionNumber())) {
        //     $form->get('transactionNumber')->addError(new FormError("Le numéro de transaction est requis."));
        //     $isValid = false;
        // }

        return $isValid;
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
        $options->set('defaultFont', 'Arial');
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
