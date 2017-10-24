/*
  Created by: Christopher Gauffin
  Description: Core functionality for hotel admin, contains functions for API requests and list generation of users and rooms
*/

$(document).ready(function(){
    if(active) {
      $("#menu li.active").removeClass("active");
      $("#menu a[href='?"+active+"']").parent("li").addClass("active");
    }

    switch(active) {
      case "users":
        getUserList();
        
        $("#user_form").submit(function(e) {
          e.preventDefault();

          var formData = new FormData(this);
          createUser(formData);
        });
      break;

      case "newroom":
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#room_img img').attr('src', e.target.result);
        };

        $("#room_img_input").change(function() {
          var input = $(this)[0];

          if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
            $("#room_img i").hide();
            $("#room_img img").show();
          } else {
            $("#room_img i").show();
            $("#room_img img").hide();
          }
        });

        $("#room_form").submit(function(e) {
          e.preventDefault();

          var formData = new FormData(this);
          createRoom(formData);
        });
      break;

      case "rooms":
        getRoomList();
      break;
    }
});

function apiRequest(method, action, params, callback = null){

  var isFormData = params.constructor.name === "FormData";

  if(isFormData){
    params.append("action", action);
  } else {
    params.action = action;
  }

  if(typeof auth.user_id !== "undefined" && typeof auth.session_id !== "undefined"){
    if(isFormData){
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
    url: "api.php",
    processData: !isFormData,
    contentType: isFormData ? false : "application/x-www-form-urlencoded",
    success: function(response) {
      try {
        if( callback != null ) callback(JSON.parse(response));
      } catch(e) {
        console.log(e.message);
        console.log(response);
      }
    },
    error: function(response){
      console.log(response);
    }
  });
}


function newAlert(alert, isError, message) {
  alert.addClass(isError ? "alert-danger" : "alert-success");
  alert.removeClass(isError ? "alert-success" : "alert-danger");
  var check = alert.find(".check");
  var error = alert.find(".error");

  if(isError) {
    error.show();
    check.hide();
  } else {
    error.hide();
    check.show();
  }

  alert.find(".message").text(message);
  alert.fadeIn(300);

  alert.unbind("click").click(function() {
    alert.fadeOut(300);
  })
}
  
  
function getUserList(){
  $("#user_table tbody").empty();

  apiRequest("GET", "getUsers", {}, function(response){
      $.each(response.body, function(i, obj){
          if(obj.id == auth.user_id) return;
          var tr = $("<tr data-id='"+obj.id+"'></tr>");
  
          tr.append("<td>"+obj.name+"</td>");
          tr.append("<td>"+obj.username+"</td>");

          var role = "Städpersonal";
          if(obj.role == 1) role = "Administratör";

          tr.append("<td>"+role+"</td>");
          tr.append("<td>"+obj.timestamp+"</td>");

          var button = $("<button class='btn btn-primary delete'><i class='fa fa-trash'></i></button>");
          var td = $("<td></td>");

          td.append(button);
          tr.append(td);
          
          $("#user_table tbody").append(tr);
      });

      $("#user_table .delete").click(function() {
        var id = $(this).parent("td").parent("tr").data("id");
        deleteUser(id);
      })
  });
}

function deleteUser(user_id) {
  console.log(user_id);
  apiRequest("POST", "deleteUser", {target_id: user_id}, function(response) {
    console.log(response);
    if(response.success) {
      newAlert($("#alert"), false, response.message);
      getUserList();
    } else {
      newAlert($("#alert"), true, response.message);
    }
  });
}

function createUser(formData) {
  apiRequest("POST", "createUser", formData, function(response) {
    if(response.success) {
      newAlert($("#alert"), false, response.message);
      getUserList();
    } else {
      newAlert($("#alert"), true, response.message);
    }
  });
}


function createRoom(formData) {
  apiRequest("POST", "createRoom", formData, function(response) {
    console.log(response);
    if(response.success) {
      newAlert($("#alert"), false, response.message);
    } else {
      newAlert($("#alert"), true, response.message);
    }
  });
}

function getRoomList() {
  apiRequest("GET", "getRooms", {}, function(response) {
    if(!response.success) {
      console.log(response);
      return;
    }

    var cleanedList = $("#cleaned_rooms table tbody");
    var uncleanedList = $("#uncleaned_rooms table tbody");
    cleanedList.empty();
    uncleanedList.empty();

    $.each(response.body, function(i, obj){
      var tr = $("<tr data-id='"+obj.id+"'></tr>");

      tr.append("<td><img src='"+obj.image.thumb+"'</td>")
      tr.append("<td>"+obj.level+obj.department+obj.number+"</td>");
      tr.append("<td>"+obj.description+"</td>");

      var td = $("<td></td>");

      if(obj.cleaned) {
        td.append(createButton("clean", "check"));
      } else {
        td.append(createButton("unclean", "remove"));
      }

      if(priv) {
        td.append(createButton("change", "edit"));
        td.append(createButton("delete", "trash"));
      }
      
      tr.append(td);
      
      if(obj.cleaned > 0) {
        cleanedList.append(tr);
      } else {
        uncleanedList.append(tr);
      }
    });

    if(cleanedList.length) {
      $("#cleaned_rooms .room-table").show();
      $("#cleaned_rooms .no-list-text").hide();
    } else {
      $("#cleaned_rooms .room-table").hide();
      $("#cleaned_rooms .no-list-text").show();
    }

    if(uncleanedList.length) {
      $("#uncleaned_rooms .room-table").show();
      $("#uncleaned_rooms .no-list-text").hide();
    } else {
      console.log("asdf");
      $("#uncleaned_rooms .room-table").hide();
      $("#uncleaned_rooms .no-list-text").show();
    }
  });
}

function createButton(action, icon) {
  return $("<button class='btn btn-primary "+action+"'><i class='fa fa-"+icon+"'></i></button>");
}