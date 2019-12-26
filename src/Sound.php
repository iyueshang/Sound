<?php

namespace Yueshang\Sound;

use Yueshang\Sound\Exception\SocketException;
use Yueshang\Sound\Exceptions\Exception;

class Sound
{
    protected $ip;

    protected $port;

    public function __construct(string $ip, string $port)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    //发送数据
    protected function send($cmd)
    {
        $socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        $res = socket_connect($socket,$this->ip,$this->port);
        $result = socket_write($socket,$cmd);

        try {
            $str = socket_read($socket,1024);
        }catch (Exception $e) {
            throw new SocketException($e->getMessage(), $e->getCode(), $e);
        }
        socket_close($socket);
        return $str;
    }

    //暂停播放
    public function stop($snlist)
    {
        $cmd['cmd'] = 'FORCESTOP';
        $cmd['snlist'] = $snlist;
        return $this->send(json_encode($cmd));
    }

    //音量调整
    public function vol($sn, $vol)
    {
        $cmd['vol'] = $vol;
        $cmd['mode'] = '1003';
        $cmd['sn'] = $sn;
        return $this->send(json_encode($cmd));
    }

    //设备状态列表
    public function deviceStatus()
    {
        $cmd['mode'] = "1003";
        return $this->send(json_encode($cmd));
    }

    //播放音乐
    public function play($snlist, $fileList)
    {
        $cmd['cmd'] = 'PLAYOFF';
        $cmd['filelist'] = $fileList;
        $cmd['sn'] = $snlist;
        return $this->send(json_encode($cmd));
    }

    //重启音柱
    public function reboot($sn)
    {
        $cmd['mode'] = "3001";
        $cmd['sn'] = $sn;
        return $this->send(json_encode($cmd));
    }

    //发送文本信息进行离线语音合成
    public function sendTextVoice($ttstring, $snlist)
    {
        $cmd['cmd'] = "PLAYTTS";
        $cmd['snlist'] = $snlist;
        $cmd['ttstring'] = base64_encode($ttstring);
        return $this->send(json_encode($cmd));
    }


    //清空当前队列， 添加新的队列到播放列表
    public function  clearQueueAnewAdd($snlist, $fileList)
    {
        $cmd['cmd'] = "PLAYOFF_START";
        $cmd['snlist'] = $snlist;
        $cmd['filelist'] = $fileList;
        return $this->send(json_encode($cmd));
    }
}
