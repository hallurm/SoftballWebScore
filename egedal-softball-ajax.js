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
                    // Opdater DOM-elementer med de nye scoredata
                    $('#hjemmehold-score').text(response.data.hjemmehold_score);
                    $('#udehold-score').text(response.data.udehold_score);
                    $('#aktuel-inning').text(response.data.inning);
                    $('#kamp-status').text(formatStatus(response.data.status));
                } else {
                    console.error('Fejl ved hentning af live score');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX-fejl:', error);
            },
            complete: function() {
                $('#loading-indicator').hide();
                // Kald funktionen igen efter 30 sekunder
                setTimeout(updateLiveScore, 30000);
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

    // Start den første opdatering
    updateLiveScore();
});
