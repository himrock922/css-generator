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

/**
* 文字列からBOMデータを削除する
*
* @param string $str 対象文字列
* @return string $str BOM削除した文字列
*/
function deleteBom($str)
{
    if (($str == NULL) || (mb_strlen($str) == 0)) {
        return $str;
    }
    if (ord($str{0}) == 0xef && ord($str{1}) == 0xbb && ord($str{2}) == 0xbf) {
        $str = substr($str, 3);
    }
    return $str;
}

  $cache = new Cache();
  $html = str_get_html($cache->get('html'), true, true, DEFAULT_TARGET_CHARSET, false, false, false);
  $css_url = array();
  if(!empty($html)) {
      $iterator = new GlobIterator(dirname(__FILE__) . '/cache/*');
      for($count = 1; $count <= $iterator->count(); $count++) {
          if($cache->get("css${count}")) {
              $css_url[] = $cache->get("css${count}");
          } else if($cache->get("import${count}")) {
              $css_url[] = $cache->get("import${count}");
          } else if($cache->get_import("css${count}")) {
              $css_url[] = $cache->get_import("css${count}");
          }
      }
  }
  if (!empty($_POST["url"])) {
      if (!empty($_POST["save"])) {
          $html = $cache->delete('html');
          $iterator = new GlobIterator(dirname(__FILE__) . '/cache/*');
          for($count = 1; $count < $iterator->count(); $count++) {
              if(!$cache->delete("css${count}")) {
                  if(!$cache->delete("import${count}")) {
                      $cache->delete_import("css{count}");
                  }
              }
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
        $i_matchs = "";
        $i_css = "";
        $i_count = 0;
        $i_url = array();
        $import_css_to = array();
        foreach($from_url as $css) {
            $matchs = extract_css_urls($css);
            if(!empty($matchs['import'])) {
              $i_matchs = $matchs['import'];
              $i_count = $count;
              $i_css = $css;
              $count++;
              continue;
            }
            $css = deleteBom($css);
            $cache->put("css${count}", $css);
            $import_css_to[] = $css;
            $count++;
        }
        foreach($import_url as $css) {
            $css = deleteBom($css);
            $cache->put("import${count}", $css);
            $i_url[] = $cache->getCacheImportFilePath("import${count}");
            $count++;
        }
        $i_css = str_replace($i_matchs, $i_url, $i_css);
        $i_css = deleteBom($i_css);
        $cache->put_import("css${i_count}", $i_css);
        $import_css_to[] = $i_css;
        $css_url = array();
        $cache_css = array();
        $iterator = new GlobIterator(dirname(__FILE__) . '/cache/*');
        for($count = 1; $count < $iterator->count(); $count++) {
            if($cache->get("css${count}")) {
                $css_url[] = $cache->get("css${count}");
                $css_path = $cache->getCacheFilePath("css${count}");
                $cache_css[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"${css_path}\">";
            } else if($cache->get("import${count}")) {
                $css_url[] = $cache->get("import${count}");
            } else if($cache->get_import("css${count}")) {
                $css_url[] = $cache->get_import("css${count}");
                $css_path = $cache->getCacheImportPath("css${count}");
                $cache_css[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"${css_path}\">";
            }
        }
        $html = str_get_html($cache->get('html'), true, true, DEFAULT_TARGET_CHARSET, false, false, false);
        $css_from = css_array_flatten($html);
        $css_replace = array_combine($css_from, $cache_css);
        $html = strtr($html, $css_replace);
        $cache->put('html', htmlspecialchars_decode($html));
        // 最終的なHTMLデータを取得 //
        $html = str_get_html($cache->get('html'), true, true, DEFAULT_TARGET_CHARSET, false, false, false);
        }
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
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/pure-min.css" integrity="sha384-UQiGfs9ICog+LwheBSRCt1o5cbyKIHbwjWscjemyBMT9YCUMZffs6UqUTd0hObXD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/buttons-min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
     <link rel="stylesheet" type="text/css" href="pure.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
    <script src="color.js"></script>
    <title>CSSジェネレーター</title>
  </head>
  <body>
    <div class="pure-g">
      <div class="pure-u-1-1 pure-text">
          <h1>CSSジェネレーター</h1>
          <form method="post" class="pure-form">
            <fieldset>
              <p><label>URL：<input type="url" name="url" size="40"></label> <button type="submit" class="pure-button pure-button-primary">送信</button></p>
              <p><label>サイト情報を保存する <input type="checkbox" name="save" value="サイト情報を保存する"></label></p>
              <a class="pure-button pure-button-secondary" href="register.php">CSSの命令文を登録する</a>
            </fieldset>
          </form>
      </div>
      <?php if(!empty($css_url)) { ?>
      <div class="pure-u-1-1">
        <h2>CSS変更フォーム</h2>
            <?php
            $cssfile = file_get_contents("css_tag.json");
            $css_array = json_decode($cssfile, true);
            for($count = 1; $count < $iterator->count(); $count++) {
                if($cache->get("css${count}")) {
                    $oParser = new Sabberworm\CSS\Parser($cache->get("css${count}"));
                    $oCss = $oParser->parse();
                    echo "<form action='index.php' method='post' class='pure-form pure-form-stacked pure-width'>";
                    echo "<fieldset>";
                    echo "<legend>CSS${count}</legend>";
                    foreach($oCss->getAllRuleSets() as $oRuleSet) {
                        if (!empty($oRuleSet)) {
                            $selector = explode("{", $oRuleSet);
                            foreach($oRuleSet->getRules() as $Rule) {
                                form_rule($Rule->getRule(), $selector, $count, $Rule->getValue(), $css_array);
                            }
                        }
                    }
                    if (!empty($oRuleSet)) {
                        echo "<label for='button'><button type='submit' class='pure-button pure-button-active'>CSS${count}を更新</button></label>";
                    }
                } else if ($cache->get("import${count}")) {
                    $oParser = new Sabberworm\CSS\Parser($cache->get("import${count}"));
                    $oCss = $oParser->parse();
                    echo "<form action='index.php' method='post' class='pure-form pure-form-stacked pure-width'>";
                    echo "<fieldset>";
                    echo "<legend>CSS${count}</legend>";
                    foreach($oCss->getAllRuleSets() as $oRuleSet) {
                        if (!empty($oRuleSet)) {
                            $selector = explode("{", $oRuleSet);
                            foreach($oRuleSet->getRules() as $Rule) {
                                form_rule($Rule->getRule(), $selector, $count, $Rule->getValue(), $css_array);
                            }
                        }
                    }
                    if (!empty($oRuleSet)) {
                        echo "<label for='button'><button type='submit' class='pure-button pure-button-active'>CSS${count}を更新</button></label>";
                    }
                } else {
                  echo "<pre>CSS${count}はimportファイルのため、表示を省略します。</pre>";
                }
                echo "</fieldset>";
                echo "</form>";
            }
            ?>
      </div>
      <?php }
        if(!empty($css_array)) {
            $css_url = array();
        }
        for($count = 1; $count < $iterator->count(); $count++) {
          if(!empty($_POST["css${count}"])) {
              $css_array = array();
              foreach($_POST["css${count}"] as $key => $value) {
                  $css_array[] = $value;
              }
              $set_value = $css_array[0];
              if($cache->get("css${count}")) {
                  $oParser = new Sabberworm\CSS\Parser($cache->get("css${count}"));
                  $oCss = $oParser->parse();
                  $css_tmp = $css_array;
                  foreach($oCss->getAllRuleSets() as $oRuleSet) {
                      foreach($oRuleSet->getRules() as $Rule) {
                          $Rule->setValue($set_value);
                      }
                      $set_value = next($css_array);
                  }
                  $cache->put("css${count}", $oCss->render());
                  $css_url[] = $cache->get("css${count}");
              } else if ($cache->get("import${count}")) {
                  $oParser = new Sabberworm\CSS\Parser($cache->get("import${count}"));
                  $oCss = $oParser->parse();
                  foreach($oCss->getAllRuleSets() as $oRuleSet) {
                      foreach($oRuleSet->getRules() as $Rule) {
                          $Rule->setValue($set_value);
                      }
                      $set_value = next($css_array);
                  }
                  $cache->put("import${count}", $oCss->render());
                  $css_url[] = $cache->get("import${count}");
              }
          } else {
              if($cache->get("css${count}")) {
                  $css_url[] = $cache->get("css${count}");
              } else if ($cache->get("import${count}")) {
                  $css_url[] = $cache->get("import${count}");
              } else if($cache->get_import("css${count}")) {
                  $css_url[] = $cache->get_import("css${count}");
            }
          }
      }
      ?>
      <?php if (!empty($css_url)) {
          $count = 1;
      ?>
      <div class="pure-u-1-1 pure-text">
        <h2>CSSの出力</h2>
        <?php foreach($css_url as $css) { ?>
        <form class="pure-form">
          <p><?php echo "CSS${count}"; ?></p>
          <textarea cols="70" rows="70" readonly>
          <?php echo($css); ?>
          </textarea>
        </form>
        <?php
        $count++;
        } 
        ?>
      </div>
      <?php } 
      if (!empty($html)) { ?>
      <div class="pure-u-1-1 pure-text">
          <h1>CSSジェネレーター</h1>
          <form method="post" class="pure-form">
            <fieldset>
              <p><label>URL：<input type="url" name="url" size="40"></label> <button type="submit" class="pure-button pure-button-primary">送信</button></p>
              <p><label>サイト情報を保存する <input type="checkbox" name="save" value="サイト情報を保存する"></label></p>
              <a class="pure-button pure-button-secondary" href="register.php">CSSの命令文を登録する</a>
            </fieldset>
          </form>
      </div>
      <?php } ?>
    </div>
  </body>
</html>

<?php 
function array_set_pointer(&$array, $value)
{
    reset($array);
    while($val=current($array))
    {
        if($val==$value) 
            break;

        next($array);
    }
}