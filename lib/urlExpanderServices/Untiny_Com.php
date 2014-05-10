<?php

include_once dirname(__FILE__) . '/URLExpanderServiceInterface.php';
include_once dirname(__FILE__) . '/URLExpander.php';
require_once dirname(__FILE__) . '/../../conf/untiny_com.php';

class Untiny_Com extends URLExpander implements URLExpanderService{

    public function __construct(){
        $this->setApiUser(UntinyComConf::$API_USER);
        $this->setApiPass(UntinyComConf::$API_PASS);
        $this->setApiUrl(UntinyComConf::$API_URL);
        $this->setApiSuccessCode(UntinyComConf::$API_SUCCESS_CODE);
        $this->setApiResponseFormat(UntinyComConf::$API_RESPONSE_FORMAT);
    }

    public function expandURL(){
        Utils::log('info', 'about to expand url using untiny.com');
        $urlToCall = $this->getApiUrl() . 'url=' . $this->getUrl()->getShortUrl()
            . '&format='.$this->getApiResponseFormat();
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
        if(!isset($api_response['org_url'])){
            Utils::log('error', 'unable to parse API response');
        }
        else{
            Utils::log('info', 'api response parsed OK...' . $api_response['org_url']);
            $longUrl = $api_response['org_url'];
            $this->getUrl()->setLongURL($longUrl);
            $this->getUrl()->setStatus(1);
            $this->getUrl()->setStatusMessage('Success');
        }
    }
}
