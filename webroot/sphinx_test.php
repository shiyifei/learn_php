<?php

require('sphinxapi.php');

$sphinx_client = new SphinxClient();
$sphinx_client->SetServer('127.0.0.1',9306);

$sphinx_client->SetArrayResult(TRUE);

$sphinx_client->SetIDRange(1,20);

$sphinx_client->SetFilter('group_id',[1,2]);

$sphinx_client->SetLimits(0,20);

$res = $sphinx_client->Query('学习','rt');

// $sphinx_client->SetMatchMode(SPH_MATCH_EXTENDED2);
// $res = $sphinx_client->Query('@title (测试)','rt');

// $res = $sphinx_client->Query('@title (测试) @content (网络)','rt');

echo '<pre>';
print_r($res['matchs']);
var_dump($res);
print_r($sphinx_client->GetLastError());
print_r($sphinx_client->GetLastWarning());
echo '</pre>';
