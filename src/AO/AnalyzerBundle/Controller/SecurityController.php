<?php

namespace AO\AnalyzerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AO\AnalyzerBundle\Form\Form\LoginForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \AO\AnalyzerBundle\Utils\Help;

/**
 * @author Abdessamad OUER.
 */
class SecurityController extends Controller
{

    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (is_object($user))
        {
            return new RedirectResponse($this->container->get('router')->generate('home'));
        }
        return new RedirectResponse($this->container->get('router')->generate('login'));
    }

    /**
     * 
     * @Route("/login", name="login")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return twig
     */
    public function loginAction(Request $request)
    {

        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirect($this->generateUrl('home'));
        }

        $session = $request->getSession();
        $oForm   = $this->createForm(new LoginForm());

        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR))
        {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        else
        {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $sLocale = $this->getRequest()->getSession()->get('_locale');

        return $this->render('AnalyzerBundle:Security:login.html.twig', array(
                    // Valeur du précédent nom d'utilisateur entré par l'internaute
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                    'locale' => $sLocale,
                    'form' => $oForm->createView()
        ));
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function securityCheckAction()
    {
        
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        
    }

    /**
     *  @Route("/set_locale", name="setLocale")
     */
    public function setLocaleAction(Request $oRequest)
    {
        $sLocale  = $oRequest->request->get('locale');
        $oSession = $this->getRequest()->getSession();
        $oSession->set('_locale', $sLocale);

        return new \Symfony\Component\HttpFoundation\Response(1);
    }

}
