<?php

//костыль
$style="white";
$style2="margin: 10px; display:none;";
for ($i=0; $i < 2; $i++) {
 // if (!(floatval($my_balance[$i]['my_sum'])==floatval($my_balance2[$i]['my_sum'])) OR !(floatval($our_balance[$i]['my_sum'])==floatval($our_balance2[$i]['my_sum']))) {
if (!(number_format($my_balance[$i]['my_sum'], 10)==number_format($my_balance2[$i]['my_sum'],10)) OR !(number_format($our_balance[$i]['my_sum'],10)==number_format($our_balance2[$i]['my_sum'],10))) {
echo number_format($my_balance[$i]['my_sum'],10).'<br/>';
echo number_format($my_balance2[$i]['my_sum'],10).'<br/>';
echo number_format($our_balance[$i]['my_sum'],10).'<br/>';
echo number_format($our_balance2[$i]['my_sum'],10).'<br/>';
    $style="red";
    $style2="margin: 10px; display: inline-block;";
  }
}

$string_my='Мой баланс:<br>';
foreach ($my_balance as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_my.=$value['my_sum'].' '.$value['name_g'].'<br>';
}
$string_our='Общий баланс:<br>';
foreach ($our_balance as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_our.=$value['my_sum'].' '.$value['name_g'].'<br>';
}

$string_my2='Мой вычисляемый баланс:<br>';
foreach ($my_balance2 as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_my2.=$value['my_sum'].' '.$value['name_g'].'<br>';
}
$string_our2='Общий вычисляемый баланс:<br>';
foreach ($our_balance2 as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_our2.=$value['my_sum'].' '.$value['name_g'].'<br>';
}

?>

<div style="overflow: hidden; text-align:left; background-color: <?=$style?>;">
  <div style="margin: 10px; display:inline-block;"><?= $string_my; ?></div>

  <div style="margin: 10px; display:inline-block;"><?= $string_our; ?></div>

  <div style="<?= $style2; ?>"><?= $string_my2; ?></div>

  <div style="<?= $style2; ?>"><?= $string_our2; ?></div>
</div>