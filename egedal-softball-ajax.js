jQuery(document).ready(function($) {
    function updateLiveScore() {
        $('#loading-indicator').show();
        $.ajax({
            url: egedal_softball_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_live_score',
                security: egedal_softball_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#hjemmehold-score').text(response.data.hjemmehold_score);
                    $('#udehold-score').text(response.data.udehold_score);
                    $('#aktuel-inning').text(response.data.inning);
                    $('#kamp-status').text(formatStatus(response.data.status));
                } else {
                    console.error('Fejl ved hentning af live score:', response.message);
                    $('#live-score-error').text('Kunne ikke hente live score. Prøv igen senere.').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX-fejl:', error);
                $('#live-score-error').text('Der opstod en fejl. Prøv igen senere.').show();
            },
            complete: function() {
                $('#loading-indicator').hide();
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

    updateLiveScore();
});
