<?php
require "simple_html_dom.php";
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/Cache.php';

function img_array_flatten($array) {
    foreach($array->find('img') as $values) {
        if(is_array($values)) {
            img_array_flatten($values);
        } else {
            $a[] = $values;
        }
    }
    return $a;
}

function css_array_flatten($array) {
    foreach($array->find('link[rel="stylesheet"],link[media="all"],link[media="screen"]') as $values) {
        if(is_array($values)) {
            css_array_flatten($values);
        } else {
            $a[] = $values;
        }
    }
    return $a;
}

function input_array_flatten($array) {
    foreach($array->find('input') as $values) {
        if(is_array($values)) {
            input_array_flatten($values);
        } else {
            $a[] = $values;
        }
    }
    return $a;
}

//画像配列を再帰的に置換処理
function img_array_replace($search, $array) {
    foreach ($array->find('img') as $value) {
        if (is_array($value)) {
            $value = img_array_replace($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->src)) {
            } else if (preg_match("/^\/[^\/].+/", $value->src)) {
                $value = str_replace($value->src, $search . $value->src, $value);
            } else if (preg_match("/^\.\/(.+)/", $value->src)) {
                $value = str_replace($value->src, $search . ltrim($value->src, "."), $value);
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->src)) {
                $value = str_replace($value->src, $search . "/" . $value->src, $value);
            }
        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

//画像配列を再帰的に置換処理
function input_array_replace($search, $array) {
    foreach ($array->find('input') as $value) {
        if (is_array($value)) {
            $value = input_array_replace($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->src)) {
            } else if (preg_match("/^\/[^\/].+/", $value->src)) {
                $value = str_replace($value->src, $search . $value->src, $value);
            } else if (preg_match("/^\.\/(.+)/", $value->src)) {
                $value = str_replace($value->src, $search . ltrim($value->src, "."), $value);
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->src)) {
                $value = str_replace($value->src, $search . "/" . $value->src, $value);
            }
        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

//CSS配列を再帰的に置換処理
function css_array_replace($search, $array) {
    foreach ($array->find('link[rel="stylesheet"],link[media="all"],link[media="screen"]') as $value) {
        if (is_array($value)) {
            $value = css_array_replace($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->href)) {
            } else if (preg_match("/^\/[^\/].+/", $value->href)) {
                $value = str_replace($value->href, $search . $value->href, $value);
            } else if (preg_match("/^\.\/(.+)/", $value->href)) {
                $value = str_replace($value->href, $search . ltrim($value->href, "."), $value);
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->href)) {
                $value = str_replace($value->href, $search . "/" . $value->href, $value);
            }
        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

//CSS配列を再帰的に置換処理
function css_array_get($search, $array) {
    foreach ($array->find('link[rel="stylesheet"],link[media="all"],link[media="screen"]') as $value) {
        if (is_array($value)) {
            $value = css_array_get($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($value->href), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            } else if (preg_match("/^\/[^\/].+/", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($search . $value->href), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            } else if (preg_match("/^\.\/(.+)/", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($search . ltrim($value->href, ".")), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($search . "/" . $value->href), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            }
        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

  $cache = new Cache();
  $html = $cache->get('html');
  $css_url = array();
  $iterator = new GlobIterator(dirname(__FILE__) . '/cache/*');
  for($count = 1; $count < $iterator->count(); $count++) {
      $css_url[] = $cache->get("css${count}");
  }
  if (!empty($_POST["url"])) {
      $url = $_POST["url"];
      // HTMLソース取得
      $html = file_get_html($url);
      // CSSのソースを取得し、外部で取ってくるように置き換える
      $css_from = css_array_flatten($html);
      $css_to = css_array_replace($url, $html);
      $css_replace = array_combine($css_from, $css_to); 
      // 画像のソースを取得し、外部で取ってくるように置き換える
      $img_from = img_array_flatten($html);
      $img_to = img_array_replace($url, $html);
      $img_replace = array_combine($img_from, $img_to);
      // CSSファイルを取得
      $css_url = css_array_get($url, $html);
      // input画像のソースを取得し、外部で取ってくるように置き換える
      $input_from = input_array_flatten($html);
      $input_to = input_array_replace($url, $html);
      $input_replace = array_combine($input_from, $input_to);

      $html = strtr($html, $css_replace);
      $html = strtr($html, $img_replace);
      $html = strtr($html, $input_replace);
    // サイト情報を保存する
    if (!empty($_POST["save"])) {
        $cache->put('html', htmlspecialchars_decode($html));
        $count = 1;
        foreach($css_url as $css) {
            $cache->put("css${count}", $css);
            $count++;
        }
    }
    /******************/
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
        <p><label>URL：<input type="url" name="url" size="40"></label> <input type="submit" value="送信"></p>
        <p><label>サイト情報を保存する <input type="checkbox" name="save" value="サイト情報を保存する"></label></p>
    </form>
</div>
</body>
</html>
