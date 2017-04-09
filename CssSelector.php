<?php

function form_rule($key, $selector, $count) {
    $rule = $key;
    switch($rule) {
        case "position":
        echo "<li>";
        echo "<label>${selector[0]}のボックス配置方法を変更する</label>";
        echo "<select name=css${count}[]>";
        echo "<option value=></option>";
        echo "<option value=static>static</option>";
        echo "<option value=relative>relative</option>";
        echo "<option value=absolute>absolute</option>";
        echo "<option value=fixed>fixed</option>";
        echo "</select>";
        echo "</li>";
        break;
        case "top":
        echo "<li>";
        echo "<label>${selector[0]}の上からの配置位置を変更する</label>";
        echo "<input type=text name=css${count}[]>";
        echo "</li>";
        break;
        case "left":
        echo "<li>";
        echo "<label>${selector[0]}の左からの配置位置を変更する</label>";
        echo "<input type=text name=css${count}[]>";
        echo "</li>";
        break;
        case "color":
        echo "<li>";
        echo "<label>${selector[0]}の色を変更する</label>";
        echo "<input class=picker name=css${count}[]>";
        echo "</li>";
        break;
    }
}