<?php

namespace Yueshang\Sound;

class Sound
{
    protected $ip;

    protected $port;

    public function __construct(string $ip, string $port)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function send($cmd)
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $res = socket_connect($socket, $this->ip, $this->port);
        $result = socket_write($socket, $cmd);
        $str = socket_read($socket, 1024);
        socket_close($socket);
    }

    //暂停播放
    public function stop($snlist)
    {
        $cmd['cmd'] = 'FORCESTOP';
        $cmd['snlist'] = $snlist;
        $this->send(json_encode($cmd));
    }

    //音量调整
    public function vol($sn, $vol)
    {
        $cmd['vol'] = $vol;
        $cmd['mode'] = '1003';
        $cmd['sn'] = $sn;
        $this->send(json_encode($cmd));
    }

    //设备状态列表
    public function deviceStatus()
    {
        $cmd['mode'] = '1003';
        $this->send(json_encode($cmd));
    }

    //播放音乐
    public function play($snlist, $fileList)
    {
        $cmd['cmd'] = 'PLAYOFF';
        $cmd['filelist'] = $fileList;
        $cmd['sn'] = $snlist;
        $this->send(json_encode($cmd));
    }
}
