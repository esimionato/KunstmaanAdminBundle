<?php

namespace Kunstmaan\AdminBundle\Entity;

use Kunstmaan\AdminBundle\Entity\User as Baseuser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Kunstmaan\AdminBundle\Modules\ClassLookup;

/**
 * omnext addcommand
 * 
 * @author Kristof Van Cauwenbergh
 *
 * @ORM\Entity
 * @ORM\Table(name="addcommand")
 * 
 */
class AddCommand extends Command implements Undoable{
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $user;
	
	/**
	 * @ORM\Column(type="integer", nullable="true")
	 */
	protected $entityid;
	
	/**
	 * @ORM\Column(type="string", nullable="true")
	 */
	protected $entityclass;
	
	public function __construct(EntityManager $em, Baseuser $user){
     	$this->em = $em;
     	$this->user = $user;
    }

   	public function executeimpl($options){
   		$this->em->persist($options['entity']);
   		$this->em->flush();
   		$this->em->refresh($options['entity']);
   		
   		$this->entityid = $options['entity']->getId();
   		$this->entityclass = ClassLookup::getClass($options['entity']);
   		
   		$this->em->persist($this);
   		$this->em->flush();
   	}
    
    public function removeimpl(){
    	// TODO extra actions
    }
    
    public function undo(){
    	$repo = $this->em->getRepository($this->entityclass);
    	$entity = $repo->find($this->entityid);
    	$this->em->remove($entity);
    	$this->em->flush();
    	
		$this->remove();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId(){
    	return $this->id;
    }
    
    /**
     * Set id
     *
     * @param id integer
     */
    public function setId($id){
    	$this->id = $id;
    }
    
    public function getUser(){
    	return $this->user;
    }
    
    public function setUser($channel){
    	$this->user = $channel;
    }
}