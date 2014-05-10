<?php

include_once dirname(__FILE__) . '/log4php/Logger.php';

Logger::configure(dirname(__FILE__) . '/../conf/log4php/config.xml');

class Log{
    private $log;

    public function __construct(){
        $this->log = Logger::getLogger(__CLASS__);
    }

    public function info($msg){
        $this->log->info($msg);
    }

    public function warn($msg){
        $this->log->warn($msg);
    }

    public function error($msg){
        $this->log->error($msg);
    }

    public function debug($msg){
        $this->log->debug($msg);
    }
}
