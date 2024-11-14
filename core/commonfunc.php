<?php

/* データベースにアクセスしない関数群 */

/* 現在の年（西暦）を取得し、前後５年分の西暦をリストとして返却する */
function	get_year_list()
{
    $year = date("Y") - 5;
    $list = array();
    
    for($i = 0; $i <=10; $i++){
        $list[] = $year + $i;
    }

    return $list;
}



?>
