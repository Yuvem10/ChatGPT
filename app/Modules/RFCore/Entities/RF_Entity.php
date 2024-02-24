<?php

namespace RFCore\Entities;

use \Datetime;
use Exception;
use ReflectionClass;

class RF_Entity
{
    protected $nullableProperties = [];

    /**
     * Constructor
     * @param $params array
     * @throws Exception
     */
    public function __construct($params = null)
    {
		$this->updateNullablePropertiesArray();

		// apply update on building object
        if ($params != null)
            $this->update($params);
    }

    /**
     * Update method
     * @param $params
     * @throws Exception
     */
    public function update($params){
		$this->updateNullablePropertiesArray();

		// manage param settings
        foreach ($params as $prop=>$value)
        {
            switch(gettype($this->$prop))
            {
                case "integer":
                    // convert to int
                    $this->$prop = intval($value);
                break;
                case "boolean":
                    // convert to int
                    $this->$prop = ($value === TRUE);
                break;

                case "object":
                    // manage property as date
                    if(get_class($this->$prop)=="DateTime" && gettype($value)=="string")
                    {
                        $this->setDateProperty($prop,$value);
                    }else{
                        // all case of property
                        $this->$prop = $value;
                    }
                break;

                default:
                    // all case of property
                    $this->$prop = $value;
                break;
            }

        }

        foreach ($this as $prop=>$value) {
            if( ($prop != 'nullableProperties') &&
                !in_array($prop, $this->nullableProperties)
				&& !in_array($prop, ['__initializer__','__cloner__'])
				&&
                ($value===null))
            {
                throw new Exception("Property '" . $prop . "' of class '".static::class."' cannot be null.");
            }
        }
    }

    /**
     * get Values method
     *
     * @return array  with all properties values
     */
    public function getProperties()
    {
        // extract Object as Array
        $ret = get_object_vars($this);
        // unset global properties
        unset($ret['__cloner__'],$ret['__initializer__'],$ret['__isInitialized__']);
        unset($ret['nullableProperties']);

        return $ret;
    }

	/**
	 * Parse all the properties of the entity and add the nullable ones to the nullableProperties array on runtime
	 * @return void
	 */
	public function updateNullablePropertiesArray()
	{
		// Retrieving all the properties of the entity
		$reflection = new ReflectionClass($this);
		$properties = $reflection->getProperties();

		foreach ($properties as $property) {
			// Retrieving the doc comment of the property
			$docComment = $property->getDocComment();
			$docComment = strtolower($docComment);

			// Making sure the property is defined as a column and is nullable
			if (
                (
                    (strpos($docComment, '@column') !== false)
                    ||
                    (strpos($docComment, '@joincolumn') !== false)
                )
				&&
				(
					(strpos($docComment, 'nullable=true') !== false)
					||
					(strpos($docComment, '@generatedvalue') !== false)
				)
			) {
				// Adding the property to the nullableProperties array
				$propertyName = $property->getName();
				$this->nullableProperties[] = $propertyName;
			}
		}
	}

    /**
     * get Value method
     * @param string Property Name
     * @return mixed value of propertie or null
     */
    public function getProperty($propName,$asArray=FALSE)
    {
        $ret=$this->$propName??NULL;

        if( ($ret!=NULL) && (gettype($ret)=='object') && ($asArray==TRUE))
        {
            $ret=get_object_vars($ret);
        }

        return $ret;
    }



    /**
     * Set a date property
     * @param string date
	 * @throws Exception
     */
    public function setDateProperty($propName, $dateString)
    {
        if($dateString==""){
            // set property to NULL
            $this->$propName = NULL;
        }else{
            // set date to $propName property
            $date = new DateTime($dateString);
			$this->$propName=$date;
        }
    }

	/**
	 * Stringify the current entity in a JSON encoded format
	 * @return false|string The JSON encoded entity
	 */
	public function __toString()
	{
		$compatibleProperties = [];
		$properties = $this->getProperties();

		// Iterating through each property of the entity in order to only keep JSON compatible properties
		foreach ($properties as $property => $value)
		{
			switch (gettype($value))
			{
				case 'integer':
				case 'boolean':
				case 'string':
				case 'double':
					$compatibleProperties[$property] = $value;
					break;
				case 'object':
					// DateTime instances can be JSON encoded
					if (($value instanceof DateTime))
					{
						$compatibleProperties[$property] = $value;
					}
					break;
			}
		}

		return json_encode($compatibleProperties);
	}

	/**
	 * Generate the targetEntity property value used to save eventLog entities
	 * @return string EventLog targetEntity property compatible string
	 */
	public function generateTargetEntityString()
	{
		return get_class($this).' => '.$this->getProperty('id');
	}

    /**
     * Returns the list of mandatory properties based on the nullableProperties property
     * @return array
     */
    public function getMandatoryProperties(): array
    {
        $ret = [];

        foreach($this->getProperties() as $property => $value)
        {
            if (!in_array($property, $this->nullableProperties))
            {
                $ret[] = $property;
            }
        }

        return $ret;
    }

    /**
     * Checks if all mandatory properties are set
     * @return bool True if all mandatory properties are set, false otherwise
     */
    public function checkForMissingFields() : bool
    {
        $ret = true;

        foreach ($this->getMandatoryProperties() as $property)
        {
            if ($this->getProperty($property) === null)
            {
                $ret = false;
                break;
            }
        }

        return $ret;
    }
}
