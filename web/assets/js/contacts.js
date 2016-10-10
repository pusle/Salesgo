var $contactCollectionHolder;

// setup an "add a tag" link
var $addContactLink = $('<a href="javascript:" class="add_tag_link btn">Add an action</a>');
var $newContactFormDiv = $('<div></div>').append($addContactLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $contactCollectionHolder = $('#contacts');

    // add the "add a tag" anchor and li to the tags ul
    $contactCollectionHolder.append($newContactFormDiv);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $contactCollectionHolder.data('index', $contactCollectionHolder.find(':input').length);

    $addContactLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addAddressForm($contactCollectionHolder, $newContactFormDiv);
    });
});

function addAddressForm($contactCollectionHolder, $newContactFormDiv) {
    // Get the data-prototype explained earlier
    var prototype = $contactCollectionHolder.data('prototype');

    // get the new index
    var index = $contactCollectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $contactCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add an action" link li
    var $newFormLi = $('<div></div>')
        .addClass('contact contact-' + index)
        .append(newForm);
    $newContactFormDiv.before($newFormLi);
}
