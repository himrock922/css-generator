<?php
class Cache
{
    // キャッシュ保存
    public function put($key, $value)
    {
        $filePath = $this->getFilePath($key);
        file_put_contents($filePath, serialize($value));
    }

    public function put_import($key, $value)
    {
        $filePath = $this->getImportPath($key);
        file_put_contents($filePath, $value);
    }
    // キャッシュ取得
    public function get($key)
    {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath))
        {
            return unserialize(file_get_contents($filePath));
        }
        else
        {
            return false;
        }
    }

    public function get_import($key)
    {
        $filePath = $this->getImportPath($key);
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
    public function delete($key)
    {
        $filePath = $this->getFilePath($key);
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
    public function delete_import($key)
    {
        $filePath = $this->getImportPath($key);
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
    private function getFilePath($key)
    {
        return CACHE_DIR . sha1(serialize($key));
    }
    private function getImportPath($key)
    {
         return CACHE_DIR . sha1($key);
    }
    public function getCacheFilePath($key)
    {
         return 'cache/' . sha1(serialize($key));
    }
    public function getCacheImportPath($key)
    {
        return 'cache/' . sha1($key);
    }
    public function getCacheImportFilePath($key)
    {
        return sha1(serialize($key));
    }
}