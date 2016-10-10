var $permitCollectionHolder;

// setup an "add a tag" link
var $addPermitLink = $('<a href="#" class="add_tag_link btn">Add a permit</a>');
var $newPermitDiv = $('<div></div>').append($addPermitLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $permitCollectionHolder = $('#permits');

    $permitCollectionHolder.find('li').each(function() {
        addPermitFormDeleteLink($(this));
    });

    // add the "add a tag" anchor and li to the tags ul
    $permitCollectionHolder.append($newPermitDiv);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $permitCollectionHolder.data('index', $permitCollectionHolder.find(':input').length);

    $addPermitLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addPermitForm($permitCollectionHolder, $newPermitDiv);
    });
});

function addPermitForm($permitCollectionHolder, $newPermitDiv) {
    // Get the data-prototype explained earlier
    var prototype = $permitCollectionHolder.data('prototype');

    // get the new index
    var index = $permitCollectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $permitCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add an address" link li
    var $newPermitFormDiv = $('<div></div>')
        .addClass('row permit-detail permit-detail-' + index)
        .append(newForm);
    $newPermitDiv.before($newPermitFormDiv);

    addPermitFormDeleteLink($newPermitDiv);
}

function addPermitFormDeleteLink($permitFormDiv) {
    var $removeFormPermit = $('<a href="#" class="btn">delete this permit</a>');
    $permitFormDiv.append($removeFormPermit);

    $removeFormPermit.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $permitFormDiv.remove();
    });
}
