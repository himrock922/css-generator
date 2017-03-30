<?php

function form_rule($key, $selector) {
    $rule = $key;
    switch($rule) {
        case "position":
        echo "<p>${selector[0]}のボックス配置方法を変更する</p>";
        echo "<select name=${selector[0]}>";
        echo "<option value=static>static</option>";
        echo "<option value=relatice>relative</option>";
        echo "<option value=absolute>absolute</option>";
        echo "<option value=fixed>fixed</option>";
        echo "</select>";
        break;
        case "top":
        echo "<p>${selector[0]}の上からの配置位置を変更する</p>";
        echo "<input type=text name=${selector[0]}>";
        break;
        case "left":
        echo "<p>${selector[0]}の左からの配置位置を変更する</p>";
        echo "<input type=text name=${selector[0]}>";
        break;
    }
}