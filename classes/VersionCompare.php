<?php


class VersionCompare
{
    var $priorVersion = '1.0.17+60';
    var $currentVersion = '';
    public function __construct( $version )
    {
        $this->currentVersion = $version;
    }

    public function isUTCVersion()
    {
        return ( $this->currentVersion >= $this->priorVersion );

    }

    public function isBerlinVersion()
    {
        return !$this->isUTCVersion();
    }

}