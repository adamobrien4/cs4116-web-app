function display_index() {
  $('div').each(function (index, value) {
    console.log(`div${index}: ${this.id}`);
  });
}

function toggle_user_ban(user_id) {
  console.log("banning user " + user_id + ".");

  $.ajax({
    url: 'admin_handler.php',
    type: 'post',
    data: {
      'toggle_user_ban_id': user_id
    },
    success: function (response) {
      console.log(response);
      if (response == "success") {
        
      } else {
        $('#user-deleted-' + user_id).prop('checked', false);
      }
    }
  });
}

function delete_user(user_id) {
  console.log("banning user " + user_id + ".");

  $.ajax({
    url: 'admin_handler.php',
    type: 'post',
    data: {
      'delete_user': user_id,
      'user_email': $('#user-email-' + user_id).text()
    },
    success: function (response) {
      console.log(response);
      if (response == "success") {
        // Do success stuff
        location.reload();
      } else {
        $('#user-deleted-' + user_id).prop('checked', false);
      }
    }
  });
}

function update_user(user_id) {

  $('#update-user-button-' + user_id).html('Submit Changes&#9;<i class="fa fa-spinner spins"></i>');

  var data = {
    'user_id': user_id,
    'firstname': $('#user-firstname-' + user_id).text(),
    'lastname': $('#user-lastname-' + user_id).text(),
    'description': $('#user-bio-' + user_id).val()
  }

  console.log(data);

  $.ajax({
    url: 'admin_handler.php',
    type: 'post',
    data: data,
    success: function (response) {
      console.log(response);
      if (response == "success") {
        $('#update-user-button-' + user_id).html('Submit Changes&#9;<i class="fa fa-check-square"></i>');
      } else {
        $('#update-user-button-' + user_id).html('Submit Changes&#9;<i class="fa fa-window-close"></i>');
      }
    }
  });
}