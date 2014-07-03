<?php

//get data from post
//authenticate
//validate
//choose url expander service
//expand url
//return expanded url

include_once dirname(__FILE__) . '/lib/ExpandedURL.php';
include_once dirname(__FILE__) . '/lib/ExpanderServiceProvider.php';

header('Content-type: application/json');

Utils::log('info', 'invoked OK');

$expandedURL = new ExpandedURL();
$expandedURL->validate($_POST);
if(!$expandedURL->isValidRequest()){
    Utils::log('info', 'validation failed. details: ' . $expandedURL->getStatusMessage);
    echo json_encode(Utils::formatResponse($expandedURL->getStatus, $expandedURL->getStatusMessage()));
    exit();
}

Utils::log('debug', 'api invoked with data: ' . json_encode($_POST));

//request was valid. choose url expander, etc
$urlExpanderService = 'unshort.tk';
$provider = URLExpanderServiceProviderFactory::createProvider($urlExpanderService);
$provider->setUrl($expandedURL);
$provider->expandURL();
sleep(5);
echo json_encode(Utils::formatResponse($expandedURL->getStatus(),
    $expandedURL->getStatusMessage(), array('expandedUrl' => $expandedURL->getLongURL())));
exit();
