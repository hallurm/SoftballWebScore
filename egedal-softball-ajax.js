jQuery(document).ready(function($) {
    function updateLiveScore() {
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
                    $('#kamp-status').text(response.data.status);
                }
            },
            complete: function() {
                // Kald funktionen igen efter 30 sekunder
                setTimeout(updateLiveScore, 30000);
            }
        });
    }

    // Start den første opdatering
    updateLiveScore();
});
