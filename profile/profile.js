$( function() {
    //$('input[type="radio"]').checkboxradio();

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
});

function show_updated_notification() {
    alert("Your account has been updated.");
}