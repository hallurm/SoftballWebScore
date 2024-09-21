// Tilføj denne kode i din tema's functions.php fil

// Registrer AJAX-handling
add_action('wp_ajax_get_live_score', 'egedal_softball_get_live_score');
add_action('wp_ajax_nopriv_get_live_score', 'egedal_softball_get_live_score');

function egedal_softball_get_live_score() {
    // Sikkerhedscheck
    check_ajax_referer('egedal_softball_nonce', 'security');

    // Hent den seneste kamp (antager at vi kun viser én kamp ad gangen)
    $args = array(
        'post_type' => 'kampe',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $latest_match = get_posts($args);

    if (!empty($latest_match)) {
        $post_id = $latest_match[0]->ID;
        $response = array(
            'success' => true,
            'data' => array(
                'hjemmehold_score' => intval(get_field('score_hjemmehold', $post_id)),
                'udehold_score' => intval(get_field('score_udehold', $post_id)),
                'inning' => sanitize_text_field(get_field('aktuel_inning', $post_id)),
                'status' => sanitize_text_field(get_field('kamp_status', $post_id))
            )
        );
    } else {
        $response = array('success' => false, 'message' => 'Ingen aktive kampe fundet.');
    }

    wp_send_json($response);
}

// Indlæs AJAX-script
function egedal_softball_enqueue_scripts() {
    wp_enqueue_script('egedal-softball-ajax', get_template_directory_uri() . '/js/egedal-softball-ajax.js', array('jquery'), '1.0', true);
    wp_localize_script('egedal-softball-ajax', 'egedal_softball_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('egedal_softball_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'egedal_softball_enqueue_scripts');
