function display_index() {

$('div').each(function(index, value) {
    console.log(`div${index}: ${this.id}`);
  });
}