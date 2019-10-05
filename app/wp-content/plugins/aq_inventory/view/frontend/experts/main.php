<?php

if (is_user_logged_in()) {
    aqi_include_scripts('tabs');

    $action = aqi_db();
    $is_expert = false;
    $info = null;

    if (current_user_can('view_expert_panel')) {
        $info = aqi_render('expert_panel', $_GET);
        $is_expert = true;
    } else {
        aqi_include_scripts('parse');
        $info = aqi_render('expert_panel_user', $_GET);
    }

    include __DIR__ . '/panel/notices.php';
    include __DIR__ . '/panel/panel.php';

} else {
    aqi_notification('question', '#469eba', '<strong>' . pll__('Do you have any questions?').'</strong><br>'.pll__('You can ask them to experts'), array(
        'id' => 'aqi_sign_in_btn',
        'href' => 'online-shop-' . aqi_get_current_lang() . '/?auth',
        'val' => pll__('Sign in')
    ));
}






