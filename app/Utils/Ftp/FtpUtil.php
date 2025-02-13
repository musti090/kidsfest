<?php

namespace App\Utils\Ftp;

class FtpUtil
{
    protected string $server;
    protected string $user;
    protected string $password;
    protected string $dir;

    public function __construct()
    {
        $this->server = env('FTP_HOST');
        $this->user = env('FTP_USERNAME');
        $this->password = env('FTP_PASSWORD');
        $this->dir = env('FTP_ROOT');
    }
}
