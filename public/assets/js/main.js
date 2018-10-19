jQuery(document).ready(function () {

    var $collectionHolder;
    var $addCribContentButton = $('<button type="button" class="add_cribContent_link">Add new content</button>');
    var $newLinkLi = $('<li></li>').append($addCribContentButton);

    $collectionHolder = $('ul.cribContents');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addCribContentButton.on('click', function (e) {
        addCribContentForm($collectionHolder, $newLinkLi);
    });

    function addCribContentForm($collectionHolder, $newLinkLi) {

        var prototype = $collectionHolder.data('prototype');
        var index = $collectionHolder.data('index');
        var newForm = prototype;

        newForm = newForm.replace(/__name__/g, index);

        $collectionHolder.data('index', index + 1);

        var $newFormLi = $('<li></li>').append(newForm);

        $newFormLi.append('<a href="#" class="remove-cribContent">X</a>');

        $newLinkLi.before($newFormLi);

        $('.remove-cribContent').click(function (e) {
            e.preventDefault();
            $(this).parent().remove();
            return false;
        })
    }
});
