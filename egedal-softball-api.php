<?php
/**
 * Plugin Name: Egedal Softball Live Score API
 * Description: Implementerer funktionalitet for live score-visning
 * Version: 1.2
 * Author: Din Navn
 */

// Sikkerhedscheck
if (!defined('ABSPATH')) {
    exit;
}

function get_live_score() {
    $args = array(
        'post_type' => 'kampe',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $latest_match = get_posts($args);

    if (!empty($latest_match)) {
        $post_id = $latest_match[0]->ID;
        return array(
            'hjemmehold_score' => intval(get_field('score_hjemmehold', $post_id)),
            'udehold_score' => intval(get_field('score_udehold', $post_id)),
            'inning' => sanitize_text_field(get_field('aktuel_inning', $post_id)),
            'status' => sanitize_text_field(get_field('kamp_status', $post_id))
        );
    }

    return false;
}
