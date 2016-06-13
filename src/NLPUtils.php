<?php
/**
 * Created by PhpStorm.
 * User: Brett
 * Date: 2016/6/13
 * Time: 23:14
 */

namespace Seek\NLPTool;


use Illuminate\Config\Repository;

class NLPUtils
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var array
     */
    protected $notifications = [];

    /**
     * Toastr constructor.
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
}