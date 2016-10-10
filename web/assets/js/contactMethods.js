var $contactMethodCollectionHolder;

// setup an "add a tag" link
var $addContactMethodLink = $('<a href="#" class="add_tag_link btn">Add method</a>');
var $newContactMethodFormDiv = $('<div></div>').append($addContactMethodLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $contactMethodCollectionHolder = $('#contactMehtods');

    // add the "add a tag" anchor and li to the tags ul
    $contactMethodCollectionHolder.append($newContactMethodFormDiv);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $contactMethodCollectionHolder.data('index', $contactMethodCollectionHolder.find(':input').length);

    $addContactMethodLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addContactMethodForm($contactMethodCollectionHolder, $newContactMethodFormDiv);
    });
});

function addContactMethodForm($contactMethodCollectionHolder, $newContactMethodFormDiv) {
    // Get the data-prototype explained earlier
    var prototype = $contactMethodCollectionHolder.data('prototype');

    // get the new index
    var index = $contactMethodCollectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $contactMethodCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add an address" link li
    var $newFormLi = $('<div></div>')
        .addClass('contactMethod contactMethod-' + index)
        .append(newForm);
    $newContactMethodFormDiv.before($newFormLi);
}
