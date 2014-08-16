<?php namespace Leger\DTO;

interface DTOConstructorInterface {

    /**
     * Constructor
     *
     * @param      $vars
     * @param bool $recursive Decides if we should look inside arrays
     * @param bool $overwrite Do we want to allow for a property to be overwritten or should we throw an exception
     */
    public function make($vars = null, $recursive = false, $overwrite = true);
}