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
    
    
        public function uploadBase64($base64String, $filename, $path): string|bool
    {
        try {
            $conn_id = ftp_connect($this->server);

            $login = ftp_login($conn_id, $this->user, $this->password);

            $finalPath = $this->dir . $path;

            if (!ftp_nlist($conn_id, $finalPath)) {
                ftp_mkdir($conn_id, $finalPath);
            }

            ftp_chdir($conn_id, $finalPath);
            $handle = fopen('data://image/jpeg;base64,' . $base64String, 'r');

            $upload = ftp_fput($conn_id, $filename, $handle, FTP_BINARY);

            ftp_close($conn_id);

            return true;
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

}
