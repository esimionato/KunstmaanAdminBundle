<?php

namespace Kunstmaan\AdminBundle\Controller;

use Kunstmaan\SearchBundle\Helper\SearchedForAdminListConfigurator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Kunstmaan\AdminBundle\Entity\User;
use Kunstmaan\AdminBundle\Entity\Group;
use Kunstmaan\AdminBundle\Entity\Role;
use Kunstmaan\AdminBundle\Form\UserType;
use Kunstmaan\AdminBundle\Form\GroupType;
use Kunstmaan\AdminBundle\Form\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Kunstmaan\AdminBundle\AdminList\UserAdminListConfigurator;
use Kunstmaan\AdminBundle\AdminList\GroupAdminListConfigurator;
use Kunstmaan\AdminBundle\AdminList\RoleAdminListConfigurator;
use Kunstmaan\AdminBundle\AdminList\LogAdminListConfigurator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SettingsController extends Controller
{
	/**
	 * @Route("/", name="KunstmaanAdminBundle_settings")
	 * @Template()
	 */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$request = $this->getRequest();
    	$adminlist = $this->get("adminlist.factory")->createList(new UserAdminListConfigurator(), $em);
    	$adminlist->bindRequest($request);
    	
    	return array(
    			'useradminlist' => $adminlist
    	);
    }

    /**
     * @Route("/users", name="KunstmaanAdminBundle_settings_users")
     * @Template()
     */
    public function usersAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $adminlist = $this->get("adminlist.factory")->createList(new UserAdminListConfigurator(), $em);
        $adminlist->bindRequest($request);

        return array(
            'useradminlist' => $adminlist,
            'addparams'     => array()
        );
    }
    
    /**
     * @Route("/users/add", name="KunstmaanAdminBundle_settings_users_add")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function adduserAction() {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$request = $this->getRequest();
    	$helper = new User();
    	$form = $this->createForm(new UserType($this->container), $helper, array('password_required' => true));
    	
    	if ('POST' == $request->getMethod()) {
    		$form->bindRequest($request);
    		if ($form->isValid()){
    				$em->persist($helper);
    				$em->flush();
    			return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_settings_users'));
    		}
    	}

    	return array(
    			'form' => $form->createView(),
    	);
    }
    
    /**
     * @Route("/users/{user_id}/edit", requirements={"user_id" = "\d+"}, name="KunstmaanAdminBundle_settings_users_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function edituserAction($user_id) {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$request = $this->getRequest();
    	$helper = $em->getRepository('KunstmaanAdminBundle:User')->getUser($user_id, $em);
    	$form = $this->createForm(new UserType($this->container), $helper, array('password_required' => false));
    	
    	if ('POST' == $request->getMethod()) {
    		$form->bindRequest($request);
    		if ($form->isValid()){
    			$em->persist($helper);
    			$em->flush();
    			return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_settings_users'));
    		}
    	}
    
    	return array(
    			'form' => $form->createView(),
    			'user' => $helper
    	);
    }

    /**
     * @Route("/groups", name="KunstmaanAdminBundle_settings_groups")
     * @Template()
     */
    public function groupsAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $adminlist = $this->get("adminlist.factory")->createList(new GroupAdminListConfigurator(), $em);
        $adminlist->bindRequest($request);

        return array(
            'groupadminlist' => $adminlist,
            'addparams'     => array()
        );
    }

    /**
     * @Route("/groups/add", name="KunstmaanAdminBundle_settings_groups_add")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function addgroupAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $request = $this->getRequest();
        $helper = new Group();
        $form = $this->createForm(new GroupType($this->container), $helper);

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()){
                $em->persist($helper);
                $em->flush();
                return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_settings_groups'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/groups/{group_id}/edit", requirements={"group_id" = "\d+"}, name="KunstmaanAdminBundle_settings_groups_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editgroupAction($group_id) {
        $em = $this->getDoctrine()->getEntityManager();

        $request = $this->getRequest();
        $helper = $em->getRepository('KunstmaanAdminBundle:Group')->find($group_id);
        $form = $this->createForm(new GroupType($this->container), $helper);

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()){
                $em->persist($helper);
                $em->flush();
                return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_settings_groups'));
            }
        }

        return array(
            'form' => $form->createView(),
            'group' => $helper
        );
    }
    
    /**
     * @Route("/searches", name="KunstmaanAdminBundle_settings_searches")
     * @Template()
     */
    public function searchesAction() {
    	$em = $this->getDoctrine()->getEntityManager();
    	$request = $this->getRequest();
    	$adminlist = $this->get("adminlist.factory")->createList(new SearchedForAdminListConfigurator(), $em);
    	$adminlist->bindRequest($request);
    
    	return array(
    			'searchedforadminlist' => $adminlist,
    			'addparams'     => array()
    	);
    }
    
    /**
     * @Route("/log", name="KunstmaanAdminBundle_settings_log")
     * @Template()
     */
    public function logAction() {
    	$em = $this->getDoctrine()->getEntityManager();
    	$request = $this->getRequest();
    	$adminlist = $this->get("adminlist.factory")->createList(new LogAdminListConfigurator(), $em);
    	$adminlist->bindRequest($request);
    
    	return array(
    			'logadminlist' => $adminlist,
    			'addparams'     => array()
    	);
    }
    
    /**
     * @Route("/roles", name="KunstmaanAdminBundle_settings_roles")
     * @Template()
     */
    public function rolesAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $adminlist = $this->get("adminlist.factory")->createList(new RoleAdminListConfigurator(), $em);
        $adminlist->bindRequest($request);

        return array(
            'roleadminlist' => $adminlist,
            'addparams'     => array()
        );
    }

    /**
     * @Route("/roles/add", name="KunstmaanAdminBundle_settings_roles_add")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function addroleAction() {
        $em = $this->getDoctrine()->getEntityManager();

        $request = $this->getRequest();
        $helper = new Role('');
        $form = $this->createForm(new RoleType($this->container), $helper);

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()){
                $em->persist($helper);
                $em->flush();
                return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_settings_roles'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/roles/{role_id}/edit", requirements={"role_id" = "\d+"}, name="KunstmaanAdminBundle_settings_roles_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editroleAction($role_id) {
        $em = $this->getDoctrine()->getEntityManager();

        $request = $this->getRequest();
        $helper = $em->getRepository('KunstmaanAdminBundle:Role')->find($role_id);
        $form = $this->createForm(new EditRoleType($this->container), $helper);

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()){
                $em->persist($helper);
                $em->flush();
                return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_settings_roles'));
            }
        }

        return array(
            'form' => $form->createView(),
            'role' => $helper
        );
    }    
}
