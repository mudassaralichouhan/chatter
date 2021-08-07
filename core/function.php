<?php

function is_login() {
    if(!isset($_SESSION[USER_ID])) {
        echo json_encode([
            STATUS => false,
            ERR_TXT => 'Session not create',
            REDIR_LINK => base_url().'/login.php'
        ]);
        exit;
    }
}

function base_url() {
    $url =  (isset ($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
    $url .= $_SERVER['SERVER_NAME'];
//    $url .= $_SERVER['REQUEST_URI'];
    return $url.'/chatter';
}

function date_time_readable($timestemp) {
    $sys_date_time = strtotime(date('Y-m-d H:i:s'));
    $date_time = strtotime($timestemp);
    $minute = floor(($sys_date_time - $date_time) / 60);

    /*
     * 1440 day
     * 43200 month
     * 525600 year
     */

    if($minute < 60) {
        return $minute. ' min ago';
    } elseif ($minute < 1440) {
        return floor($minute/60).' hour ago';
    } elseif ($minute < 43200) {
        return floor($minute/1440).' day ago';
    } elseif ($minute < 525600) {
        return floor($minute/43200).' month ago';
    } else {
        return floor($minute/525600).' year ago';
    }
}
function get_msg_date_time_format($timestamp) {
    $sys_date_time = strtotime(date('Y-m-d H:i:s'));
    $date_time = strtotime($timestamp);
    $minutes = floor(($sys_date_time - $date_time) / SEC);

    if($minutes < 1) {

        return 'now';

    } elseif($minutes <= 30) {

        return $minutes. ' minute ago';

    } elseif ($minutes < 1440) { // Today

        $date = date_create($timestamp);
        return date_format($date, 'h:i a');
        // 08:30AM

    } elseif ($minutes < 2880) { // 1 day ago e.g yesterday

        $date = date_create($timestamp);
        return 'Yesterday '.date_format($date, 'h:i a');
        // Yesterday 08:30AM

    } elseif ($minutes < 10080) { // 7 day ago

        $date = date_create($timestamp);
        return floor($minutes/1440).' day ago at '.date_format($date, 'h:i a');
        // 7 Day ago 08:30AM

    } elseif($minutes < 525600) { // under a year

        $date = date_create($timestamp);
        return date_format($date, 'l d-M h:i a');
        // Saturday:05-Jun 10:30 pm

    } else {

        $date = date_create($timestamp);
        return date_format($date, 'd-M Y h:i a');
        // Saturday:05-Jun 2021 10:30 pm

    }
}

function response_msg( bool $status=false, string $err_txt='', string $redir_link='#', array $data = [] ) {
    echo json_encode([
        STATUS => $status,
        ERR_TXT => $err_txt,
        REDIR_LINK => $redir_link,
        'data' => $data
    ]);
    exit;
}