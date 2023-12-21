<?php

namespace App\Controller;

use App\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\ProjetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProfilController extends AbstractController
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/profil");
     */

    public function profil(ProjetRepository $projetRepository, UserRepository $userRepository, Request $request)
    {
        $data = $request->request->all();

        return $this->render('profil/profil.html.twig', [
            'projets' => $projetRepository->findAll(),
            'user' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/pwchange", name="pwchange", methods={"POST"})
     */
    public function pwchange(Request $request, ProjetRepository $projetRepository)
    {
        $data = $request->request->all();
        $user = $this->getUser();

        if ($data["password2"] == $data["password3"]) {
            $encoded = $this->passwordEncoder->encodePassword($user, $data["password2"]);
            $user->setPassword($encoded);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été bien modifié !');
            return $this->render('profil/profil.html.twig', [
                'projets' => $projetRepository->findAll(),
            ]);
        } else {
            $this->addFlash('danger', 'Les champs du mot de passe ne sont pas identiques !');
        }

        return $this->render('profil/profil.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/domainechange", name="domainechange", methods={"POST"})
     */
    public function domainechange(Request $request, ProjetRepository $projetRepository)
    {
        $pro = new Projet;
        $data = $request->getContent();
        $projets = $projetRepository->findAll();
        $id = substr($data, 14);

        foreach ($projets as $projet) {
            if ($projet->getId() == $id) {
                $pro = $projet;
            }
        }
        $user = $this->getUser();

        $user->setProjet($pro);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('profil/profil.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }
}
