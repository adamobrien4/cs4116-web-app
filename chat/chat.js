var checkMessageInterval;
var mostRecentMessageTimestamp;

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
                        if (data == "success") {
                            $('#send-chat-field').val('');
                            getMessages(current_active_chat, current_active_chat);
                        } else {
                            console.log(data);
                            $('#send-chat-button').effect("shake");
                        }
                    },
                    error: (xhr, status, e) => {
                        $('#send-chat-button').effect("shake");
                    }
                });
            } else {
                $("#send-chat-field").effect("shake");
            }
        }
    });
});

function getMessages(chat_id) {

    clearInterval(checkMessageInterval);

    $('#chat-box').empty();
    $('#chat-box').html("<img src='https://static.collectui.com/shots/3678774/dash-loader-large' style='width:100%;'/>");
    // Update to let the page know which chat the user is currently interacting with
    current_active_chat = chat_id;
    $.ajax('chat_handler.php', {
        type: 'POST',
        data: {
            'chat_id_request': chat_id,
            'user_type': chats[chat_id]['you_are_user']
        },
        success: (data, status, xhr) => {
            try {
                displayMessages(JSON.parse(data), chat_id);
            } catch (err) {
                console.log(err);
                $("#chat_" + chat_id).effect("shake");
                $("#chat-box").empty();
                $("#chat-box").html("<img src='https://img.icons8.com/clouds/100/000000/error.png' width='200px;'/>");
            }
        },
        error: (xhr, status, e) => {
            $("#chat_" + chat_id).effect("shake");
            $("#chat-box").empty();
            $("#chat-box").html("<img src='https://img.icons8.com/clouds/100/000000/error.png' width='200px;'/>");
        }
    });
}

function displayMessages(data, index) {
    var mBox = $('#chat-box');
    mBox.empty();

    jQuery.each(data, function (i, val) {
        var cls = "";

        var element = "";
        var messageWhen = moment(val.timestamp, "YYYY-MM-DD hh:ii:ss").from(moment(new Date()));
        mostRecentMessageTimestamp = val.timestamp;

        if (val.user_id == chats[index].other_user_id) {
            // This message is from the other user

            if (chats[index].you_are_user == "A") {
                if (val.timestamp >= chats[index].A_last_viewed) {
                    // This is a new unread message
                    cls = "<sup><span class='badge badge-success'>1</span></sup>";
                }
            } else {
                if (val.timestamp >= chats[index].B_last_viewed) {
                    // This is a new unread message
                    cls = "<sup><span class='badge badge-success'>1</span></sup>";
                }
            }

            element = `
                <div class="incoming_msg">
                    <div class="received_msg">
                    <div class="received_withd_msg">
                        <p>${val.message}</p>
                        ${cls}
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
                    ${cls}
                    <span class="time_date">${messageWhen}</span> </div>
                </div>
            `;


        }


        mBox.append(element);
    });

    mBox.animate({ scrollTop: mBox.prop("scrollHeight") }, 500);

    checkMessageInterval = setInterval(checkForNewMessages, 4000);
}

function checkForNewMessages(){
    console.log("Checking for new messages on chat " + current_active_chat + " -> " + mostRecentMessageTimestamp);
    $.ajax('chat_handler.php', {
        type: 'POST',
        data: {
            'chat_id_request': current_active_chat,
            'messageTimestamp': mostRecentMessageTimestamp,
            'user_type': chats[current_active_chat]['you_are_user']
        },
        success: (data, status, xhr) => {
            if(data == "no_new"){
                console.log('No new messages');
            } else {
                try {
                    displayMessages(JSON.parse(data), current_active_chat);
                } catch (err) {
                    console.log(err);
                }
            }
            
        }
    });
}