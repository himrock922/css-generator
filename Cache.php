<?php
class Cache
{
    // キャッシュ保存
    public function put($key, $value)
    {
        $filePath = $this->getFilePath($key);
        file_put_contents($filePath, serialize($value));
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

    // CSSキャッシュ取得
    public function get_css($key)
    {
      $filePath = $this->getFilePath($key);
      $css_url = array();
      if (file_exists($filePath))
      {
        $html = unserialize(file_get_contents($filePath));
        $doc = new DOMDocument();
        $doc->loadHTML(@$html);
        $xml = simplexml_import_dom(@$doc);
        $results = $xml->xpath('//*[@rel="stylesheet" or @media="all" or @media="screen"]');
        foreach($results as $line) {
            if ($line->xpath('@href') != false) {
                if(preg_match('/^(http|https):/i', $line['href'])) {
                    $css_url[] = mb_convert_encoding(file_get_contents($line['href']),"UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
                } else {
                    $css_url[] = mb_convert_encoding(file_get_contents($line['href']),"UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
                }
            }
        }
        return $css_url;
      } else {
        return false;
      }
    }
    // キャッシュファイルパス取得
    private function getFilePath($key)
    {
        return CACHE_DIR . sha1(serialize($key));
    }
}