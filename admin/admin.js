function display_index() {
  $('div').each(function (index, value) {
    console.log(`div${index}: ${this.id}`);
  });
}

function toggle_user_ban(user_id) {
  alert("Banning user " + user_id);

  console.log("banning user " + user_id + ".");

  $.ajax({
    url: 'admin_handler.php',
    type: 'post',
    data: {
      'toggle_user_ban_id': user_id
    },
    success: function (response) {
      console.log(response);
        if(response == "success") {
          // Do success stuff
        } else {
          // Failure
        }
    }
});

}