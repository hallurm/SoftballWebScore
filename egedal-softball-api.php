<?php
/**
 * Plugin Name: Egedal Softball Live Score API
 * Description: Implementerer API-endepunkt for live score-opdateringer
 * Version: 1.1
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
    
    if (!isset($params['kamp_id']) || !isset($params['inning']) || !isset($params['hjemmehold_score']) || !isset($params['udehold_score']) || !isset($params['status'])) {
        return new WP_Error('manglende_parametre', 'Alle påkrævede parametre skal angives', array('status' => 400));
    }

    $post_id = intval($params['kamp_id']);
    $inning = sanitize_text_field($params['inning']);
    $hjemmehold_score = intval($params['hjemmehold_score']);
    $udehold_score = intval($params['udehold_score']);
    $status = sanitize_text_field($params['status']);

    if (!get_post($post_id)) {
        return new WP_Error('ugyldig_kamp_id', 'Den angivne kamp-ID eksisterer ikke', array('status' => 404));
    }

    // Opdater ACF-felter
    $update_inning = update_field('aktuel_inning', $inning, $post_id);
    $update_hjemmehold = update_field('score_hjemmehold', $hjemmehold_score, $post_id);
    $update_udehold = update_field('score_udehold', $udehold_score, $post_id);
    $update_status = update_field('kamp_status', $status, $post_id);

    if ($update_inning === false || $update_hjemmehold === false || $update_udehold === false || $update_status === false) {
        return new WP_Error('opdateringsfejl', 'Der opstod en fejl under opdatering af kampdata', array('status' => 500));
    }

    return new WP_REST_Response(array('success' => true, 'message' => 'Kampdata blev opdateret succesfuldt'), 200);
}
