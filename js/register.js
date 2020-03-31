$(() => {
    console.log("Ready");

    $('#results-list').empty();
    jQuery.each(user_data, (index, val) => {
        $('#results-list').append(`<li>${val}</li>`);
    });
});