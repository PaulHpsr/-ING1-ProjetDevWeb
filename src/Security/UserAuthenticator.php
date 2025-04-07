<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserAuthenticator extends AbstractAuthenticator
{
    private RouterInterface $router;
    private UserRepository $userRepository;

    public function __construct(RouterInterface $router, UserRepository $userRepository)
    {
        $this->router         = $router;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        // Exécute cet authenticator seulement sur la route app_login avec la méthode POST
        return $request->attributes->get('_route') === 'app_login'
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // Récupération des identifiants depuis le formulaire
        $email     = $request->request->get('_username', '');
        $password  = $request->request->get('_password', '');
        $csrfToken = $request->request->get('_csrf_token');

        error_log("Email fourni : " . $email);
        error_log("Mot de passe fourni : " . $password);

        if (empty($email) || empty($password)) {
            throw new AuthenticationException('Email and password must be provided. Email fourni : "' . $email . '" Mot de passe fourni : "' . $password . '"');
        }

        // Création du Passport en chargeant l'utilisateur via le UserRepository
        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                if (!$user) {
                    throw new AuthenticationException('User not found.');
                }
                if ($user->getStatus() === 'pending') {
                    throw new AuthenticationException('Compte en attente de confirmation par un admin.');
                }
                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken)
            ]
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Redirige vers la page de login en transmettant le message d'erreur
        return new RedirectResponse($this->router->generate('app_login', [
            'error' => $exception->getMessage()
        ]));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Redirige vers la page d'accueil après authentification réussie
        return new RedirectResponse($this->router->generate('app_home'));
    }
}
