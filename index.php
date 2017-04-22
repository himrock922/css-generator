<?php
require "simple_html_dom.php";
require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/Cache.php';
require_once dirname(__FILE__) . '/CssSelector.php';
require_once 'vendor/autoload.php';

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
    $host =  parse_url($search);
    foreach ($array->find('img') as $value) {
        if (is_array($value)) {
            $value = img_array_replace($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->src)) {
            } else if (preg_match("/^\/[^\/].+/", $value->src)) {
                $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . $value->src, $value);
            } else if (preg_match("/^\.\/(.+)/", $value->src)) {
                $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . $host["path"] . ltrim($value->src, "."), $value);
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->src)) {
                $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . $host["path"] . "/" . $value->src, $value);
            } else if (preg_match("/^\.\.\/.+/", $value->src)) {
               $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . ltrim($value->src, ".."), $value);
            }
        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

//画像配列を再帰的に置換処理
function input_array_replace($search, $array) {
    $host =  parse_url($search);
    foreach ($array->find('input') as $value) {
        if (is_array($value)) {
            $value = input_array_replace($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->src)) {
            } else if (preg_match("/^\/[^\/].+/", $value->src)) {
                $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . $value->src, $value);
            } else if (preg_match("/^\.\/(.+)/", $value->src)) {
                $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . $host["path"] . ltrim($value->src, "."), $value);
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->src)) {
                $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . $host["path"] . "/" . $value->src, $value);
            } else if (preg_match("/^\.\.\/.+/", $value->src)) {
               $value = str_replace($value->src, $host["scheme"] . "://" . $host["host"] . ltrim($value->src, ".."), $value);
            }
        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

//CSS配列を再帰的に置換処理
function css_array_replace($search, $array) {
    $host =  parse_url($search);
    foreach ($array->find('link[rel="stylesheet"],link[media="all"],link[media="screen"]') as $value) {
        if (is_array($value)) {
            $value = css_array_replace($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->href)) {
            } else if (preg_match("/^\/[^\/].+/", $value->href)) {
                $value = str_replace($value->href, $host["scheme"] . "://" . $host["host"] . $value->href, $value);
            } else if (preg_match("/^\.\/(.+)/", $value->href)) {
                $value = str_replace($value->href, $host["scheme"] . "://" . $host["host"] . $host["path"] . ltrim($value->href, "."), $value);
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->href)) {
                $value = str_replace($value->href, $host["scheme"] . "://" . $host["host"] . $host["path"] . "/" . $value->href, $value);
            } else if (preg_match("/^\.\.\/.+/", $value->href)) {
                $value = str_replace($value->href, $host["scheme"] . "://" . $host["host"] . ltrim($value->href, ".."), $value);
            }
        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

//CSS配列を再帰的に置換処理
function css_array_get($search, $array) {
    $host =  parse_url($search);
    foreach ($array->find('link[rel="stylesheet"],link[media="all"],link[media="screen"]') as $value) {
        if (is_array($value)) {
            $value = css_array_get($search, $array);
        } else {
            if (preg_match("/^(http|https):/i", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($value->href), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            } else if (preg_match("/^\/[^\/].+/", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . $value->href), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            } else if (preg_match("/^\.\/(.+)/", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . $host["path"]  . ltrim($value->href, ".")), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            } else if (preg_match("/^([^\.\/]+)(.*)/", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . $host["path"]  . "/" . $value->href), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            } else if (preg_match("/^\.\.\/.+/", $value->href)) {
                $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . ltrim($value->href, "..")), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
            }

        }
        $resultAtr[] = $value;
    }
    return $resultAtr;
}

function css_import_get($search, $import) {
    $host =  parse_url($search);
    if (preg_match("/^(http|https):/i", $import)) {
        $value = mb_convert_encoding(@file_get_contents($import), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
    } else if (preg_match("/^\/[^\/].+/", $import)) {
        $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . $import), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
    } else if (preg_match("/^\.\/(.+)/", $import)) {
        $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . $host["path"]  . ltrim($import, ".")), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
    } else if (preg_match("/^([^\.\/]+)(.*)/", $import)) {
        $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . $host["path"]  . "/" . $import), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
    } else if (preg_match("/^\.\.\/.+/", $import)) {
        $value = mb_convert_encoding(@file_get_contents($host["scheme"] . "://" . $host["host"] . ltrim($import, "..")), "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS");
    }
    return $value;
}
/**
 * Extract URLs from CSS text.
 */
