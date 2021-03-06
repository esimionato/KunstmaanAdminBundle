<?php

namespace Kunstmaan\AdminBundle\AdminList;

use Kunstmaan\AdminListBundle\AdminList\AdminListFilter;
use Kunstmaan\AdminListBundle\AdminList\FilterDefinitions\DateFilterType;
use Kunstmaan\AdminListBundle\AdminList\FilterDefinitions\StringFilterType;
use Kunstmaan\AdminListBundle\AdminList\AbstractAdminListConfigurator;

class LogAdminListConfigurator extends AbstractAdminListConfigurator{

	public function buildFilters(AdminListFilter $builder){
		$builder->add('user', new StringFilterType("user"), "User");
		$builder->add('status', new StringFilterType("status"), "Status");
        $builder->add('message', new StringFilterType("message"), "Message");
        $builder->add('createdat', new DateFilterType("createdat"), "Created At");
    }
    
	public function buildFields()
    {
    	$this->addField("user", "User", true);
    	$this->addField("status", "Status", true);
    	$this->addField("message", "Message", true);
    	$this->addField("createdat", "Created At", true);
    }

	public function canAdd() {
        return false;
    }

    public function getAddUrlFor($params=array()) {
    	return array();
    }

    public function canEdit() {
    	return false;
    }
    
    public function getEditUrlFor($item) {
    	return array();
    }

    public function canDelete($item) {
        return false;
    }

    public function getAdminType($item) {
        return null;
    }

    public function getRepositoryName() {
        return 'KunstmaanAdminBundle:LogItem';
    }

    public function adaptQueryBuilder($querybuilder, $params=array()) {
        parent::adaptQueryBuilder($querybuilder);
    }


    public function getValue($item, $columnName) {
        $result = parent::getValue($item, $columnName);

        if($result instanceof \Doctrine\ORM\PersistentCollection) {
            $results = "";
            foreach($result as $entry) {
                $results[] = $entry->getName();
            }

            return implode(', ', $results);
        }

        if(is_array($result)) {
            return implode(', ', $result);
        }

        return $result;
    }
}
