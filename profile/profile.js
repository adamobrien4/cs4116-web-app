$( function() {
    // Turn checkboxes into JQueryUI elements
    $('input[type=radio]').checkboxradio({
        create: function( event, ui ) {
            populate_data_fields();
        }
    });

    // Enable sortable interests list
    var interestsList = document.getElementById('interests-list');
    new Sortable(interestsList, {
        animation: 150,
        ghostClass: 'blue-background-class',
        onSort: (evt) => {
            update_user_interests_array();
        }
    });

    // Populate available interests grid
    jQuery.each(available_interests, function(index, el) {
        $('#addInterestsMenu').append(`<div class='grid-square' data-toggle='tooltip' data-placement='right' title='${el.name}' onclick='addInterest(${index})'><i class='fas ${el.icon}'></i></div>`);
    });

    // Populate available traits grid
    jQuery.each(available_traits, function(index, el) {
        $('#addTraitsMenu').append(`<div class='grid-square' data-toggle='tooltip' data-placement='right' title='${el.name}' onclick='addTrait(${index})'><i class='fas ${el.icon}'></i></div>`);
    });
    $('[data-toggle="tooltip"]').tooltip();


    populate_user_interests_list();
    populate_user_traits_list();
});

function populate_user_interests_list() {
    $('#interests-list').empty();
    jQuery.each(user_interests, function(i, val) {
        var interest = available_interests[val];
        $('#interests-list').append(`<li class='list-group-item' id='interest-item-${val}' style='cursor: grab;'><div class='input-group'><div class='input-group-prepend'></span><span class='btn btn-outline-dark fas ${interest.icon}'></span></div><span class='form-control'>${interest.name}</span><div class='input-group-append'><button class='btn btn-outline-danger' type='button' onclick='removeInterest(${val})'><i class='fas fa-trash'></i></button></div></div></li>`)
    });
}

function populate_user_traits_list() {
    $('#traits-list').empty();
    jQuery.each(user_traits, function(i, val) {
        var trait = available_traits[val];
        $('#traits-list').append(`<li class='list-group-item' id='trait-item-${val}'><div class='input-group'><div class='input-group-prepend'><span class='btn btn-outline-dark fas ${trait.icon}'></span></div><span class='form-control'>${trait.name}</span><div class='input-group-append'><button class='btn btn-outline-danger' type='button' onclick='removeTrait(${val})'><i class='fas fa-trash'></i></button></div></div></li>`)
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

function update_user_interests_array() {
    user_interests = [];
    $('#interests-list li').each((idx, li) => {
        var el = $(li);
        // Get interests id from li id
        var iid = el[0]['id'].slice(-1);

        user_interests.push(iid);
    });
}

function show_updated_notification() {
    alert("Your account has been updated.");
}

function addInterest(interest_id) {
    if( user_interests.length >= 5 ){
        alert("Only 5 interests at a time.");
        return;
    }
    for(var iid of user_interests) {
        if(iid == interest_id) {
            // Duplicate interest not allowed
            $('#interest-item-' + interest_id).effect('shake');
            return;
        }
    }

    user_interests.push(interest_id);
    populate_user_interests_list();
}

function addTrait(trait_id) {
    if( user_traits.length >= 5 ){
        alert("Only 5 traits at a time.");
        return;
    }
    for(var tid of user_traits) {
        if(tid == trait_id) {
            // Duplicate interest not allowed
            $('#trait-item-' + trait_id).effect('shake');
            return;
        }
    }

    user_traits.push(trait_id);
    populate_user_traits_list();
}

function removeInterest(interest_id) {
    jQuery.each(user_interests, function(i, val) {
        if(val == interest_id) {
            user_interests.splice(i,1);
        }
    });
    populate_user_interests_list();
}

function removeTrait(trait_id) {
    jQuery.each(user_traits, function(i, val) {
        if(val == trait_id) {
            user_traits.splice(i,1);
        }
    });
    populate_user_traits_list();
}

function submitInterests() {
    alert("Submitting Interests");

    $.ajax('profile_handler.php', {
        type: 'POST',
        data: {'interests': user_interests},
        success: (data, status, xhr) => {
            alert("Interests have been sucessfully updated!");
        },
        error: (xhr, status, e) => {
            alert("There was an error. Please try updating your interests again.");
        }
    });
}

function submitTraits() {
    alert("Submitting Traits");

    $.ajax('profile_handler.php', {
        type: 'POST',
        data: {'traits': user_traits},
        success: (data, status, xhr) => {
            alert("Traits have been sucessfully updated!");
        },
        error: (xhr, status, e) => {
            alert("There was an error. Please try updating your traits again.");
        }
    });
}