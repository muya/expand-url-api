<?php

include_once dirname(__FILE__) . '/URLExpanderServiceInterface.php';
include_once dirname(__FILE__) . '/URLExpander.php';
require_once dirname(__FILE__) . '/../../conf/unshorten_it.php';

class Unshorten_It extends URLExpander implements URLExpanderService{

    public function __construct(){
        $this->setApiUser(UnshortenItConf::$API_USER);
        $this->setApiPass(UnshortenItConf::$API_PASS);
        $this->setApiUrl(UnshortenItConf::$API_URL);
        $this->setApiSuccessCode(UnshortenItConf::$API_SUCCESS_CODE);
        $this->setApiResponseFormat(UnshortenItConf::$API_RESPONSE_FORMAT);
    }

    public function expandURL(){
        Utils::log('info', 'about to expand url using unshorten.it');
        $urlToCall = $this->getApiUrl() . 'shortURL=' . $this->getUrl()->getShortUrl()
            . '&responseFormat='.$this->getApiResponseFormat()
            . '&apiKey='.$this->getApiPass();
        Utils::log('info', 'url that will be called for expand: ' . $urlToCall);
        $response = Utils::httpGetSend($urlToCall);
        Utils::log('debug', 'response received: ' );
        Utils::log('debug', $response, true);

        if($response['httpStatusCode'] < 200 || $response['httpStatusCode'] >= 400){
            //error occurred
            Utils::log('error', 'received non-200 HTTP Status Code: '
                . $response['httpStatusCode']);
            $this->getUrl()->setStatus(0);
            $this->getUrl()->setStatusMessage('Error while fetching Un-shortening API');
            return;
        }
        $api_response = json_decode($response['result'], true);
        //if valid, we should have a key with the original shortened url
        if(!isset($api_response['fullurl'])){
            Utils::log('error', 'unable to parse API response');
            $this->getUrl()->setStatus(0);
            $this->getUrl()->setStatusMessage('Error while fetching Un-shortening API');
        }
        else{
            Utils::log('info', 'api response parsed OK...' . $api_response['fullurl']);
            $longUrl = $api_response['fullurl'];
            $this->getUrl()->setLongURL($longUrl);
            $this->getUrl()->setStatus(1);
            $this->getUrl()->setStatusMessage('Success');
            // Utils::log('info', 'long url: ' . $longUrl);
        }
    }
}
