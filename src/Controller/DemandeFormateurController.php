<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DemandeFormateurRepository;
use App\Entity\DemandeFormateur;
use App\Form\DemandeFType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;


class DemandeFormateurController extends AbstractController
{
    /**
     * @Route("/demande/formateur/list", name="list_demande_formateur")
     */
    public function list(): Response
    {
        $form=$this->getDoctrine()->getRepository(DemandeFormateur::class)
        ->findAll();

        return $this->render('demandeformateur/listDemandeFormateur.html.twig', [
            'form' => $form,
        ]);
    }
    
     
   
      /**
     * @param $prenom
     * @param DemandeFormateurRepository $repository
     * @param Request $request
     * @Route("/demande/formateur/recherche", name="recherche")
     */
    function recherche(DemandeFormateurRepository $repository , Request $request){
        $data=$request->get('search');
                $formateur=$repository->findBy(['Prenom'=>$data]);
                return $this->render('demandeformateur/afficheDemandeFormateur.html.twig', [
                    'formateur' => $formateur
                ]);
            }
    /**
     * @Route("/demande/formateur/new", name="Adddemandeformateur")
     * Method({"GET","POST"})
     */    public function Add(Request $request): Response
    {
        $demandeformateur = new DemandeFormateur();
        $form =$this->createForm(DemandeFType::class,$demandeformateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
           
            $file = $form->get('CV')->getData();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads_directory'),$filename);
            $demandeformateur->setCv($filename);
           
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->persist($demandeformateur);
            $entityManager->flush();
            return $this->redirectToRoute('list_demande_formateur');

        }
        
        return $this->render('demandeformateur/addDemandeFormateur.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/demande/formateur/Delete/{id}" , name="Delete_formateur")
     *Method({"DELETE"})
     */
    public function Delete(Request $request,$id)
    {
            $formateur = $this->getDoctrine()
            ->getRepository(DemandeFormateur::class)
            ->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formateur);
            $entityManager->flush();

            
            return $this->redirectToRoute('list_demande_formateur');
            
}
        /**
        * @Route("/download/{id}", name="download_file")
        **/
        public function downloadFileAction($id){
            
            $formateur = $this->getDoctrine()
            ->getRepository(DemandeFormateur::class)
            ->find($id);
            $var='uploads/'. $formateur->getCV();
            (string)$var;
            $response = new BinaryFileResponse($var);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'CV.txt');
            return $response;
    }
}
