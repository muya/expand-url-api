<?php

include_once dirname(__FILE__) . '/URLExpanderServiceInterface.php';
include_once dirname(__FILE__) . '/URLExpander.php';
include_once dirname(__FILE__) . '/../../conf/urlex_org.php';

class Urlex_Org extends URLExpander implements URLExpanderService {


    public function __construct(){
        $this->setApiUser(UrlExConf::$API_USER);
        $this->setApiPass(UrlExConf::$API_PASS);
        $this->setApiUrl(UrlExConf::$API_URL);
        $this->setApiSuccessCode(UrlExConf::$API_SUCCESS_CODE);
        $this->setApiResponseFormat(UrlExConf::$API_RESPONSE_FORMAT);
    }

    public function expandURL(){
        Utils::log('info', 'about to expand url using urlex.org');
        $urlToCall = $this->getApiUrl()  . $this->getApiResponseFormat() . '/' .$this->getUrl()->getShortUrl();
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
        if(!isset($api_response[$this->getUrl()->getShortUrl()])){
            Utils::log('error', 'unable to parse API response');
            $this->getUrl()->setStatus(0);
            $this->getUrl()->setStatusMessage('Error while fetching Un-shortening API');
        }
        else{
            Utils::log('info', 'api response parsed OK...' . $api_response[$this->getUrl()->getShortUrl()]);
            $longUrl = str_replace('/\/\//', '/', $api_response[$this->getUrl()->getShortUrl()]).'';
            Utils::log('info', 'long url: ' . $longUrl);
            $this->getUrl()->setLongURL($longUrl);
            $this->getUrl()->setStatus(1);
            $this->getUrl()->setStatusMessage('Success');
        }

    }
}
