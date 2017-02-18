<?php
       /*//配列を再帰的に置換処理
       function arrayReplace($results = array(), $before = '', $after = '') {
           $results = $xml->xpath('//img/@src');
           foreach ($results as $key => $value) {
               if (is_array($value)) {
                   $value = arrayReplace($value, $before, $after);
                } else {
                    switch (true) {
                        case preg_match("/^(http|https):/i", $before):
                            break;
                        case preg_match("/^\/[^\/].+/", $before):
                            $value = str_replace($before, $after . $before, $value);
                            break;
                    }
                }
                $resultArr->xpath["$key"] = $value;
            }
            return $resultArr;
       }*/
  if (!empty($_POST["url"])) {
      $url = $_POST["url"];
      echo $url;
      // HTMLソース取得
      $html = mb_convert_encoding(@file_get_contents($url),"UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
      $html = preg_replace('/<\s*meta\s+charset\s*=\s*["\'](.+)["\']\s*\/?\s*>/i', '<meta charset="${1}"><meta http-equiv="Content-Type" content="text/html; charset=${1}">', $html);
      // CSSのソースを取得し、外部で取ってくるように置き換える
      @$doc = new DOMDocument();
      @$doc->loadHTML($html);
      $xml = simplexml_import_dom($doc);
      $results = $xml->xpath('//*[@rel="stylesheet" or @media="all" or @media="screen"]');    
      foreach($results as $line) {
          if ($line->xpath('@href') != false) {
              if(preg_match('/^(http|https):/i', $line['href'])) {
                $css_url[] = mb_convert_encoding(file_get_contents($line['href']),"UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
                $css_name[] = $line['href'];
              } else {
                $html = str_replace($line['href'], $url . $line['href'], $html);
                $css_url[] = mb_convert_encoding(@file_get_contents( $url . $line['href']),"UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
                $css_name[] = $line['href'];
             }
          }
      }
      // 画像のソースを取得し、外部で取ってくるように置き換える
      $results = $xml->xpath('//img/@src');
      //$html = arrayReplace($results, "", $url);
      foreach($results as $line) {
         // if ($line->xpath('@src') != false) {
              switch (true) {
                  case preg_match("/^(http|https):/i", $line):
                    break;
                  case preg_match("/^\/[^\/].+/", $line):
                     $html = str_replace($line, $url . $line, $html);
                    break;

              }
              //} else {
               //   $replace = $url . $line['src'];
              // $html = str_replace($line['src'], $url . $line['src'], $html);
          }
              //$css_url[] = $url . $line['href'] . '';
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
    echo htmlspecialchars_decode($html);
    }
?>



<?php if (!empty($css_url)) { ?>
<div style="text-align:center">
<h2>CSSの出力</h2>
<?php foreach($css_url as $css) { ?>
    <div>
        <textarea cols="70" rows="70">
            <?php echo($css); ?>
        </textarea>
    </div>
<?php   }
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
