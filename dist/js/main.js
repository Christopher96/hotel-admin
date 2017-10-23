/*
  Created by: Christopher Gauffin
  Description: Core functionality for hotel admin, contains functions for API requests and list generation of users and rooms
*/

$(document).ready(function () {
  if (active) {
    $("#menu li.active").removeClass("active");
    $("#menu a[href='?" + active + "']").parent("li").addClass("active");
  }

  switch (active) {
    case "users":
      getUserList();

      $("#user_form").submit(function (e) {
        e.preventDefault();

        var formdata = new FormData(this);
        createUser(formdata);
      });
      break;
  }
});

function newAlert(alert, isError, message) {
  alert.addClass(isError ? "alert-danger" : "alert-success");
  alert.removeClass(isError ? "alert-success" : "alert-danger");
  var check = alert.find(".check");
  var error = alert.find(".error");

  if (isError) {
    error.show();
    check.hide();
  } else {
    error.hide();
    check.show();
  }

  alert.find(".message").text(message);
  alert.fadeIn(300);

  alert.unbind("click").click(function () {
    alert.fadeOut(300);
  });
}

function apiRequest(method, action, params, callback = null) {

  var isFormData = params.constructor.name === "FormData";

  if (isFormData) {
    params.append("action", action);
  } else {
    params.action = action;
  }

  if (typeof auth.user_id !== "undefined" && typeof auth.session_id !== "undefined") {
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
  $("#user_table tbody").empty();

  apiRequest("GET", "getUsers", {}, function (response) {
    $.each(response.body, function (i, obj) {
      if (obj.id == auth.user_id) return;
      var tr = $("<tr data-id='" + obj.id + "'></tr>");

      tr.append("<td>" + obj.name + "</td>");
      tr.append("<td>" + obj.username + "</td>");

      var role = "Städpersonal";
      if (obj.role == 1) role = "Administratör";

      tr.append("<td>" + role + "</td>");
      tr.append("<td>" + obj.timestamp + "</td>");

      var button = $("<button class='btn btn-primary delete'><i class='fa fa-trash'></i></button>");
      var td = $("<td></td>");

      td.append(button);
      tr.append(td);

      $("#user_table tbody").append(tr);
    });

    $("#user_table .delete").click(function () {
      var id = $(this).parent("td").parent("tr").data("id");
      deleteUser(id);
    });
  });
}

function deleteUser(user_id) {
  console.log(user_id);
  apiRequest("POST", "deleteUser", { target_id: user_id }, function (response) {
    console.log(response);
    if (response.success) {
      newAlert($("#alert"), false, response.message);
      getUserList();
    } else {
      newAlert($("#alert"), true, response.message);
    }
  });
}

function createUser(formdata) {
  apiRequest("POST", "createUser", formdata, function (response) {
    if (response.success) {
      newAlert($("#alert"), false, response.message);
      getUserList();
    } else {
      newAlert($("#alert"), true, response.message);
    }
  });
}
//# sourceMappingURL=main.js.map
