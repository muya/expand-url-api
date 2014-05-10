<?php

include_once dirname(__FILE__) . '/urlExpanderServices/Urlex_Org.php';
include_once dirname(__FILE__) . '/urlExpanderServices/Unshorten_It.php';
include_once dirname(__FILE__) . '/urlExpanderServices/Untiny_Com.php';
include_once dirname(__FILE__) . '/urlExpanderServices/Unshort_Tk.php';

class InvalidURLExpanderServiceException extends LogicException{}

class URLExpanderServiceProviderFactory{
    public static function createProvider($type){
        if($type == 'urlex.org'){
            return new Urlex_Org();
        }
        else if ($type == 'unshorten.it'){
            return new Unshorten_It();
        }
        else if ($type == 'untiny.com'){
            return new Untiny_Com();
        }
        else if ($type == 'unshort.tk'){
            return new Unshort_Tk();
        }
        else {
             throw new InvalidURLExpanderServiceException("Unknown URL Expander"
                . " service");
        }
    }
}
