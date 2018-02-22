
$("#status").change(function() {
  var selected_item = $(this).val()
  if(selected_item == "2") {
    $("#loaners").val("").show();
  }
  else {
    $("#loaners").val("").hide();
    $("#new_loaner").val("").hide();
  }
}
);

$("#loaners").change(function() {
  var selected_item = $(this).val()
  if(selected_item == "add_loaner" && $("#status").val() == "2") {
    $("#new_loaner").val("").show();
  }
  else {
    $("#new_loaner").val("").hide();
  }
}
);
