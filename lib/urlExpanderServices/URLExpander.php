<?php

class URLExpander{
    private $url;

    private $api_user;
    private $api_pass;
    private $api_url;

    private $api_success_code;
    private $api_response_format;

    public function setApiResponseFormat($format){
        $this->api_response_format = $format;
    }

    public function getApiResponseFormat(){
        return $this->api_response_format;
    }

    public function setUrl($url){
        $this->url = $url;
    }

    public function getUrl(){
        return $this->url;
    }

    public function setApiUser($api_user){
        $this->api_user = $api_user;
    }

    public function getApiUser(){
        return $this->api_user;
    }

    public function setApiPass($api_pass){
        $this->api_pass = $api_pass;
    }

    public function getApiPass(){
        return $this->api_pass;
    }

    public function setApiUrl($api_url){
        $this->api_url = $api_url;
    }

    public function getApiUrl(){
        return $this->api_url;
    }

    public function getApiSuccessCode(){
        return $this->api_success_code;
    }

    public function setApiSuccessCode($api_success_code){
        $this->api_success_code = $api_success_code;
    }
}
