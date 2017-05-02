<?php

function form_rule($key, $selector, $count, $value, $css_array) {
    $rule = $key;
    $id = array_search_recursive($rule, $css_array);
    if(!empty($id)) {
        switch($css_array[$id]['tag_name']) {
        case 1:
        echo "<label>${selector[0]}の色を変更する</label>";
        echo "<input class='picker' value='${value}' name='css${count}[]'>";
        break;
        case 2:
        echo "<label>${selector[0]}の値を変更する</label>";
        echo "<input value='${value}' type='text' name='css${count}[]'>";
        break;
        case 3:
        echo "<label>${selector[0]}の値を変更する</label>";
        echo "<select name='css${count}[]'>";
        echo "<option value='${value}'>${value}</option>";
        foreach($css_array[$id]['block'] as $select_tag) {
            echo "<option value='${select_tag}'>${select_tag}</option>";
        }
        echo "</select>";
        break;
        }
    } else {
        return false;
    }
}

function array_search_recursive($search_element, $array)
{
    $recursive_func = function ($search_element, $array) use (&$recursive_func) {
        foreach ($array as $key => $value) {
            if(is_array($value)){
                if($recursive_func($search_element, $value) !== false) return $key;
            }
            if ($search_element == $value) return $key;
        }
        return false;
    };
    return $recursive_func($search_element, $array);
}