<?php
  if (!empty($_POST["url"])) {
      $url = $_POST["url"];
      // HTMLソース取得
      $html = mb_convert_encoding(file_get_contents($url),"UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
      // CSS用にソースを配列で取得
      $domDocument = new DOMDocument();
      @$domDocument->loadHTML($html);
      $xmlObject = simplexml_import_dom(@$domDocument);
      $results = $xmlObject->xpath('//*[@rel="stylesheet"]');    
      foreach($results as $line) {
          echo $line;
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
<div style="text-align:center">
    <h1> CSSジェネレーター</h1>
    <form method="post">
        <p><label>URL：<input type="text" name="url" size="40"></label> <input type="submit" value="送信"></p>
    </form>
</div>
<?php if (!empty($html)) {
    echo htmlspecialchars_decode($html);
    }
?>
</body>
</html>
