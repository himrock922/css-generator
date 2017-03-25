<?php

function form_rule($key) {
    $rule = $key;
    switch($rule) {
        case "position":
        echo "<p>~の配置変更";
        echo "<select name=>";
        echo "<option value=static>static</option>";
        echo "<option value=relatice>relative</option>";
        echo "<option value=absolute>absolute</option>";
        echo "<option value=fixed>fixed</option>";
        echo "</select>";
        echo "</p>";    
    }
}