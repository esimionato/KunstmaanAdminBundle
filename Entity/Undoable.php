<?php

namespace Kunstmaan\AdminBundle\Entity;

/**
 * implement this if command has to be undoable 
 * 
 * @author Kristof Van Cauwenbergh
 */
interface Undoable{
 
	public function undo();
}