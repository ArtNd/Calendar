<?php


namespace App\Controller\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        $content = "Vous n'avez pas les droits, si vous pensez qu'ils s'agit d'un erreur veuillez contacter Payicam";
        return new Response($content, 403);
    }
}