<?php

namespace tymo49\ShortlinkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/shortlink/{linkname}", name="link_redir")
     * @Method("GET")
     */
    public function indexAction($linkname)
    {
        $em = $this->getDoctrine()->getManager();
        $linkExist = $em->getRepository('tymo49\ShortlinkBundle\Entity\Link')->checkNewLinkName($linkname);
        if(!empty($linkExist))
        {

            return $this->redirect($linkExist['link'], 301);
        }
        else
        {
            return $this->redirectToRoute('link_add');
        }
    }
}
