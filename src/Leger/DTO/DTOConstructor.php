<?php namespace Leger\DTO;

use Leger\DTO\Exceptions\InvalidDTOException;
use Leger\DTO\Exceptions\PropertyAlreadyAssignedException;

abstract class DTOConstructor implements DTOConstructorInterface {

    /**
     * Array of already assigned vars
     * @var array
     */
    private $assignedProperties = [];

    /**
     * Constructor
     *
     * @param      $vars
     * @param bool $recursive Decides if we should look inside arrays
     * @param bool $overwrite Do we want to allow for a property to be overwritten or should we throw an exception
     */
    public function make($vars = null, $recursive = false, $overwrite = true)
    {
        // If $vars was not set, look for Input::all()
        $vars = $vars ?: \Input::all();

        if (empty($vars))
        {
            throw new InvalidDTOException("Nothing wased passed to dto !");
        }

        // Assign the $vars
        $this->assign($vars, $recursive, $overwrite);

        // Remove assignedProperties since it is only used for internal logic
        unset($this->assignedProperties);

        return $this;
    }

    /**
     * Goes through each members of the array and tries to set properties
     *
     * @param $vars
     * @param $recursive
     * @param $overwrite
     *
     * @throws \Exception
     */
    private function assign($vars, $recursive, $overwrite)
    {
        foreach ($vars as $varname => $var)
        {
            if (is_array($var) && $recursive)
            {
                $this->assign($var, $recursive, $overwrite);
            } else
            {
                $this->assignToProperty($varname, $var, $overwrite);
            }
        }
    }

    /**
     * Assign to property if it exists
     *
     * @param $varname
     * @param $var
     * @param $overwrite
     *
     * @throws \Exception
     */
    private function assignToProperty($varname, $var, $overwrite)
    {
        if (array_key_exists($varname, get_object_vars($this)))
        {
            if ($this->hasAlreadyBeenAssigned($varname) && ! $overwrite)
            {
                $childClassName = get_class($this);
                throw new PropertyAlreadyAssignedException("Property [$varname] has already been assigned in [$childClassName] (overwrite option is set to false) !");
            }
            $this->addToAssignedProperties($varname);

            // If the property is already set before we did anything... use default ? initialize object ??
            if (isset($this->$varname))
            {
                dd("is set with", $this->$varname, $varname);
            }

            $this->$varname = $var;
        }
    }

    /**
     * Determine if property has already been assigned
     *
     * @param $varname
     *
     * @return bool
     */
    private function hasAlreadyBeenAssigned($varname)
    {
        return in_array($varname, $this->assignedProperties);
    }

    /**
     * Add the property to already assigned ones
     *
     * @param $varname
     */
    private function addToAssignedProperties($varname)
    {
        $this->assignedProperties[] = $varname;
    }
}


////////////////////////////////////////////////////////////////////////
//@todo: decide if I need to be able to pass typehinted objects
// if(isset($this->$varname))
// {
//     //@todo: pass in constructor .. ????
//     $class = new \ReflectionClass($this->$varname);

//     if ($class->isInstantiable()) {
//         $this->$varname = $class->newInstance();
//     } else {
//         throw new \Exception("Class [$class] is not instentiable");
//     }

//     // $this->$varname = \App::make($this->$varname);

// } else
// {
//     $this->$varname = $var;
// }

////////////////////////////////////////////////////////////////////////
