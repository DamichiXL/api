<?php
/**
 * Created by PhpStorm.
 * User: Maksym.Sliusarchuk
 * Date: 16.05.2018
 * Time: 12:35
 */
include "simple_html_dom.php";

function formatString(string $s){
    $res = $s;
    $res = strip_tags($res);
    $res = str_replace("1 підгрупа","|",$res);
    $res = str_replace("2 підгрупа","",$res);
    $res = str_replace("Цілою групою","|Цілою групою",$res);
    $res = substr($res,1);
    $res = str_replace("доц.","|доц.",$res);
    $res = str_replace("проф.","|проф.",$res);
    $res = str_replace("викл.","|викл.",$res);
    $res = str_replace("Викладач","|Викладач",$res);
    return $res;
}


if (isset($_GET['group']))
    $g = $_GET['group'];
else
    $g = 1;
$url = "http://fmi-rshu.org.ua/groups/".$g;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$body = curl_exec($ch);
curl_close($ch);
$html = str_get_html($body);
$arr = array();
$i=0;
foreach ($html->find(".block-margin ul li") as $ul){
    $arr[$i]= array();
    $text = str_get_html($ul);
    foreach($text->find('.timetable-group-lesson') as $item){
           $arr[$i][] = formatString($item->innertext);

    }
    $i++;
    $text->clear();
    unset($text);
}
$html->clear();
unset($html);
echo json_encode($arr,JSON_UNESCAPED_UNICODE);