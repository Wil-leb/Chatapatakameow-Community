//*****R. jQuery vote system*****//
$(document).ready(function(){
    let $vote = $('#vote');

    $('.like', $vote).click(function(e) {
        e.preventDefault();
        vote(1);
    });

    $('.dislike', $vote).click(function(e) {
        e.preventDefault();
        vote(-1);
    });

    function vote(value) {
        $('.vote-loading').show();
        $('.vote-logos').hide();

        $.post('./views/albums/albums.php', {
            id: $vote.data('id'),
            ip: $vote.data('ip'),
            vote: value
        }).done(function(data, textStatus, jqXHR) {
            $('#like-count').text(data.like);
            $('#dislike-count').text(data.dislike);
            $vote.removeClass('is-liked is-disliked');

            if(data.success) {
                if(value == 1){
                    $vote.addClass('is-liked');
                }
    
                else {
                    $vote.addClass('is-disliked');
                }
            }

            let ratio = Math.round(100 * (data.like / (parseInt(data.dislike) + parseInt(data.like))));
            $('.vote-progress').css('width', ratio + '%');
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert(jqXHR.responseText);
        }).always(function() {
            $('.vote-loading').hide();
            $('.vote-logos').fadeIn();
        });
    }

})