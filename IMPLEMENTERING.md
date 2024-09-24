# Implementering af Live Score-funktionalitet på hjemmesiden

## 1. Forberedelse

1.1. Sørg for, at du har adgang til WordPress-installationen og kan redigere temaet.
1.2. Sikr dig, at du har de nødvendige rettigheder til at tilføje og redigere filer i dit tema.

## 2. Tilføjelse af nødvendige filer

2.1. Opret en ny fil kaldet `egedal-softball-ajax.js` i dit temas JavaScript-mappe (typisk `/wp-content/themes/dit-tema/js/`).
2.2. Opret en ny fil kaldet `egedal-softball-api.php` i dit temas rodmappe (typisk `/wp-content/themes/dit-tema/`).

## 3. Implementering af backend-funktionalitet (PHP)

3.1. Åbn `egedal-softball-api.php` og tilføj følgende kode:

```php
<?php
function get_live_score() {
    // Her skal du implementere logikken til at hente live score data
    // Dette er blot et eksempel, du skal tilpasse det til din faktiske datakilde
    $score_data = array(
        'hjemmehold_score' => 5,
        'udehold_score' => 3,
        'periode' => '7. inning',
        'status' => 'I gang'
    );

    return $score_data;
}

function egedal_softball_get_live_score() {
    $score_data = get_live_score();
    wp_send_json_success($score_data);
}

add_action('wp_ajax_get_live_score', 'egedal_softball_get_live_score');
add_action('wp_ajax_nopriv_get_live_score', 'egedal_softball_get_live_score');
```

3.2. Åbn din `functions.php` fil og tilføj følgende kode for at inkludere den nye API-fil:

```php
require_once get_template_directory() . '/egedal-softball-api.php';
```

## 4. Implementering af frontend-funktionalitet (JavaScript)

4.1. Åbn `egedal-softball-ajax.js` og tilføj følgende kode:

```javascript
jQuery(document).ready(function($) {
    function updateLiveScore() {
        $('#loading-indicator').show();
        $.ajax({
            url: egedal_softball_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_live_score'
            },
            success: function(response) {
                if (response.success) {
                    $('#hjemmehold-score').text(response.data.hjemmehold_score);
                    $('#udehold-score').text(response.data.udehold_score);
                    $('#periode').text(response.data.periode);
                    $('#status').text(formatStatus(response.data.status));
                } else {
                    console.error('Fejl ved hentning af live score');
                }
                $('#loading-indicator').hide();
            },
            error: function(xhr, status, error) {
                console.error('AJAX-fejl:', error);
                $('#loading-indicator').hide();
            }
        });
    }

    function formatStatus(status) {
        switch(status.toLowerCase()) {
            case 'ikke startet':
                return 'Ikke startet endnu';
            case 'i gang':
                return 'Kampen er i gang';
            case 'afsluttet':
                return 'Kampen er afsluttet';
            default:
                return status;
        }
    }

    // Opdater live score hvert 30. sekund
    setInterval(updateLiveScore, 30000);

    // Kør updateLiveScore ved pageload
    updateLiveScore();
});
```

4.2. Tilføj følgende kode til din `functions.php` fil for at indlæse JavaScript-filen:

```php
function enqueue_egedal_softball_scripts() {
    wp_enqueue_script('egedal-softball-ajax', get_template_directory_uri() . '/js/egedal-softball-ajax.js', array('jquery'), '1.0', true);
    wp_localize_script('egedal-softball-ajax', 'egedal_softball_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_egedal_softball_scripts');
```

## 5. Tilføjelse af HTML-struktur

5.1. Tilføj følgende HTML-kode til den side eller template, hvor du ønsker at vise live score:

```html
<div id="live-score-container">
    <h2>Live Score</h2>
    <div id="loading-indicator" style="display: none;">Indlæser...</div>
    <div id="score-display">
        <span id="hjemmehold-score">0</span> - <span id="udehold-score">0</span>
    </div>
    <div id="periode"></div>
    <div id="status"></div>
</div>
```

## 6. Styling (valgfrit)

6.1. Tilføj CSS til din temafil eller en separat CSS-fil for at style live score-visningen:

```css
#live-score-container {
    background-color: #f0f0f0;
    padding: 20px;
    border-radius: 5px;
    text-align: center;
}

#score-display {
    font-size: 24px;
    font-weight: bold;
    margin: 10px 0;
}

#periode, #status {
    font-style: italic;
}
```

## 7. Test og fejlfinding

7.1. Test funktionaliteten ved at besøge den side, hvor du har implementeret live score-visningen.
7.2. Åbn browser-konsollen for at se eventuelle fejlmeddelelser.
7.3. Verificer, at score, periode og status opdateres korrekt.

## 8. Tilpasning og optimering

8.1. Juster opdateringsintervallet efter behov ved at ændre værdien i `setInterval`-funktionen.
8.2. Tilpas styling og layout efter dit temas design.
8.3. Overvej at implementere fejlhåndtering og fallback-visning, hvis data ikke kan hentes.

## 9. Sikkerhed og ydeevne

9.1. Implementer nonce-kontrol i dine AJAX-anmodninger for at forbedre sikkerheden.
9.2. Overvej at implementere caching af score-data for at reducere belastningen på serveren.

Ved at følge disse trin, skulle du nu have en fungerende live score-funktionalitet på din WordPress-hjemmeside for Egedal Softball.
