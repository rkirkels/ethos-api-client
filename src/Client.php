<?php

namespace RutgerKirkels\Ethos_Api_Client;


class Client
{
    protected $panelId;

    public function __construct($panelId = null) {
        if (!is_null($panelId)) {
            $this->panelId = $panelId;
        }
    }

    public function setPanelId(string $panelId) {
        $this->panelId = $panelId;
    }
}