function extract_css_urls( $text )
{
    $urls = array( );
 
    $url_pattern     = '(([^\\\\\'", \(\)]*(\\\\.)?)+)';
    $urlfunc_pattern = 'url\(\s*[\'"]?' . $url_pattern . '[\'"]?\s*\)';
    $pattern         = '/(' .
         '(@import\s*[\'"]' . $url_pattern     . '[\'"])' .
        '|(@import\s*'      . $urlfunc_pattern . ')'      .
        '|('                . $urlfunc_pattern . ')'      .  ')/iu';
    if ( !preg_match_all( $pattern, $text, $matches ) )
        return $urls;
 
    // @import '...'
    // @import "..."
    foreach ( $matches[3] as $match )
        if ( !empty($match) )
            $urls['import'][] = 
                preg_replace( '/\\\\(.)/u', '\\1', $match );
 
    // @import url(...)
    // @import url('...')
    // @import url("...")
    foreach ( $matches[7] as $match )
        if ( !empty($match) )
            $urls['import'][] = 
                preg_replace( '/\\\\(.)/u', '\\1', $match );
 
    // url(...)
    // url('...')
    // url("...")
    foreach ( $matches[11] as $match )
        if ( !empty($match) )
            $urls['property'][] = 
                preg_replace( '/\\\\(.)/u', '\\1', $match );
 
    return $urls;
}

  $cache = new Cache();
  $html = str_get_html($cache->get('html'), true, true, DEFAULT_TARGET_CHARSET, false, false, false);
  if(!empty($html)) {
    $css_url = array();
    $iterator = new GlobIterator(dirname(__FILE__) . '/cache/*');
    for($count = 1; $count < $iterator->count(); $count++) {
        $css_url[] = $cache->get("css${count}");
        $css_path = $cache->getCacheFilePath("css${count}");
        $cache_css[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"${css_path}\">";
    }
    $css_from = css_array_flatten($html);
    $css_replace = array_combine($css_from, $cache_css); 
    $html = strtr($html, $css_replace);
  }
  if (!empty($_POST["url"])) {
      if (!empty($_POST["save"])) {
          $html = $cache->delete('html');
          $iterator = new GlobIterator(dirname(__FILE__) . '/cache/*');
          for($count = 1; $count < $iterator->count(); $count++) {
              $cache->delete("css${count}");
            }
      }
      $url = $_POST["url"];
      // HTMLソース取得
      $html = file_get_html($url, false, null, -1, -1, true, true, DEFAULT_TARGET_CHARSET, false);
      // CSSのソースを取得し、外部で取ってくるように置き換える
      $css_from = css_array_flatten($html);
      $css_to = css_array_replace($url, $html);
      $css_replace = array_combine($css_from, $css_to); 
      // 画像のソースを取得し、外部で取ってくるように置き換える
      $img_from = img_array_flatten($html);
      $img_to = img_array_replace($url, $html);
      $img_replace = array_combine($img_from, $img_to);
      // CSSファイルを取得
      $from_url = css_array_get($url, $html);
      // input画像のソースを取得し、外部で取ってくるように置き換える
      $input_from = input_array_flatten($html);
      $input_to = input_array_replace($url, $html);
      $input_replace = array_combine($input_from, $input_to);

      foreach($from_url as $css) {
          $matchs = extract_css_urls($css);
          if(empty($matchs['import'])) {
              continue;
          }
          foreach($matchs['import'] as $import) {
              $import_url[] = css_import_get($url, $import);
          }
      }
      $css_url = array_merge($from_url, $import_url);
      $html = strtr($html, $css_replace);
      $html = strtr($html, $img_replace);
      if($input_replace != false) {
        $html = strtr($html, $input_replace);
      }
    // サイト情報を保存する
    if (!empty($_POST["save"])) {
        $cache->put('html', htmlspecialchars_decode($html));
        $count = 1;
        foreach($css_url as $css) {
            $cache->put("css${count}", $css);
            $count++;
        }
    }
    $css_url = array();
    $iterator = new GlobIterator(dirname(__FILE__) . '/cache/*');
    for($count = 1; $count < $iterator->count(); $count++) {
        if(!empty($_POST["css${count}"])) {
            echo $_POST["css${count}"];
        }
        $css_url[] = $cache->get("css${count}");
    }
    /******************/
  }
?>
<?php if (!empty($html)) {
    echo htmlspecialchars_decode($html);
    }
?>
<!DOCTYPE html>
<html lang="ja"> 
  <head>
    <meta charset="UTF-8">
    <!-- Minified - Latest version -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
    <script src="color.js"></script>
    <title>CSSジェネレーター</title>
  </head>
  <body style="text-align:center;">
    <div class="container">
      <div class="row">
        <h1> CSSジェネレーター</h1>
          <form method="post">
            <p><label>URL：<input type="url" name="url" size="40"></label> <input type="submit" value="送信"></p>
            <p><label>サイト情報を保存する <input type="checkbox" name="save" value="サイト情報を保存する"></label></p>
          </form>
      </div>
      <?php if(!empty($css_url)) { ?>
      <div class="row">
        <h2>CSS変更フォーム</h2>
            <?php
            for($count = 1; $count < $iterator->count(); $count++) {
                $oParser = new Sabberworm\CSS\Parser($cache->get("css${count}"));
                $oCss = $oParser->parse();
                echo "<form action='index.php' method='post'>";
                echo "<ul>";
                foreach($oCss->getAllRuleSets() as $oRuleSet) {
                    if (!empty($oRuleSet)) {
                        $selector = explode("{", $oRuleSet);
                        foreach($oRuleSet->getRules() as $Rule) {
                            form_rule($Rule->getRule(), $selector, $count);
                        }
                    }
                }
                if (!empty($oRuleSet)) {
                    echo "<li><input type=submit value=CSS${count}を更新></li>";
                } else {
                  /*  $import_css = $cache->get("css${count}");

                    $dom = new DOMDocument();
                    @$dom->loadHTML($import_css);
                    $css = simplexml_import_dom($dom);
                    for($import_count = 1; $import_count < $iterator->count(); $import_count++) {
                        if($count == $import_count) {
                            echo $count;
                            continue;
                        } 
                        $css_path_replace[] = $cache->getCacheFilePath("cs${import_count}");
                    }
                    print_r($css_path_replace);
                    $matchs = extract_css_urls($import_css);
                    print_r($matchs['import']);
                    //      foreach($matches[1] as $url){
                    //          print $url . "<br />";
                    //    }
                   // var_dump($css);*/
                }
                echo "</ul>";
                echo "</form>";
            } ?>
      </div>
      <?php }
    /*   $css_url[] = array();
        for($count = 1; $count < $iterator->count(); $count++) {
          if(!empty($_POST["css${count}"])) {
              $css_array = $_POST["css${count}"];
              $oParser = new Sabberworm\CSS\Parser($cache->get("css${count}"));
              $oCss = $oParser->parse();
              foreach($oCss->getAllRuleSets() as $oRuleSet) {
                  foreach($oRuleSet->getRules() as $Rule) {
                        if(!empty(current($css_array))) {
                            $Rule->setValue(current($css_array));
                        }
                  }
              }
          $cache->put("css${count}", $oCss);
          $css_url[] = $cache->get("css${count}");
          }
      }*/
      ?>
      <?php if (!empty($css_url)) { ?>
      <div class="row">
        <h2>CSSの出力</h2>
        <?php foreach($css_url as $css) { ?>
        <form>
          <textarea cols="70" rows="70">
          <?php echo($css); ?>
          </textarea>
        </form>
        <?php } ?>
      </div>
      <?php } ?>
      <div class="row">
        <h1> CSSジェネレーター</h1>
        <form method="post">
            <p><label>URL：<input type="url" name="url" size="40"></label> <input type="submit" value="送信"></p>
            <p><label>サイト情報を保存する <input type="checkbox" name="save" value="サイト情報を保存する"></label></p>
        </form>
      </div>
    </div>
  </body>
</html>
