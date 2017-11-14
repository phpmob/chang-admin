<?php

/*
 * This file is part of the PhpMob package.
 *
 * (c) Ishmael Doss <nukboon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpMob\CoreBundle\Controller;

use PhpMob\CoreBundle\Security\UserImpersonatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Ishmael Doss <nukboon@gmail.com>
 */
class ImpersonateUserController
{
    /**
     * @var UserImpersonatorInterface
     */
    private $impersonator;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $authorizationRole;

    /**
     * @param UserImpersonatorInterface $impersonator
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param UserProviderInterface $userProvider
     * @param RouterInterface $router
     * @param string $authorizationRole
     */
    public function __construct(
        UserImpersonatorInterface $impersonator,
        AuthorizationCheckerInterface $authorizationChecker,
        UserProviderInterface $userProvider,
        RouterInterface $router,
        string $authorizationRole
    ) {
        $this->impersonator = $impersonator;
        $this->authorizationChecker = $authorizationChecker;
        $this->userProvider = $userProvider;
        $this->router = $router;
        $this->authorizationRole = $authorizationRole;
    }

    /**
     * @param Request $request
     * @param string $username
     *
     * @return Response
     */
    public function impersonateAction(Request $request, string $username): Response
    {
        if (!$this->authorizationChecker->isGranted($this->authorizationRole)) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userProvider->loadUserByUsername($username);

        if (null === $user) {
            throw new HttpException(Response::HTTP_NOT_FOUND);
        }

        $this->impersonator->impersonate($user);

        $this->addFlash($request, $username);

        return new RedirectResponse($request->get('referer'));
    }

    /**
     * @param Request $request
     * @param string $username
     */
    private function addFlash(Request $request, string $username): void
    {
        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('success', [
            'message' => 'phpmob.user.impersonate',
            'parameters' => ['%name%' => $username],
        ]);
    }
}
