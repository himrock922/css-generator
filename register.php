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
    <script src="toggle.js"></script>
    <script src="tag_add.js"></script>
    <title>CSSジェネレーター -CSS命令文登録フォーム-</title>
  </head>
  <body>
    <div class="pure-g">
      <div class="pure-u-1-1 pure-text">
          <h1>CSS命令文登録フォーム</h1>
          <form method="post" class="pure-form pure-form-aligned" name="form" id="register">
            <fieldset>
              <legend>CSSジェネレーターに登録するCSSの命令を登録します。</legend>

              <div class="pure-control-group">
                  <label for="css">CSS命令文</label>
                  <input type="text" name="css[name]" size="20" required>
              </div>

              <div class="pure-control-group">
                  <label for="select_one" class="pure-radio">
                      <input id="select_one"onchange="entryChange();" type="radio" name="css[tag_name]" value="1" checked>
                      色を選択
                  </label>
                  <label for="select_two" class="pure-radio">
                      <input id="select_two" onchange="entryChange();" type="radio" name="css[tag_name]" value="2">
                      値を入力
                　</label>
                　<label for="select_three" class="pure-radio">
                      <input id="select_three" onchange="entryChange();" type="radio" name="css[tag_name]" value="3">
                      固定値を指定
                　</label>
              </div>
              
              <div class="pure-control-group" id="select_tag" style="display:none; text-align:center;">
                  <table id="table_tag">
                      <tr>
                          <td>
                              <label for="block">固定値</label>
                              <input type="text" name="css[block][]" size="20">
                          </td>
                      </tr>
                  </table>
                  <br />
                  <button type="button" id="tag_add" class="pure-button pure-button-primary">フォームを追加</button>
                  <button type="button" id="tag_delete" class="pure-button pure-button-error">フォームを削除</button>
              </div>

              <button type="submit" class="pure-button pure-button-success">登録</button>
              <a class="pure-button pure-button-secondary" href="index.php">TOPページへ戻る</a>
            </fieldset>
          </form>
      </div>
      <?php if(!empty($_POST["css"])) {
          $json = file_get_contents("css_tag.json");
          $records = (array)json_decode($json, true);
          $records[] =  $_POST["css"];
          file_put_contents("css_tag.json",json_encode($records));
      } ?>
      <div class="pure-u-1-1 pure-text">
        <h2>登録したCSS命令ファイルの出力</h2>
        <?php
            $cssfile = file_get_contents("css_tag.json");
            $css_array = json_decode($cssfile, true);
        ?>
        <form class="pure-form">
          <textarea cols="70" rows="70" readonly>
             
          <?php print_r($css_array);
          ?>
          </textarea>
        </form>
    </div>
  </body>
</html>