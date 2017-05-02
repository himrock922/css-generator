<?php
class Cache
{
    // キャッシュ保存
    public function put($key, $value, $path)
    {
        $filePath = $this->getFilePath($key, $path);
        file_put_contents($filePath, serialize($value));
    }

    public function put_import($key, $value, $path)
    {
        $filePath = $this->getImportPath($key, $path);
        file_put_contents($filePath, $value);
    }
    // キャッシュ取得
    public function get($key, $path)
    {
        $filePath = $this->getFilePath($key, $path);
        if (file_exists($filePath))
        {
            return unserialize(file_get_contents($filePath));
        }
        else
        {
            return false;
        }
    }

    public function get_import($key, $path)
    {
        $filePath = $this->getImportPath($key, $path);
        if (file_exists($filePath))
        {
            return file_get_contents($filePath);
        }
        else
        {
            return false;
        }
    }

    // ファイル削除
    public function delete($key, $path)
    {
        $filePath = $this->getFilePath($key, $path);
        if (file_exists($filePath))
        {
            return unlink($filePath);
        }
        else
        {
            return false;
        }
    }

    // ファイル削除
    public function delete_import($key, $path)
    {
        $filePath = $this->getImportPath($key, $path);
        if (file_exists($filePath))
        {
            return unlink($filePath);
        }
        else
        {
            return false;
        }
    }

    // キャッシュファイルパス取得
    private function getFilePath($key, $path)
    {
        return CACHE_DIR . $path . '/' . sha1(serialize($key));
    }
    private function getImportPath($key, $path)
    {
         return CACHE_DIR . $path . '/' . sha1($key);
    }
    public function getCacheFilePath($key, $path)
    {
         return $path . '/' . sha1(serialize($key));
    }
    public function getCacheImportPath($key, $path)
    {
        return $path . '/' . sha1($key);
    }
    public function getCacheImportFilePath($key, $path)
    {
        return sha1(serialize($key));
    }
}