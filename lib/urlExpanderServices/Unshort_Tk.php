<?php

include_once dirname(__FILE__) . '/URLExpanderServiceInterface.php';
include_once dirname(__FILE__) . '/URLExpander.php';
require_once dirname(__FILE__) . '/../../conf/unshort_tk.php';

class Unshort_Tk extends URLExpander implements URLExpanderService{

    public function __construct(){
        $this->setApiUser(UnshortTkConf::$API_USER);
        $this->setApiPass(UnshortTkConf::$API_PASS);
        $this->setApiUrl(UnshortTkConf::$API_URL);
        $this->setApiSuccessCode(UnshortTkConf::$API_SUCCESS_CODE);
        $this->setApiResponseFormat(UnshortTkConf::$API_RESPONSE_FORMAT);
    }

    public function expandURL(){
        Utils::log('info', 'about to expand url using unshort.tk');
        $urlToCall = $this->getApiUrl() . 'u=' . $this->getUrl()->getShortUrl();
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
        if(!isset($api_response[$this->getUrl()->getShortUrl()])){
            Utils::log('error', 'unable to parse API response');
            $this->getUrl()->setStatus(0);
            $this->getUrl()->setStatusMessage('Error while fetching Un-shortening API');
        }
        else{
            Utils::log('info', 'api response parsed OK...' . $api_response[$this->getUrl()->getShortUrl()]);
            $longUrl = $api_response[$this->getUrl()->getShortUrl()];
            Utils::log('info', 'long url: ' . $longUrl);
            $this->getUrl()->setLongURL($longUrl);
            $this->getUrl()->setStatus(1);
            $this->getUrl()->setStatusMessage('Success');
        }
    }
}
