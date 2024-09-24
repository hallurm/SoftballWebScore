// Tilføj denne kode i din tema's functions.php fil

// Inkluder API-filen
require_once get_template_directory() . '/egedal-softball-api.php';

// Registrer AJAX-handling
add_action('wp_ajax_get_live_score', 'egedal_softball_get_live_score');
add_action('wp_ajax_nopriv_get_live_score', 'egedal_softball_get_live_score');

function egedal_softball_get_live_score() {
    check_ajax_referer('egedal_softball_nonce', 'security');

    $score_data = get_live_score();

    if ($score_data) {
        wp_send_json_success($score_data);
    } else {
        wp_send_json_error(array('message' => 'Ingen aktive kampe fundet.'));
    }
}

// Indlæs AJAX-script
function egedal_softball_enqueue_scripts() {
    wp_enqueue_script('egedal-softball-ajax', get_template_directory_uri() . '/js/egedal-softball-ajax.js', array('jquery'), '1.1', true);
    wp_localize_script('egedal-softball-ajax', 'egedal_softball_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('egedal_softball_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'egedal_softball_enqueue_scripts');
