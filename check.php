<?php

require("system/__init__.php");


/**
 * Session must be clean on this step
 */
if (!empty($_SESSION)) {
    session_unset();
    session_destroy();
}

/**
 * Handle form data
 */
if ($_POST) {
    $captcha_res = captcha_verify();
    $email_value = post_arg('email_input', 'strtolower', '/^[\w\d_\-\.]+@[\w\d\-\.]+\.\w+$/');
    $email_code = post_arg('email_code_input', 'clean_intval', '/^\d{1,16}$/');
    $mobile_value = post_arg('mobile_input', 'clean_mobile', '/^[\d]{10,12}$/');
    $mobile_code = post_arg('mobile_code_input', 'clean_intval', '/^\d{1,16}$/');
    $vote_keys = post_arg('vote_keys', 'clean_mobile', '/^[\d,]{1,50}$/');

    if ($captcha_res && $email_value && $email_code && $mobile_value
            && $mobile_code && $vote_keys) {
        $args = array(
            'email_value' => $email_value,
            'email_code' => $email_code,
            'mobile_value' => $mobile_value,
            'mobile_code' => $mobile_code,
            'vote_keys' => $vote_keys);
        $res = search_log_line($args);
        $publine = $res[0];
        $logline = $res[1];
        $foundline = $res[2];
    } else if (!$captcha_res) {
        append_error("Не пройдено тест на роботів!");
    } else {
        append_error("Не введено необхідні дані");
    }
} else {
    $captcha_res = false;
}

require(get_template('check'));
