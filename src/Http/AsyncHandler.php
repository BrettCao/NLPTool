<?php

namespace Mozzos\NLPTool\Http;

/**
 * Class AsyncHandler
 * @package Mozzos\NLPTool\Http
 */
class AsyncHandler extends ArrayObject
{
    protected $_http = null;
    //Callback functions
    protected $onRecvResponse = NULL;
    protected $recvCtx = NULL;
    protected $onBusiness = NULL;
    protected $busCtx = NULL;

    protected $onAPIReturn = NULL;
    protected $apiCtx = NULL;

    protected $finished = false;

    public function __construct($http)
    {
        $this->_http = $http;
        parent::__construct(array());
    }

    public function getHttp()
    {
        return $this->_http->_curlInit;
    }

    public function setOnRecvResponse($cb, $ctx)
    {
        $this->onRecvResponse = $cb;
        $this->recvCtx = $ctx;
    }

    public function setOnBusiness($cb, $ctx)
    {
        $this->onBusiness = $cb;
        $this->busCtx = $ctx;
    }

    public function setOnAPIReturn($cb, $ctx)
    {
        $this->onAPIReturn = $cb;
        $this->apiCtx = $ctx;
    }

    public function getArrayCopy()
    {
        if (!$this->finished) async_http::in()->wait((string)$this);
        return parent::getArrayCopy();
    }

    public function count()
    {
        if (!$this->finished) async_http::in()->wait((string)$this);
        return parent::count();
    }

    public function serialize()
    {
        if (!$this->finished) async_http::in()->wait((string)$this);
        return parent::serialize();
    }

    public function offsetGet($index)
    {
        if (!$this->finished) async_http::in()->wait((string)$this);
        return parent::offsetGet($index);
    }

    public function offsetExists($index)
    {
        if (!$this->finished) async_http::in()->wait((string)$this);
        return parent::offsetExists($index);
    }

    public function offsetSet($index, $newval)
    {
        if (!$this->finished) async_http::in()->wait((string)$this);
        return parent::offsetSet($index, $newval);
    }

    public function offsetUnset($index)
    {
        if (!$this->finished) async_http::in()->wait((string)$this);
        return parent::offsetUnset($index);
    }

    public function RequestCompeleted()
    {
        $this->_http->processRequest();
        $data = curl_multi_getcontent($this->getHttp());

        if (is_callable($this->onRecvResponse)) {
            $data = call_user_func($this->onRecvResponse, $this->_http, $data, $this->recvCtx);
        }
        if (is_callable($this->onBusiness)) {
            $data = call_user_func($this->onBusiness, $data, $this->busCtx);
        }
        if (is_callable($this->onAPIReturn)) {
            $data = call_user_func($this->onAPIReturn, $data, $this->apiCtx);
        }
        $this->exchangeArray($data);
        $this->finished = true;
    }

    public function __toString()
    {
        $id = __CLASS__ . (string)$this->getHttp();
        return $id;
    }
}