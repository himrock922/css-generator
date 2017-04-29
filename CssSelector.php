<?php

function form_rule($key, $selector, $count) {
    $rule = $key;
    switch($rule) {
        case "position":
        echo "<label>${selector[0]}のボックス配置方法を変更する</label>";
        echo "<select name=css${count}[]>";
        echo "<option value=></option>";
        echo "<option value=static>static</option>";
        echo "<option value=relative>relative</option>";
        echo "<option value=absolute>absolute</option>";
        echo "<option value=fixed>fixed</option>";
        echo "</select>";
        break;
        case "top":
        echo "<label>${selector[0]}の上からの配置位置を変更する</label>";
        echo "<input type=text name=css${count}[]>";
        break;
        case "left":
        echo "<label>${selector[0]}の左からの配置位置を変更する</label>";
        echo "<input type=text name=css${count}[]>";
        break;
        case "color":
        echo "<label>${selector[0]}の色を変更する</label>";
        echo "<input class=picker name=css${count}[]>";
        break;
    }
}