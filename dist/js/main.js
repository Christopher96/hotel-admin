/*
  Created by: Christopher Gauffin
  Description: Core functionality for blog, contains function for API requests and post generation
*/

$(document).ready(function () {
  if (active) {
    $("#menu li.active").removeClass("active");
    $("#menu a[href='?" + active + "']").parent("li").addClass("active");
  }
});

function apiRequest(method, action, params, callback = null) {

  var isFormData = params.constructor.name === "FormData";

  if (isFormData) {
    params.append("action", action);
  } else {
    params.action = action;
  }

  if (typeof auth.user_id !== "undefined" && typeof auth.user_id !== "undefined") {
    if (isFormData) {
      params.append("user_id", auth.user_id);
      params.append("session_id", auth.session_id);
    } else {
      params.user_id = auth.user_id;
      params.session_id = auth.session_id;
    }
  }

  $.ajax({
    type: method,
    data: params,
    url: "php/api.php",
    processData: !isFormData,
    contentType: isFormData ? false : "application/x-www-form-urlencoded",
    success: function (response) {
      try {
        if (callback != null) callback(JSON.parse(response));
      } catch (e) {
        console.log(e.message);
        console.log(response);
      }
    },
    error: function (response) {
      console.log(response);
    }
  });
}

function getUserList() {
  apiRequest("GET", "getUsers", {}, function (response) {
    $.each(response.body, function (i, obj) {
      var tr = $("<tr data-id='" + obj.id + "'>");

      tr.append("<td>" + obj.name + "</td>");
      tr.append("<td>" + obj.username + "</td>");

      var role = "Städpersonal";
      if (obj.role == 1) role = "Administratör";

      tr.append("<td>" + role + "</td>");
      tr.append("<td>" + obj.timestamp + "</td>");

      tr.append("<td><button class='btn btn-primary'><i class='fa fa-trash'></i></button></td>");

      $("#user_table tbody").append(tr);
    });
  });
}
//# sourceMappingURL=main.js.map
