<?php

function form_rule($key, $selector) {
    $rule = $key;
    switch($rule) {
        case "position":
        echo "<li>";
        echo "<label>${selector[0]}のボックス配置方法を変更する</label>";
        echo "<select name=${selector[0]}>";
        echo "<option value=static>static</option>";
        echo "<option value=relatice>relative</option>";
        echo "<option value=absolute>absolute</option>";
        echo "<option value=fixed>fixed</option>";
        echo "</select>";
        echo "</li>";
        break;
        case "top":
        echo "<li>";
        echo "<label>${selector[0]}の上からの配置位置を変更する</label>";
        echo "<input type=text name=${selector[0]}>";
        echo "</li>";
        break;
        case "left":
        echo "<li>";
        echo "<label>${selector[0]}の左からの配置位置を変更する</label>";
        echo "<input type=text name=${selector[0]}>";
        echo "</li>";
        break;
        case "color":
        echo "<li>";
        echo "<label>${selector[0]}の色を変更する</label>";
        echo "<input type=text class=picker name=${selector[0]}>";
        echo "</li>";
        break;
    }
}