<?php
namespace app\base;

/**
 * custom class for ajax response
 * 
 * @author Thiago You <thya9o@outlook.com>
 */
class AjaxResponse extends \stdClass  {
    /**
     * @var boolean
     */
    public $success;
    
    /**
     * @param boolean $success
     */
    public function __construct($success = true) 
    {
        $this->success = $success;
    }
}