<?php

namespace RutgerKirkels\Ethos_Api_Client;

define('ETHOS_HOST','ethosdistro.com');

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

    public function getStats() {
        $miners = [];
        $client = new \GuzzleHttp\Client();
        $url = 'http://' .$this->panelId . '.' . ETHOS_HOST;
        $res = $client->request('get', $url, [
            'query' => ['json' => 'yes']
        ]);
        $data = json_decode((string) $res->getBody());

        foreach ($data->rigs as $ethosId => $minerData) {
            $miner = new Miner();
            $miner->setId($ethosId);
            $miner->setTotalHashrate($minerData->hash);
            $miner->setCondition($minerData->condition);
            $miner->setDriveName($minerData->drive_name);
            $miner->setLanChip($minerData->lan_chip);
            $miner->setMotherboard($minerData->mobo);
            $miner->setTotalRam($minerData->ram);
            $miner->setVersion($minerData->version);
            $bios = explode(' ', $minerData->bioses);
            $hash = explode(' ', $minerData->miner_hashes);
            $temps = explode(' ', $minerData->temp);
            $watts = explode(' ', $minerData->watts);
            $fans = explode(' ', $minerData->fanrpm);
            $core = explode(' ', $minerData->core);
            $mem = explode(' ', $minerData->mem);
            $powertune = explode(' ', $minerData->powertune);
            $vramsize = explode(' ', $minerData->vramsize);
            for ($i = 0; $i < intval($minerData->gpus); $i++) {
                $gpu = new Gpu();
                $gpu->setBios($bios[$i]);
                $gpu->setHash(floatval($hash[$i]));
                $gpu->setFanSpeed(intval($fans[$i]));
                $gpu->setPower(floatval($watts[$i]));
                $gpu->setTemperature(intval($temps[$i]));
                $gpu->setPowerTune(intval($powertune[$i]));
                $gpu->setVramSize(intval($vramsize[$i]));
                $gpu->setCoreSpeed(intval($core[$i]));
                $gpu->setMemorySpeed(intval($mem[$i]));
                $miner->addGpu($gpu);
            }

            $miners[$ethosId] = $miner;
        }
        return $miners;
    }
}