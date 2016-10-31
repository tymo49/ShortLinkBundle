<?php

namespace tymo49\ShortlinkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use tymo49\ShortlinkBundle\Entity\Link;
use tymo49\ShortlinkBundle\Form\LinkType;

/**
 * Link controller.
 *
 * @Route("/link")
 */
class LinkController extends Controller
{

    /**
     * Creates a new Link entity.
     *
     * @Route("/", name="link_add")
     * @Method({"GET", "POST"})
     * @Template("tymo49ShortlinkBundle:Link:add.html.twig")
     */
    public function generateAction(Request $request)
    {
        $link = new Link();
        $form = $this->createForm('tymo49\ShortlinkBundle\Form\LinkGenerateType', $link);
        $form->handleRequest($request);
        $linkName = '';
        $linkExist = '';
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $linkName = $this->get('tymo49.sl.uniqueLinkName')->getUniqueLinkName();
            $link->setName($linkName);

            if(empty($linkExist))
            {
                $em->persist($link);
                $em->flush();
            }
        }
        else
        {
            $linkExist = $em->getRepository('tymo49\ShortlinkBundle\Entity\Link')->checkNewLink($link->getLink());
        }

        return array(
            'link' => $link,
            'form' => $form->createView(),
            'linkName' => $linkName,
            'linkExist' => $linkExist,
        );
    }

    /**
     * Lists all Link entities.
     *
     * @Route("/admin/", name="link_index")
     * @Method("GET")
     * @Template("tymo49ShortlinkBundle:Link:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $links = $em->getRepository('tymo49ShortlinkBundle:Link')->findAll();

        return array(
            'links' => $links,
        );
    }

    /**
     * Creates a new Link entity.
     *
     * @Route("/admin/new", name="link_new")
     * @Method({"GET", "POST"})
     * @Template("tymo49ShortlinkBundle:Link:new.html.twig")
     */
    public function newAction(Request $request)
    {
        $link = new Link();
        $form = $this->createForm('tymo49\ShortlinkBundle\Form\LinkType', $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($link);
            $em->flush();

            return $this->redirectToRoute('link_show', array('id' => $link->getId()));
        }

        return array(
            'link' => $link,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Link entity.
     *
     * @Route("/admin/{id}", name="link_show")
     * @Method("GET")
     * @Template("tymo49ShortlinkBundle:Link:show.html.twig")
     */
    public function showAction(Link $link)
    {
        $deleteForm = $this->createDeleteForm($link);

        return array(
            'link' => $link,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Link entity.
     *
     * @Route("/admin/{id}/edit", name="link_edit")
     * @Method({"GET", "POST"})
     * @Template("tymo49ShortlinkBundle:Link:edit.html.twig")
     */
    public function editAction(Request $request, Link $link)
    {
        $deleteForm = $this->createDeleteForm($link);
        $editForm = $this->createForm('tymo49\ShortlinkBundle\Form\LinkType', $link);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($link);
            $em->flush();

            return $this->redirectToRoute('link_edit', array('id' => $link->getId()));
        }

        return array(
            'link' => $link,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Link entity.
     *
     * @Route("/admin/{id}", name="link_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Link $link)
    {
        $form = $this->createDeleteForm($link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($link);
            $em->flush();
        }

        return $this->redirectToRoute('link_index');
    }

    /**
     * Creates a form to delete a Link entity.
     *
     * @param Link $link The Link entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Link $link)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('link_delete', array('id' => $link->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
