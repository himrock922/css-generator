<?php
  if (!empty($_POST["url"])) {
      $url = $_POST["url"];
      // HTMLソース取得
      $html = mb_convert_encoding(file_get_contents($url),"UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
      $html = preg_replace('/<\s*meta\s+charset\s*=\s*["\'](.+)["\']\s*\/?\s*>/i', '<meta charset="${1}"><meta http-equiv="Content-Type" content="text/html; charset=${1}">', $html);
      // CSSのソースを取得し、外部で取ってくるように置き換える
      @$doc = new DOMDocument();
      @$doc->loadHTML($html);
      $xml = simplexml_import_dom($doc);
      $results = $xml->xpath('//*[@rel="stylesheet" or @media="all" or @media="screen"]');    
      foreach($results as $line) {
          if ($line->xpath('@href') != false) {
              $html = str_replace($line['href'], $url . $line['href'], $html);
              //$css_url[] = $url . $line['href'] . '';
          }
      }
      // 画像のソースを取得し、外部で取ってくるように置き換える
      $results = $xml->xpath('//img');
      foreach($results as $line) {
          if ($line->xpath('@src') != false) {
              $html = str_replace($line['src'], $url . $line['src'], $html);
              //$css_url[] = $url . $line['href'] . '';
          }
      }

  }
?>
<!DOCTYPE html>
<html lang="ja"> 
<head>
<meta charset="UTF-8">
<title>CSSジェネレーター</title>
</head>
<body>
<div>
<?php if (!empty($html)) {
   // foreach($css_url as $line) {
    //    echo '<link rel="stylesheet" type="text/css" href=' . $line. '>';
  //  }
    echo htmlspecialchars_decode($html);
    
    }
?>
</div>
<div style="text-align:center">
    <h1> CSSジェネレーター</h1>
    <form method="post">
        <p><label>URL：<input type="text" name="url" size="40"></label> <input type="submit" value="送信"></p>
    </form>
</div>
</body>
</html>
