$( function() {
    // Turn checkboxes into JQueryUI elements
    $('input[type=checkbox]').checkboxradio();
    //$('.collapse').collapse();

    // Enable sortable interests list
    var interestsList = document.getElementById('interests-list');
    new Sortable(interestsList, {
        animation: 150,
        ghostClass: 'blue-background-class'
    });

    // Populate available interests grid
    jQuery.each(available_interests, function(index, el) {
        $('#addInterestsMenu').append("<div class='grid-square' data-toggle='tooltip' data-placement='right' title='"+ el.name +"' onclick='addInterest("+ index +")'><span class='material-icons'>"+ el.icon +"</span></div>");
    });
    $('[data-toggle="tooltip"]').tooltip()


    populate_user_interests_list();

    populate_data_fields();
});

function populate_user_interests_list() {
    $('#interests-list').empty();
    jQuery.each(user_interests, function(i, val) {
        var interest = available_interests[val];
        $('#interests-list').append("<li class='list-group-item' id='interest-item-"+interest.icon+"'><div class='input-group'><div class='input-group-prepend'><span class='material-icons btn btn-outline-dark'>"+ interest.icon +"</span></div><span class='form-control'>"+ interest.name +"</span><div class='input-group-append'><button class='btn btn-outline-danger material-icons' type='button' onclick='removeInterest("+ val +")'>delete</button></div></div></li>")
    });
}

function populate_data_fields() {
    // Populate form with users data
    $('#firstname').val(user_profile_data.firstname);
    $('#lastname').val(user_profile_data.lastname);
    $('#age').val(user_profile_data.age);
    $('#description').val(user_profile_data.description);

    if(user_profile_data.gender == "male") {
        $('#gender-m').prop('checked', true);
    } else if(user_profile_data.gender == "female") {
        $('#gender-f').prop('checked', true);
    }

    if(user_profile_data.seeking == "male") {
        $('#seeking-m').prop('checked', true);
    } else if(user_profile_data.seeking == "female") {
        $('#seeking-f').prop('checked', true);
    }
}

function show_updated_notification() {
    alert("Your account has been updated.");
}

function addInterest(interest_id) {
    user_interests.push(interest_id);
    populate_user_interests_list();
}

function removeInterest(interest_id) {
    // TODO : Fix bug that is deleting the incorrect value from the user_interests array
    jQuery.each(user_interests, function(i, val) {
        if(val == interest_id) {
            user_interests.splice(i,1);
        }
    });
    populate_user_interests_list();
}