<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig');
    }

     /**
     * @Route("/users/annonces/ajout", name="users_annonces_ajout")
     */
    public function ajoutAnnonce(Request $request): Response
    {
    	$annonce = new Annonces;

    	$form = $this->createForm(AnnoncesType::class, $annonce);

    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$annonce->setUsers($this->getUser());
    		$annonce->setActive(false);

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($annonce);
    		$em->flush();

    		return $this->redirectToRoute('users');
    		# code...
    	}

        return $this->render('users/annonces/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     /**
     * @Route("/users/profil/modifier", name="users_profil_modifier")
     */
    public function editProfil(Request $request): Response
    {
        
        $user= $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');

            return $this->redirectToRoute('users');
            # code...
        }

        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/users/pass/modifier", name="users_pass_modifier")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            //on vérifie si les deux mots de passe sont identiques
            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succés');

                return $this->redirectToRoute('users');
            }else{
                $this->addFlash('error', 'Les deux mots de passse ne sont pas identiques');

            }


        }
        
        return $this->render('users/editpass.html.twig');
    }
}
