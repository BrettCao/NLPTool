<?php

namespace Mozzos\NLPTool\Http;

/**
 * Class AsyncHttp
 * @package Mozzos\NLPTool\Http
 */
class AsyncHttp
{
    protected static $ahttp = NULL;

    public static function in()
    {
        if (self::$ahttp == NULL) {
            self::$ahttp = new AsyncHttp();
        }
        return self::$ahttp;
    }

    protected $mh = NULL;
    protected $active = 0;
    protected $map = array();

    private function __construct()
    {
        $this->mh = curl_multi_init();
    }

    public function __destruct()
    {
        curl_multi_close($this->mh);
    }

    public function add($asynchandler)
    {
        curl_multi_add_handle($this->mh, $asynchandler->getHttp());
        $this->map[(string)$asynchandler->getHttp()] = $asynchandler;

        do {
            $mrc = curl_multi_exec($this->mh, $this->active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }

    public function wait($ah = false)
    {
        $fin = false;
        do {
            do {
                $mrc = curl_multi_exec($this->mh, $this->active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            while ($done = curl_multi_info_read($this->mh)) {
                $asynchandler = $this->map[(string)$done['handle']];
                $asynchandler->RequestCompeleted($done['result']);
                if ((string)$asynchandler === $ah) {
                    $fin = true;
                }
                curl_multi_remove_handle($this->mh, $done['handle']);
            }
            if ($fin)
                break;    //读完了当前能够读取的所有数据，包括想要的数据

            if ($this->active > 0) {
                curl_multi_select($this->mh, 0.1);
            }
        } while ($this->active > 0);
    }
}