$(() => {
    console.log('Ready');
    jQuery.each(connections, function (i, val) {
        var user = val.user;
        console.log(user);
        $('#card-user-title').text( user.firstname + " " + user.lastname + ", " + user.age );
        $('#user-bio').text( user.description );

        jQuery.each(user.interests, (interest_index, interest) => {
            $('#interests-row').append(`
                <div class="col-sm">
                    <i class="fa ${interest.icon}"></i>
                    <p>${interest.name}</p>
                </div>
            `);
        });

        jQuery.each(user.traits, (trait_index, trait) => {
            $('#traits-row').append(`
                <div class="col-sm">
                    <i class="fa ${trait.icon}"></i>
                    <p>${trait.name}</p>
                </div>
            `);
        });
    });

    $('#accept-request').click(()=>{
        $.ajax('home_handler.php', {
            type: 'POST',
            data: {
                'connection_id': connections[0].connection_id,
                'status': 1,
                'other_user': connections[0].other_user_id
            },
            success: (data, status, xhr) => {
                //alert(data);
                console.log(data);
            }
        });
    });

    $('#decline-request').click(()=>{
        $.ajax('home_handler.php', {
            type: 'POST',
            data: {
                'connection_id': connections[0].connection_id,
                'status': 0,
                'other_user': connections[0].other_user_id
            },
            success: (data, status, xhr) => {
                alert(data);
            }
        });
    });
});