<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface{
	public function __construct(RouterInterface $router){
//		$this->session = $session;
		$this->router = $router;
	}

	public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
	{
//		//return a warning message
//		$this->session->getFlashBag()->add('Warning', 'Unauthorized access');
		//redirect to "Login" page
		return new RedirectResponse($this->router->generate('app_login'));
	}
}
