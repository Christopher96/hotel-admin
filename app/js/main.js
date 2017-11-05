/*
  Created by: Christopher Gauffin
  Description: Core functionality for hotel admin, contains functions for API requests and list generation of users and rooms
*/

// This function fires when the document is loaded and checks for which page it is
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

          hideSwitch($("#room_img .lightbox-img"), $("#room_img i"), input.files[0]);
        });

        if(room_id) {
          updateRoomForm(room_id);
        }

        $("#room_form").submit(function(e) {
          e.preventDefault();

          var formData = new FormData(this);
          
          if(room_id) {
            formData.append("room_id", room_id);
            updateRoom(room_id, formData);
          } else {
            createRoom(formData);
          }
        });

      break;

      case "rooms":
        getRoomList();
      break;
    }
});

// Helper function for making API calls
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

// Generates alerts with a custom message
function newAlert(container, isError, message) {
  var alert = $(
    '<div class="alert" role="alert">'+
      '<i class="fa fa-exclamation-circle error"></i>  '+
      '<i class="fa fa-check-circle check"></i>  '+
      '<span class="message"></span>'+
    '</div>'
  );

  $("#"+container).append(alert);

  alert.addClass(isError ? "alert-danger" : "alert-success");
  alert.removeClass(isError ? "alert-success" : "alert-danger");
  var check = alert.find(".check");
  var error = alert.find(".error");

  hideSwitch(error, check, isError);
  
  alert.find(".message").text(message);
  alert.fadeIn(300);

  alert.unbind("click").click(function() {
    alert.fadeOut(300);
  });

  setInterval(function() {
    alert.fadeOut(300);
  }, 5000);

}
  
// Generates a list of all the users
function getUserList(){

  var userList = $("#user_table table tbody");
  userList.empty();

  apiRequest("GET", "getUsers", {}, function(response){
      $.each(response.body, function(i, obj){
        if(obj.id == auth.user_id) return;

        var tr = $("<tr data-id='"+obj.id+"'></tr>");

        tr.append("<td><div><span class='label'>Namn:</span><span class='info'>"+obj.name+"</span></div></td>");
        tr.append("<td><div><span class='label'>Användarnamn:</span><span class='info'>"+obj.username+"</span></div></td>");

        var role = "Städare";
        if(obj.role == 1) role = "Administratör";

        tr.append("<td><div><span class='label'>Roll:</span><span class='info'>"+role+"</span></div></td>");
        tr.append("<td><div><span class='label'>Registrerades:</span><span class='info'>"+obj.timestamp+"</span></div></td>");

        var td = $("<td></td>");
        var div = $("<div></div>");
        var button = createButton("delete-user", "trash", obj.id);
        div.append(button);
        td.append(div);
        tr.append(td);
        
        userList.append(tr);
      });

      hideSwitch($("#user_table table"), $("#user_table .no-list-text"), userList.children().length > 0);
  });

  
}

function deleteUser(user_id) {
  apiRequest("POST", "deleteUser", {target_id: user_id}, function(response) {
    console.log(response);
    if(response.success) {
      newAlert("user_list_alert", true, response.message);
      getUserList();
    }
  });
}

function createUser(formData) {
  apiRequest("POST", "createUser", formData, function(response) {
    if(response.success) {
      newAlert("user_form_alert", false, response.message);
      getUserList();
    } else {
      newAlert("user_form_alert", true, response.message);
    }
  });
}


function createRoom(formData) {
  apiRequest("POST", "createRoom", formData, function(response) {
    console.log(response);
    if(response.success) {
      newAlert("edit_alert", false, response.message);
    } else {
      newAlert("edit_alert", true, response.message);
    }
  });
}

function updateRoom(id, formData) {
  apiRequest("POST", "updateRoom", formData, function(response) {
    console.log(response);
    if(response.success) {
      newAlert("edit_alert", false, response.message);
    } else {
      newAlert("edit_alert", true, response.message);
    }
  });
}

function changeRoomStatus(id, status) {
  apiRequest("POST", "changeRoomStatus", {room_id: id, status: status}, function(response) {
    console.log(response);
    if(response.success) {
      getRoomList();
      newAlert((status != 1) ? "unclean_alert" : "clean_alert", status != 1, response.message);
    }
  });
}

function deleteRoom(id) {
  var alert = ($("#cleaned_rooms tr[data-id="+id+"]").length == 0) ? "unclean_alert" : "clean_alert";

  apiRequest("POST", "deleteRoom", {room_id: id}, function(response) {
    if(response.success) {
      getRoomList();
      newAlert(alert, true, response.message);
      
    } else {
      newAlert(alert, true, response.message);
    }
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
    form.find("[name=status]").val(obj.status);
    form.find("[name=description]").text(obj.description);
    form.find("[type=submit]").text("Uppdatera hotellrum");
    
    var lightbox = form.find(".lightbox-img");
    lightbox.data("title", obj.image.name);
    lightbox.attr("href", obj.image.full);
    lightbox.find("img").attr("src", obj.image.thumb);

    $(".title span").text("Uppdatera rum");
    $(".title .code").text(" #"+obj.code);
    hideSwitch($("#room_img .lightbox-img"), $("#room_img i"), true);
  });
}

// Generates a list of all the rooms
function getRoomList() {
  apiRequest("GET", "getRooms", {}, function(response) {
    var cleanedList = $("#cleaned_rooms table tbody");
    var uncleanedList = $("#uncleaned_rooms table tbody");
    cleanedList.empty();
    uncleanedList.empty();

    $.each(response.body, function(i, obj){
      var id = obj.id;
      
      var tr = $("<tr data-id='"+id+"'></tr>");

      var td = $(
        "<td>"+
          "<a href='"+obj.image.full+"' data-lightbox='images' data-title='"+obj.image.name+"'>"+ 
            "<img src='"+obj.image.thumb+"' class='img-thumbnail'>"+
          "</a>"+
        "</td>"
      );
      tr.append(td);

      tr.append("<td>#"+obj.code+"</td>");
      tr.append("<td>"+obj.description+"</td>");

      var td = $("<td></td>");
      var div = $("<div></div>");
      
      if(obj.status == 1) {
        div.append(createButton("unclean-room", "remove", id));
      } else {
        div.append(createButton("clean-room", "check", id));
      }

      if(priv) {
        div.append(createButton("change-room", "edit", id));
        div.append(createButton("delete-room", "trash", id));
      }

      td.append(div);
      tr.append(td);
      
      if(obj.status == 1) {
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
  var button = $("<button class='btn btn-primary "+action+" edit-btn'><i class='fa fa-"+icon+"'></i></button>");
  button.click(function() {
    switch(action){
      case "delete-room":
        deleteRoom(id);
      break;
      case "change-room":
        changeRoom(id);
      break;
      case "clean-room":
        changeRoomStatus(id, 1);
      break;
      case "unclean-room":
        changeRoomStatus(id, 0);
      break;
      case "delete-user":
        deleteUser(id);
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