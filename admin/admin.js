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
      } else {
        $('#user-deleted-' + user_id).prop('checked', false);
      }
    }
  });
}

function update_user(user_id) {

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
        // Do success stuff
      } else {
        
      }
    }
  });
}