<?php

namespace CDNServer\Core\Controller\Admin;
use CDNServer\Core\Entity\User;
use CDNServer\Core\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package CDNServer\Core\Controller\Admin
 * @author Pierre LECERF <plecerf@keley-live.com>
 */
class UserController extends Controller
{
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CDNServerCore:User')->findAll();

        return array(
            'entities'  => $entities
        );
    }

    public function showAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CDNServerCore:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    public function createAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException();

        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_show', array('id' => $entity->getId())));
        }

        return $this->render('CDNServerCore:Admin/User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('admin_user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    public function newAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException();

        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    public function editAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CDNServerCore:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        //$form->remove('plainPassword');
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    public function updateAction(Request $request, $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CDNServerCore:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $this->container->get('fos_user.user_manager')->updatePassword($entity);
            $em->persist($entity);

            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }

        return $this->render('CDNServerCore:Admin/User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            throw $this->createAccessDeniedException();

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CDNServerCore:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_user'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
}
