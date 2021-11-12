var $collectionHolder;

var $addNewItem = $('<div class="d-flex flex-column justify-content-center"><a href="#" class="btn-sm btn-success text-center pl-5 pr-5 mt-4">Dodaj nowy wiersz</a></div>');

$(document).ready(function () {
    // get collection
    $collectionHolder = $('#exercise_list');

    $collectionHolder.append($addNewItem);

    $collectionHolder.data('index', $collectionHolder.find('#panel').length)
    // add remove button
    $collectionHolder.find('#panel').each(function (){
    addRemoveButton($(this));
    });

    $addNewItem.click(function (e) {
        e.preventDefault();
        addNewForm();
    });

});

function addNewForm() {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collectionHolder.data('index', index+1);

    var $panel = $('<div class="panel panel-warning"><div class="panel-heading"></div></div>');

    var $panelBody = $(
        '<div class="panel-body mt-4"></div>'
        ).append(newForm);

    $panel.append($panelBody);

    addRemoveButton($panel);

    $addNewItem.before($panel);
}
// Add items


function addRemoveButton ($panel) {
    
    var $removeButton = $('<div class="d-flex flex-column justify-content-center"><a href="#" class="btn-sm btn-danger text-center pl-5 pr-5 mt-4">Usuń</a></div>');

    var $panelFooter = $('<div class="panel-footer"></div>').append($removeButton);

    $removeButton.click(function (e){
        e.preventDefault()
        $(e.target).parents('.panel').slideUp(1000, function (){
            $(this).remove();
        })
    }
    );

    $panel.append($panelFooter);
}


