<?php

namespace Ndp\CustomCollect\Collections;

/**
 * Description of SelectAttrib
 *
 * @author Noe de Paz <ndepaz2016@gmail.com>
 */
class SelectAttribute {
    protected $field,$alias,$delimiter = '.', $dummyObject;
    public function __construct(string $select) {
        $selectAs = $this->getRegexMatch('/^\s*([\w\.]*)\s*as\s*([\w]*)\s*$/', $select);
        $selectLeftWithDot = $this->getRegexMatch('/^\s*([\w\.]*)$/', $select);
        $selectLeft = $this->getRegexMatch('/^\s*([\w]*)\s*$/', $select);
        
        if(array_flatten($selectAs) !== []){
            $this->field = array_first($selectAs[1]);
            $this->alias =array_first($selectAs[2]);
        }else if($selectLeftWithDot ){
            $this->field = array_first($selectLeftWithDot[1]);
            $this->alias = array_last(explode($this->delimiter,$this->field));
        } else if(array_flatten($selectLeft) !== []){
            $this->field = array_first($selectLeft[1]);
            $this->alias = $this->field;
        }
    }
    public function getField(){
        return $this->field;
    }
    public function getAlias(){
       return $this->alias;
    }

    /**
     * @param $object
     * @param $dummy
     * @return mixed
     */
    public function setAliasFieldForObject($object, $dummy) {
        $properties = explode($this->delimiter, $this->getField());
        $dummy->{$this->getAlias()} = $this->getLastPropertyValue($object,$properties);
        return $dummy;
    }

    protected function getLastPropertyValue($object,array $properties) {
        $value = null;
        foreach ($properties as $property) {
            if($value == null){
                $value = $object->{$property};
            } else{
                $value = $value->{$property};
            }
        }
        return $value;
    }
    function getRegexMatch($regexPattern,$string) {
        $matches = null;
        preg_match_all($regexPattern, $string, $matches);
        if($matches == []){
            return [];
        }
        return $matches;
    }
}
