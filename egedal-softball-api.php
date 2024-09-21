<?php
/**
 * Plugin Name: Egedal Softball Live Score API
 * Description: Implementerer API-endepunkt for live score-opdateringer
 * Version: 1.0
 * Author: Din Navn
 */

// Sikkerhedscheck
if (!defined('ABSPATH')) {
    exit;
}

// Registrer API-endepunkt
add_action('rest_api_init', function () {
    register_rest_route('egedal-softball/v1', '/update-score', array(
        'methods' => 'POST',
        'callback' => 'egedal_softball_update_score',
        'permission_callback' => function () {
            return current_user_can('edit_posts');
        }
    ));
});

// Callback-funktion for API-endepunkt
function egedal_softball_update_score($request) {
    $params = $request->get_params();
    
    $post_id = $params['kamp_id'];
    $inning = sanitize_text_field($params['inning']);
    $hjemmehold_score = intval($params['hjemmehold_score']);
    $udehold_score = intval($params['udehold_score']);
    $status = sanitize_text_field($params['status']);

    // Opdater ACF-felter
    update_field('aktuel_inning', $inning, $post_id);
    update_field('score_hjemmehold', $hjemmehold_score, $post_id);
    update_field('score_udehold', $udehold_score, $post_id);
    update_field('kamp_status', $status, $post_id);

    return new WP_REST_Response(array('success' => true), 200);
}
