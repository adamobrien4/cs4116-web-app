$(() => {
    $('#accept-request').click(()=>{
        $.ajax('home_handler.php', {
            type: 'POST',
            data: {
                'connection_id': conn_data.connection_id,
                'status': 1,
                'other_user': conn_data.other_user_id,
                'is_request': conn_data.is_request
            },
            success: (data, status, xhr) => {
                switch(data){
                    case "success_c":
                    case "success_p":
                        location.reload();
                    break;
                    default:
                        console.log(data);
                        alert("An error occurred");
                    break;
                }
            }
        });
    });

    $('#decline-request').click(()=>{
        $.ajax('home_handler.php', {
            type: 'POST',
            data: {
                'connection_id': conn_data.connection_id,
                'status': 0,
                'other_user': conn_data.other_user_id,
                'is_request': conn_data.is_request
            },
            success: (data, status, xhr) => {
                switch(data){
                    case "success_ca":
                    case "success_pa":
                        location.reload();
                    break;
                    default:
                        console.log(data);
                        alert("An error occurred");
                    break;
                }
            }
        });
    });
});