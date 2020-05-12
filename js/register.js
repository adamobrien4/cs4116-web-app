$(() => {
    if(user_data.length > 0){
        $('#results-list').empty();
    }
    jQuery.each(user_data, (index, val) => {
        $('#results-list').append(`
            <div class="text-center">
                <img class="card-img-top" style="width: 300px" src="./assets/uploads/${val.user_id}.jpg" alt="User profile image">
                <div class="card-body">
                    <h5 class="card-title">${val.firstname}</h5>
                    <p class="card-text">Register to find out more!</p>
                </div>
            </div>
        `);
    });
});