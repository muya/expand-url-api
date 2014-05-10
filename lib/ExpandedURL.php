<?php

include_once dirname(__FILE__) . '/Utils.php';

class ExpandedURL{
    private $shortURL;
    private $longURL;
    private $shortenerService;
    private $status;
    private $statusMessage;
    private $isValidRequest;
    private $requestKey;

    public function __construct(){
        $this->isValidRequest = true;
        $this->statusMessage = '';
    }

    public function isValidRequest(){
        return $this->isValidRequest;
    }

    public function setIsValidRequest($isValid){
        $this->isValidRequest = $isValid;
    }

    public function setShortURL($shortURL){
        $this->shortURL = $shortURL;
    }

    public function getShortURL(){
        return $this->shortURL;
    }

    public function setLongURL($longURL){
        $this->longURL = $longURL;
    }

    public function getLongURL(){
        return $this->longURL;
    }

    public function setShortenerService($shortenerService){
        $this->shortenerService = $shortenerService;
    }

    public function getShortenerService(){
        return $this->shortenerService;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatusMessage($statusMessage){
        $this->statusMessage = $statusMessage;
    }

    public function getStatusMessage(){
        return $this->statusMessage;
    }

    public function validate($request){
        if(!isset($_POST) || empty($_POST)){
            Utils::log('error', 'api invoked with empty POST request');
            $this->setStatus(0);
            $this->setStatusMessage('Invalid invocation method. POST expected');
            $this->setIsValidRequest(false);
            return;
        }
        if(!isset($request['shortUrl']) || ($request['shortUrl'] == '')){
            Utils::log('error', 'api invoked without shortUrl parameter');
            $this->setStatus(0);
            $this->setStatusMessage("'shortUrl' parameter not defined.");
            $this->setIsValidRequest(false);
            return;
        }
        $this->setShortURL($request['shortUrl']);
    }
}
