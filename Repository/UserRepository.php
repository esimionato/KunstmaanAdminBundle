<?php

namespace Kunstmaan\AdminBundle\Repository;

use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Doctrine\ORM\EntityRepository;

/**
 * BlogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{

	public function getUser($user_id, EntityManager $em)
	{
		$user = $em->getRepository('KunstmaanAdminBundle:User')->find($user_id);
		if (!$user) {
			throw new NotFoundHttpException('The id given for the user is not valid.');
		}
		return $user;
	}
	
}