<?php

class MusicList
{
    public function getList(string $name):array
    {
        switch ($name) {
            case 'caiqin':
                return ['张三的歌', '读你', '渡口'];
                break;
            case 'zongcilang':
                return ['永远同在', '故乡的原风景'];
                break;
            default:
                return ['无间道', '女人花'];
                break;
        }
    }
}

$server = new Yar_Server(new MusicList());
$server->handle();




