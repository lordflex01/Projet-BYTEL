<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/user")
 */
class userController extends AbstractController
{

    /**
     * @Route("/user", name="user1", methods={"GET","POST"})
     */
    public function user(UserRepository $userRepository): Response
    {
        return $this->render('user/user.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/user.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On récupère les images transmises
            $image = $form->get('image')->getData();

            //on gèrère un nouveau nom de fichier
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            //on copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('image_directory'),
                $fichier
            );

            //on stocke l'image dans la base de données (son nom)
            $img = new Image();
            $img->setName($fichier);
            $user->setImage($img);

            $entityManager = $this->getDoctrine()->getManager();

            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
            $user->setPassword($encoded);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom de l'image
            $img = $user->getImage();
            $nom = $img->getName();
            //on supprime le fichier
            unlink($this->getParameter('image_directory') . '/' . $nom);
            //on supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($img);
            $em->flush();

            //On récupère les images transmises
            $image = $form->get('image')->getData();

            //on gèrère un nouveau nom de fichier
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            //on copie le fichier dans le dossier uploads
            $image->move(
                $this->getParameter('image_directory'),
                $fichier
            );

            //on stocke l'image dans la base de données (son nom)
            $img = new Image();
            $img->setName($fichier);
            $user->setImage($img);

            $entityManager = $this->getDoctrine()->getManager();

            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
            $user->setPassword($encoded);

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            //on récupère le nom de l'image
            $img = $user->getImage();
            $nom = $img->getName();
            //on supprime le fichier
            unlink($this->getParameter('image_directory') . '/' . $nom);
            //on supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($img);
            $em->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('user');
    }

    /**
     * @Route("supprime/image/{id}", name="user_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            //on récupère le nom de l'image
            $nom = $image->getName();
            //on supprime le fichier
            unlink($this->getParameter('image_directory') . '/' . $nom);

            //on supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token invalide'], 400);
        }
    }
}
