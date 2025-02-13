<?php

namespace App\Utils\Ftp;

class UploadUtil extends FtpUtil
{
    public function upload($file, $filename, $path): string|bool
    {
        if (!$file)
            return false;

        $conn_id = ftp_connect($this->server);

        if (!$conn_id)
            return false;

        $login = ftp_login($conn_id, $this->user, $this->password);

        if (!$login)
            return false;

        $finalPath = $this->dir . $path;

        if (!ftp_nlist($conn_id, $finalPath)) {
            ftp_mkdir($conn_id, $finalPath);
        }

        ftp_chdir($conn_id, $finalPath);

        $upload = ftp_put($conn_id, $filename, $file->path());

        if ($upload)
            return false;

        ftp_close($conn_id);

        return true;
    }
}
