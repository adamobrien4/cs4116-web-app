$(() => {
    console.log("ready");

    moment.tz.setDefault("Dublin/Europe");

    $('#send-chat-button').click(() => {
        console.log('Clicked');

        var message = $('#send-chat-field').val();

        if (current_active_chat == -1) {
            alert('Please select a chat to send this message into first.');
        } else {
            if (message.length > 0) {
                $.ajax('chat_handler.php', {
                    type: 'POST',
                    data: {
                        'message': message,
                        'chat_id': current_active_chat
                    },
                    success: (data, status, xhr) => {
                        if(data == "success"){
                            $('#send-chat-field').val('');
                            getMessages(current_active_chat, current_active_chat);
                        } else {
                            console.log(data);
                            alert(data + " fix this");
                        }
                    },
                    error: (xhr, status, e) => {
                        alert("There was an error. Please try sending the chat again.");
                    }
                });
            } else {
                alert("Message is empty");
            }
        }
    });
});

function getMessages(chat_id) {
    // Update to let the page know which chat the user is currently interacting with
    current_active_chat = chat_id;
    $.ajax('chat_handler.php', {
        type: 'POST',
        data: {
            'chat_id_request': chat_id,
            'user_type': chats[chat_id]['you_are_user']
        },
        success: (data, status, xhr) => {
            console.log(data);

            try {
                displayMessages(JSON.parse(data), chat_id);
            } catch (err) {
                console.log(err);
            }
        },
        error: (xhr, status, e) => {
            alert("There was an error. Please try updating your interests again.");
        }
    });
}

function displayMessages(data, index) {
    var mBox = $('#chat-box');
    mBox.empty();

    jQuery.each(data, function (i, val) {
        var cls = "btn-outline-primary";

        var element = "";
        var messageWhen = moment(val.timestamp, "YYYY-MM-DD hh:ii:ss").from(moment(new Date()));

        if (val.user_id == chats[index].other_user_id) {
            // This message is from the other user
            cls = "btn-outline-danger";

            if (chats[index].you_are_user == "A") {
                if (val.timestamp > chats[index].A_last_viewed) {
                    // This is a new unread message
                    cls = "btn-success";
                }
            } else {
                if (val.timestamp > chats[index].B_last_viewed) {
                    // This is a new unread message
                    cls = "btn-success";
                }
            }

            element = `
                <div class="incoming_msg">
                    <div class="received_msg">
                    <div class="received_withd_msg">
                        <p>${val.message}</p>
                        <span class="time_date">${messageWhen}</span></div>
                    </div>
                </div>
            `;


        } else {
            // This message is from the curent user
            element = `
                <div class="outgoing_msg">
                    <div class="sent_msg">
                    <p>${val.message}</p>
                    <span class="time_date">${messageWhen}</span> </div>
                </div>
            `;


        }


        mBox.append(element);
    });

    mBox.animate({ scrollTop:mBox.prop("scrollHeight")}, 500);
}