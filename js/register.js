$(() => {
    console.log("Ready");

    $('#results-list').empty();
    jQuery.each(user_data, (index, val) => {
        $('#results-list').append(`
            <div class="card">
                <img class="card-img-top" src="#" alt="User profile image">
                <div class="card-body">
                    <h5 class="card-title">${val}</h5>
                    <p class="card-text">Register to find out more!</p>
                </div>
            </div>
        `);
    });
});