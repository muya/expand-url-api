<?php

include_once dirname(__FILE__) . '/Log.php';


class Utils{
    /**
    * function to log message to file
    */
    public static function log($level, $msg = null, $encode = false){
        $log = new Log();
        $level = strtolower($level);
        $msg = strtoupper($level) . ' | ' . (($encode == true) ? json_encode($msg) : $msg);
        switch ($level) {
            case 'error':
                $log->error($msg);
                break;
            case 'info':
                $log->info($msg);
                break;
            case 'warn':
                $log->warn($msg);
                break;
            default:
                //default should be debug
                $log->debug($msg);
                break;
        }
    }

    public static function formatResponse($status = null, $message = null, $data = null){
        return array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        );
    }

    /**
     * Sends the request via GET.
     *
     * @return mixed the retrieved status and/or response
     */
    public static function httpGetSend($send) {
        $ch = curl_init($send);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response['result'] = $result;
        $response['httpStatusCode'] = $httpStatus;
        curl_close($ch);
        return $response;
    }

    /**
    * function to send POST request using curl
    */
    public static function sendRequestToServer($url, $request, $isSecure = false, $curlOptions = array()) {
        //extract any options that may have been sent in the curl options
        if (!is_array($curlOptions)) {
            $curlOptions = array();
        }
        $headers = ((isset($curlOptions['headers'])) && (is_array($curlOptions['headers']))) ? $curlOptions['headers'] : array();
        $connectTimeout = (isset($curlOptions['connectTimeout'])) ? (int) $curlOptions['connectTimeout'] : DEFAULT_CONNECT_TIMEOUT;
        $readTimeout = (isset($curlOptions['readTimeout'])) ? (int) $curlOptions['readTimeout'] : DEFAULT_READ_TIMEOUT;
        $certificatePath = (isset($curlOptions['certificatePath'])) ? (int) $curlOptions['certificatePath'] : DEFAULT_CERT_PATH;

        $ch = curl_init();
        //SSL Options
        if ($isSecure) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CAINFO, $certificatePath);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, $readTimeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        // curl_setopt($ch, CURLOPT_VERBOSE, true);
        // $curlLogFile = LOG_DIRECTORY . 'INFO.log';
        // $verbose = fopen($curlLogFile, 'ab');
        // curl_setopt($ch, CURLOPT_STDERR, $verbose);

        $response = curl_exec($ch);
        $HTTPStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = array(
            'result' => $response,
            'httpStatusCode' => $HTTPStatusCode
        );
        Utils::log('debug', 'invoked url: ' . $url);
        Utils::log('debug', 'request data: ' . $request);
        Utils::log('debug', 'response data: ' . $responseData, true);
        return $responseData;
    }


}
