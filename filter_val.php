<?php
class filterClass {

    public function filterInput($input, $type) {
        switch($type) {
            case 'email':
                return filter_var($input, FILTER_VALIDATE_EMAIL);
            case 'url':
                return filter_var($input, FILTER_VALIDATE_URL);
            case 'int':
                return filter_var($input, FILTER_VALIDATE_INT);
            case 'float':
                return filter_var($input, FILTER_VALIDATE_FLOAT);
            case 'ip':
                return filter_var($input, FILTER_VALIDATE_IP);
            case 'mac':
                return filter_var($input, FILTER_VALIDATE_MAC);
            case 'regex':
                return filter_var($input, FILTER_VALIDATE_REGEXP);
        }
    }
}

header('Content-Type: text/html;charset=utf-8');

$input = htmlentities($_GET['input']);
echo 'input:'.$input.'<br/>';

if (!empty($input)) {
    $filterObj = new filterClass();

    $output = $filterObj->filterInput($input,'email');
    var_dump($output);
    echo '<br/>';
    $output = $filterObj->filterInput($input,'url');
    var_dump($output);
    echo '<br/>';
    $output = $filterObj->filterInput($input,'int');
    var_dump($output);
    echo '<br/>';
    $output = $filterObj->filterInput($input,'float');
    var_dump($output);
    echo '<br/>';
    $output = $filterObj->filterInput($input,'ip');
    var_dump($output);
    echo '<br/>';
    $output = $filterObj->filterInput($input, 'mac');
    var_dump($output);
    echo '<br/>';
    $output = $filterObj->filterInput($input,'regex');
    var_dump($output);
    echo '<br/>';
}

