<?php

namespace AppBundle\Service;


class Browser
{
    private $curl;
    private $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';


    public function request($url, $data = array(), $get = false, $buildQuery = true)
    {
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_USERAGENT      => $this->userAgent,
                CURLOPT_URL            => $url
            ));

        if (!empty($data) && !$get && $buildQuery) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
        } elseif(!empty($data) && $get && $buildQuery) {
            curl_setopt($this->curl, CURLOPT_URL, $url.'?'.http_build_query($data));
        } elseif (!empty($data) && !$get && !$buildQuery) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        }


        $result = curl_exec($this->curl);
        $curlInfo = curl_getinfo($this->curl);

        curl_close($this->curl);

        return ($curlInfo['http_code'] == 200) ? $result : false;

    }

} 