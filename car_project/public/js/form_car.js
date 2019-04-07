$(document).ready(function () {
    var $container = $('#car_keywords');

    var index = $container.find(':input').length;

    $container.find('.col-form-label').remove();

    if(index == 0) {
        addKeyword($container);
    }
    $('.addKeyword').click(function(e) {
        e.preventDefault();

        addKeyword($container);
    });

    $('.delete-image').click(function (e) {
        $('.img-upload').remove();
    });

    $('.delete-keyword').click(function (e) {
       var path = $(this).attr('data-delete-path');
       var keywordId = $(this).attr('data-keyword-id');
       var $keywordsArea = $(this).closest('.keywordsArea');

       $.ajax({
           method: "POST",
           url: path,
           data: {id:keywordId},
           success: function () {
               $keywordsArea.remove();
           },
           error: function () {
               $('.error-delete-keyword').css('display', 'block');
           }
       })
    });


    function addKeyword($container) {
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Mot clé n°'+(index + 1))
            .replace(/__name__/g, index);

        var $prototype = $(template);

        deleteButton($prototype);

        $container.append($prototype);

        index++;
    }

    function deleteButton($prototype) {
        var $deleteBtn = $('<a href="#" class="btn btn-danger">Supprimer</a>');

        $prototype.append($deleteBtn);

        $deleteBtn.click(function (e) {
            $prototype.remove();

            e.preventDefault();
            return false;
        });
    }
});