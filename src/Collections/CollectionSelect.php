<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ndp\CustomCollect\Collections;
use Illuminate\Support\Collection;

/**
 * Description of CustomCollections
 *
 * @property callable setDummyObjectProps
 * @author Noe de Paz <ndepaz2016@gmail.com>
 */
class CollectionSelect {
    /* @property Collection $collection;*/
    protected $collection,$setDummyObjectProps,$selectStatements;
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param array $selectStrings
     * @return CollectionSelect
     */
    public function select(... $selectStrings) {
        $selectStrings = array_flatten($selectStrings);
        $selectStatements = [];
        foreach($selectStrings as $selectString){
             $selectStatements[]=new SelectAttribute($selectString);
        }
        $this->selectStatements = $selectStatements;
        return $this;
    }

    /**
     * @param $object
     * @param $dummy
     * @param $setDummyObjectProps
     * @return mixed
     */
    protected function setAdditionalDummyProperties($object,& $dummy,$setDummyObjectProps){
        if(isset($this->setDummyObjectProps) && is_callable($this->setDummyObjectProps)){
            $setDummyObjectProps($dummy,$object);
        }
        return $dummy;
    }

    /**
     * Sets additional properties after the dummy object's dynamic properties are set.
     * @param $setDummyObjectProps
     * @return $this
     */
    public function setAfterSelectCallable($setDummyObjectProps){
        $this->setDummyObjectProps = $setDummyObjectProps;
        return $this;
    }

    public function get(){
        $selectStatements= $this->selectStatements;
        return $this->collection->map(function($object) use($selectStatements) {
            $dummy = new class{};
            foreach ($selectStatements as $select) {
                /* @var SelectAttribute $select */
                $dummy = $select->setAliasFieldForObject($object, $dummy);
            }
            return $this->setAdditionalDummyProperties($object,$dummy,$this->setDummyObjectProps);
        });
    }
}
