// setup an "add a address" link
var $addActivityLink = $('<a href="#" class="btn btn-success">Add Activity</a>');
var $newActivityLinkLi = $('<li></li>').append($addActivityLink);


//######################################################################
jQuery(document).ready(function () {
    // Get the ul that holds the collection of Activities
    var $collectionHolder = $('ul.Activities');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function () {
        addFormDeleteLink($(this));
    });

    // add the "add a address" anchor and li to the Activities ul
    $collectionHolder.append($newActivityLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addActivityLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new address form (see code block below)
        addForm($collectionHolder, $newActivityLinkLi);
    });


});





function addForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<li></li>');

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="pull-right btn btn-sm btn-danger remove">x</a>');

    // Display the form in the page in an li, before the "Add Activity" link li
    $newFormLi.append(newForm);
    $newFormLi.append('<div class="col-sm-12"><div class="space"></div></div>');


    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });

//    addFormDeleteLink($newFormLi);
}


function addFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#" class="btn btn-danger">delete</a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}


