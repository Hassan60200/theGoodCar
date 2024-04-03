<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Services\EmailNotificationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(private readonly EmailNotificationManager $emailNotificationManager,
                                private readonly UserRepository           $userRepository,
                                private readonly EntityManagerInterface   $entityManager)
    {
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $password = $form->get('password')->getData();
            $user->setPassword($passwordEncoder->hashPassword($user, $password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->emailNotificationManager->sendEmailAfterRegistration($user);
            $this->emailNotificationManager->verifyEmail($user);

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $this->userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash('danger', 'Aucun compte n\'a été trouvé avec cet email !');

                return $this->redirectToRoute('app_forgot_password');
            } else {
                $resetPasswordToken = uniqid();

                $user->setResetPasswordToken($resetPasswordToken);
                $this->entityManager->flush();

                $this->emailNotificationManager->sendEmailResetPassword($user);

                $this->addFlash('success', 'Un email vous a été envoyé pour réinitialiser votre mot de passe !');

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/forgot-password.html.twig');
    }

    #[Route(path: '/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = $this->userRepository->findOneBy(['resetPasswordToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'Token invalide !');

            return $this->redirectToRoute('app_forgot_password');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $user->setPassword($passwordEncoder->hashPassword($user, $password));
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été réinitialisé !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset-password.html.twig', ['token' => $token, 'form' => $form->createView()]);
    }

    #[Route('/registration/success', name: 'app_registration_success')]
    public function registrationSuccess(): Response
    {
        return $this->render('registration/success.html.twig');
    }
}
