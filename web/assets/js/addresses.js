var $addressCollectionHolder;

// setup an "add a tag" link
var $addAddressLink = $('<a href="#" class="add_tag_link btn">Add an address</a>');
var $newAddressFormDiv = $('<div></div>').append($addAddressLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $addressCollectionHolder = $('#addresses');

    // add the "add a tag" anchor and li to the tags ul
    $addressCollectionHolder.append($newAddressFormDiv);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $addressCollectionHolder.data('index', $addressCollectionHolder.find(':input').length);

    $addAddressLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addAddressForm($addressCollectionHolder, $newAddressFormDiv);
    });
});

function addAddressForm($addressCollectionHolder, $newAddressFormDiv) {
    // Get the data-prototype explained earlier
    var prototype = $addressCollectionHolder.data('prototype');

    // get the new index
    var index = $addressCollectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $addressCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add an address" link li
    var $newFormLi = $('<div></div>')
        .addClass('address address-' + index)
        .append(newForm);
    $newAddressFormDiv.before($newFormLi);
}
