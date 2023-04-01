/**
 * Simple (ugly) code to handle the comment vote up/down
 */
var $postuler = $('.js-annonce');
$postuler.on('click', function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);

    // console.log('/user/' + $link.data('id-user') + '/candidature/' + $link.data('id-annonce'));
    // /user/{idUser}/candidature/{idAnnonce}
    $.ajax({
        url: '/user/' + $link.data('id-user') + '/candidature/' + $link.data('id-annonce'),
        method: 'POST'
    }).then(function(data) {
        alert("c'est pris en compte");
        // $container.find('.js-vote-total').text(data.votes);
    });
});