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

      case "editroom":
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#room_img img').attr('src', e.target.result);
        };

        $("#room_img_input").change(function() {
          var input = $(this)[0];
          
          if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
          }

          hideSwitch($("#room_img i"), $("#room_img img"), input.files[0]);
        });

        $("#room_form").submit(function(e) {
          e.preventDefault();

          var formData = new FormData(this);
          createRoom(formData);
        });

        if(room_id !== undefined) {
          updateRoomForm(room_id);

          $("#room_form [type=submit]").text("Uppdatera hotellrum");
        }
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

function updateRoomFormImg(path) {
  
}

function newAlert(alert, isError, message) {
  alert.addClass(isError ? "alert-danger" : "alert-success");
  alert.removeClass(isError ? "alert-success" : "alert-danger");
  var check = alert.find(".check");
  var error = alert.find(".error");

  hideSwitch(error, check, isError);
  
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

function deleteRoom(id) {
  apiRequest("POST", "deleteRoom", {room_id: id}, function(response) {
    getRoomList();
  });
}

function changeRoom(id) {
  location.href = "?editroom&room_id="+id;
}

function updateRoomForm(id) {
  apiRequest("GET", "getSingleRoom", {room_id: id}, function(response) {
    console.log(response);
    var form = $("#room_form");
    var obj = response.body;
    form.find("[name=number]").val(obj.number);
    form.find("[name=level]").val(obj.level);
    form.find("[name=department]").val(obj.department);
    form.find("[name=description]").text(obj.description);
    form.find("img").attr("src", obj.image.thumb);
  });
}

function getRoomList() {
  apiRequest("GET", "getRooms", {}, function(response) {
    var cleanedList = $("#cleaned_rooms table tbody");
    var uncleanedList = $("#uncleaned_rooms table tbody");
    cleanedList.empty();
    uncleanedList.empty();

    $.each(response.body, function(i, obj){
      var id = obj.id;
      
      var tr = $("<tr data-id='"+id+"'></tr>");

      tr.append("<td><img src='"+obj.image.thumb+"'</td>")
      tr.append("<td>"+obj.level+obj.department+obj.number+"</td>");
      tr.append("<td>"+obj.description+"</td>");

      var td = $("<td></td>");

      
      if(obj.cleaned) {
        td.append(createButton("clean", "check", id));
      } else {
        td.append(createButton("unclean", "remove", id));
      }

      if(priv) {
        td.append(createButton("change", "edit", id));
        td.append(createButton("delete", "trash", id));
      }
      
      tr.append(td);
      
      if(obj.cleaned > 0) {
        cleanedList.append(tr);
      } else {
        uncleanedList.append(tr);
      }
    });

    hideSwitch($("#cleaned_rooms .room-table"), $("#cleaned_rooms .no-list-text"), cleanedList.children().length != 0);
    hideSwitch($("#uncleaned_rooms .room-table"), $("#uncleaned_rooms .no-list-text"), uncleanedList.children().length != 0);

  });
}

function createButton(action, icon, id) {
  var button = $("<button class='btn btn-primary "+action+"'><i class='fa fa-"+icon+"'></i></button>");
  button.click(function() {
    switch(action){
      case "delete":
        deleteRoom(id);
      break;
      case "change":
        changeRoom(id);
      break;
    }
  });

  return button;
}

function hideSwitch(show, hide, bool) {
  if(bool) {
    show.show();
    hide.hide();
  } else {
    hide.show();
    show.hide();
  }
}