<?php
function dropdown($array, $key, $value) {
    $data = ['' => '-'];
    foreach ($array as $k => $v) {
        $data[$v[$key]] = $v[$value];
    }
    return $data;
}
?>