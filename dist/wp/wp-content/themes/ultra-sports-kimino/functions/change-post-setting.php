<?php

function modify_post_rewrite_rules() {
    $permalink_structure = get_option('permalink_structure');

    // カテゴリーアーカイブ
    add_rewrite_rule('newsroom/category/([^/]+)/page/([0-9]+)/?$', 'index.php?category_name=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule('newsroom/category/([^/]+)/?$', 'index.php?category_name=$matches[1]', 'top');

    // タグアーカイブ (必要なら追加)
    add_rewrite_rule('newsroom/tag/([^/]+)/page/([0-9]+)/?$', 'index.php?tag=$matches[1]&paged=$matches[2]', 'top');
    add_rewrite_rule('newsroom/tag/([^/]+)/?$', 'index.php?tag=$matches[1]', 'top');

    // 日付アーカイブ
    add_rewrite_rule('newsroom/([0-9]{4})/([0-9]{2})/([0-9]{2})/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]', 'top');
    add_rewrite_rule('newsroom/([0-9]{4})/([0-9]{2})/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]', 'top');
    add_rewrite_rule('newsroom/([0-9]{4})/?$', 'index.php?year=$matches[1]', 'top');

    // 投稿詳細ページ（パーマリンク構造に対応）
    if ($permalink_structure) {
        $permalink_structure = str_replace('%postname%', '([^/]+)', $permalink_structure);
        $permalink_structure = str_replace('%year%', '([0-9]{4})', $permalink_structure);
        $permalink_structure = str_replace('%monthnum%', '([0-9]{2})', $permalink_structure);
        $permalink_structure = str_replace('%day%', '([0-9]{2})', $permalink_structure);
        $permalink_structure = trim($permalink_structure, '/');

        add_rewrite_rule('newsroom/' . $permalink_structure . '/?$', 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]', 'top');
    } else {
        add_rewrite_rule('newsroom/([^/]+)/?$', 'index.php?name=$matches[1]', 'top');
    }
}
add_action('init', 'modify_post_rewrite_rules');

function post_has_archive( $args, $post_type ) {
    if ( 'post' === $post_type ) {
        $args['rewrite'] = array(
            'slug'       => 'newsroom',
            'with_front' => false
        );
        $args['has_archive'] = 'newsroom';
        $args['labels'] = array(
            'name' => 'ニュースルーム'
        );
    }
    return $args;
}
add_filter( 'register_post_type_args', 'post_has_archive', 10, 2 );

function change_category_permalinks($termlink, $term, $taxonomy) {
    if ($taxonomy === 'category') {
        $termlink = str_replace('/category/', '/newsroom/category/', $termlink);
    }
    return $termlink;
}
add_filter('term_link', 'change_category_permalinks', 10, 3);

function change_post_link($permalink, $post) {
    if ($post->post_type === 'post') {
        $permalink_structure = get_option('permalink_structure');
        if (!$permalink_structure) {
            return home_url('/news/' . $post->post_name . '/');
        }

        $year  = get_the_date('Y', $post);
        $month = get_the_date('m', $post);
        $day   = get_the_date('d', $post);
        $slug  = $post->post_name;

        $replacements = [
            '%year%' => $year,
            '%monthnum%' => $month,
            '%day%' => $day,
            '%postname%' => $slug,
        ];

        $news_permalink = str_replace(array_keys($replacements), array_values($replacements), $permalink_structure);
        return home_url('/newsroom' . $news_permalink);
    }
    return $permalink;
}
add_filter('post_link', 'change_post_link', 10, 2);

// 年アーカイブのリンク変更
add_filter('year_link', function ($link, $year) {
    return home_url("/newsroom/{$year}/");
}, 10, 2);

// 月アーカイブのリンク変更
add_filter('month_link', function ($link, $year, $month) {
    return home_url("/newsroom/{$year}/{$month}/");
}, 10, 3);

// 日アーカイブのリンク変更
add_filter('day_link', function ($link, $year, $month, $day) {
    return home_url("/newsroom/{$year}/{$month}/{$day}/");
}, 10, 4);