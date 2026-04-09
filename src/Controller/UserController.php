<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{

   #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request , SluggerInterface $slugger ,  ManagerRegistry $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User($passwordHasher);  

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) { 
        $userName=$form->get('username')->getData();
        $user->setUserName($userName);
       $password = $form->get('password')->getData();
       $user->setPassword($password);
       $pdp = $form->get('photoprofile')->getData();
        if ($pdp) {
            $originalFilename = pathinfo($pdp->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$pdp->guessExtension();
        
            $pdp->move(
                $this->getParameter('pdp_directory'),
                $newFilename
            );
            $user->setProfilePicture($newFilename);
        }
       $em->getManager()->persist($user);
       $em->getManager()->flush();
        return $this->redirectToRoute('home'); 
        }
        return $this->render('inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/admin/users', name: 'admin_user_list')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function list(ManagerRegistry $em): Response
    {
        $allUsers = $em->getRepository(User::class)->findAll();
        $currentUser = $this->getUser();
        $users = array_filter($allUsers, function($user) use ($currentUser) {
        return $user !== $currentUser;
    });

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/new', name: 'admin_user_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function new(Request $request,  SluggerInterface $slugger,  ManagerRegistry $em, UserPasswordHasherInterface $passwordHasher)
{
    $user = new User($passwordHasher);  

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) { 
        $password = $form->get('password')->getData();
       $user->setPassword($password);
        

        $pdp = $form->get('photoprofile')->getData();
        if ($pdp) {
            $originalFilename = pathinfo($pdp->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$pdp->guessExtension();
        
            $pdp->move(
                $this->getParameter('pdp_directory'),
                $newFilename
            );
            $user->setProfilePicture($newFilename);
        }

        $em->getManager()->persist($user);
        $em->getManager()->flush();

        return $this->redirectToRoute('admin_user_list'); 
    }

    return $this->render('user/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/admin/user/edit/{id}', name: 'admin_user_edit')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function edit(User $user, Request $request , UserPasswordHasherInterface $passwordHasher , ManagerRegistry $em, SluggerInterface $slugger): Response
{
    $user->__construct($passwordHasher);
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {

        $pdp = $form->get('photoprofile')->getData();  
        $password = $form->get('password')->getData();
        if (!empty($password)) {
            $user->setPassword($password);
        }

        if ($pdp) {
            $originalFilename = pathinfo($pdp->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$pdp->guessExtension();

            $pdp->move(
                $this->getParameter('pdp_directory'),
                $newFilename
            );

            $user->setProfilePicture($newFilename);
        }

        $em->getManager()->flush();

        return $this->redirectToRoute('admin_user_list');
    }

    return $this->render('user/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}
    #[Route('/admin/user/delete/{id}', name: 'admin_user_delete')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(User $user, ManagerRegistry $em): Response
    {
        $em->getManager()->remove($user);
        $em->getManager()->flush();

        return $this->redirectToRoute('admin_user_list');
    }
}