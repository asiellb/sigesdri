<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/04/2017
 * Time: 7:02
 */

namespace DRI\UserBundle\Controller;


use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SecurityController extends  BaseController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function lockAction(Request $request)
    {
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);


        return $this->renderLock(array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }


    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return Response
     */
    protected function renderLock(array $data)
    {
        return $this->render('DRIUserBundle:Security:lock.html.twig', $data);
    }

    public function lockCheckAction()
    {

    }
}