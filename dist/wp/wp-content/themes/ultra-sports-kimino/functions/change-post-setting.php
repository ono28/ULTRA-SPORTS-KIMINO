<?php

function add_article_post_permalink( $permalink ) {
    $permalink = '/news' . $permalink;
    return $permalink;
}
add_filter( 'pre_post_link', 'add_article_post_permalink' );

function add_article_post_rewrite_rules( $post_rewrite ) {
    $return_rule = array();
    foreach ( $post_rewrite as $regex => $rewrite ) {
        $return_rule['news/' . $regex] = $rewrite;
    }
    return $return_rule;
}
add_filter( 'post_rewrite_rules', 'add_article_post_rewrite_rules' );

// リライトルールを追加
function add_news_rewrite_rules() {
    add_rewrite_rule(
        '^news/([0-9]{4})/([0-9]{2})/([0-9]{2})/([^/]+)/?$',
        'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]',
        'top'
    );
    add_rewrite_rule(
        '^news/([^/]+)/?$',
        'index.php?name=$matches[1]',
        'top'
    );
}
add_action( 'init', 'add_news_rewrite_rules' );

// 管理画面の「投稿」を「NEWS」に変更
function change_post_menu_label() {
    global $menu, $submenu;
    $menu[5][0] = 'NEWS';
    $submenu['edit.php'][5][0] = 'NEWS一覧';
    $submenu['edit.php'][10][0] = '新しいNEWS';
}
add_action( 'admin_menu', 'change_post_menu_label' );

function change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'NEWS';
    $labels->singular_name = 'NEWS';
    $labels->add_new = '新規追加';
    $labels->add_new_item = '新しいNEWSを追加';
    $labels->edit_item = 'NEWSを編集';
    $labels->new_item = '新しいNEWS';
    $labels->view_item = 'NEWSを表示';
    $labels->search_items = 'NEWSを検索';
    $labels->not_found = 'NEWSが見つかりませんでした';
    $labels->not_found_in_trash = 'ゴミ箱にNEWSはありません';
    $labels->all_items = 'NEWS一覧';
    $labels->menu_name = 'NEWS';
    $labels->name_admin_bar = 'NEWS';
}
add_action( 'init', 'change_post_object_label' );