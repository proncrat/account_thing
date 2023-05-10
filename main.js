
checkboxes = document.querySelectorAll("input[type='checkbox']")
checkCookie() 
function checkCookie() {
  let first_time_sync_required = getCookie("first_time_sync_required");
  if (first_time_sync_required != "") {
    if(first_time_sync_required == 1){
      console.log("exists")
      sync_from_account()
      document.cookie = "first_time_sync_required=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";    
    };
  }else{
    set_toggle();
  };
  check_change()
};

function getCookie(cname) {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

for (var i = 0; i < checkboxes.length; i++) {
  checkboxes[i].addEventListener('click', function(event) {
    save_slider_pos()
  });
}

function check_change(){
  if(checkboxes[4].checked == true){
    document.cookie = "auto_sync=1; path=/";
  }else{
    document.cookie = "auto_sync=0; path=/";
  }
}

function save_slider_pos(){
  var settings_json = {
    "auto_next" : checkboxes[0].checked,
    "auto_play" : checkboxes[1].checked,
    "auto_skip_intro" : checkboxes[2].checked,
    "auto_skip_outro" : checkboxes[3].checked,
    "auto_sync" : checkboxes[4].checked,
    "share_data" : checkboxes[5].checked,
    "custom_recom" : checkboxes[6].checked,
  };
  localStorage.setItem("settings", JSON.stringify(settings_json));
  check_change()
}

function set_toggle(){
  if ("settings" in localStorage) {
    storedNames = JSON.parse(localStorage.getItem("settings"));
    checkboxes[0].checked = storedNames.auto_next;
    checkboxes[1].checked = storedNames.auto_play;
    checkboxes[2].checked = storedNames.auto_skip_intro;
    checkboxes[3].checked = storedNames.auto_skip_outro;
    checkboxes[4].checked = storedNames.auto_sync;
    checkboxes[5].checked = storedNames.share_data;
    checkboxes[6].checked = storedNames.custom_recom;
  }else{
    var settings_json = {
        "auto_next" : true,
        "auto_play" : true,
        "auto_skip_intro" : false,
        "auto_skip_outro" : false,
        "auto_sync" : true,
        "share_data" : false,
        "custom_recom" : false,
    };
    localStorage.setItem("settings", JSON.stringify(settings_json));
    set_toggle()
  }
};

function sync_from_account(){
  $.ajax({
    type: 'POST',
    url: 'get_sync_data.php',
    dataType: "JSON",
    success: function(data) {
      checkboxes[0].checked = data.settings.auto_next;
      checkboxes[1].checked = data.settings.auto_play;
      checkboxes[2].checked = data.settings.auto_skip_intro;
      checkboxes[3].checked = data.settings.auto_skip_outro;
      checkboxes[4].checked = data.settings.auto_sync;
      checkboxes[5].checked = data.settings.share_data;
      checkboxes[6].checked = data.settings.custom_recom;
      save_slider_pos()
    },
    error: function (jqXHR, exception) {
      console.log(exception)
    }
  });
};

$("#sync_manual").click(function(){
  var sync_data = {
    "settings" : JSON.parse(localStorage.getItem("settings")),
    "watch_data": []
  };
  $.ajax({
      type: 'POST',
      url: 'sync.php',
      data:{
          sync: JSON.stringify(sync_data)
      },
      success: function(data) {
          console.log(data)
          time_sync()
      },
      error: function (jqXHR, exception) {
          alert("Contact admin, Shits broken.");
      }
  });
});

function log_out(){
  $.ajax({
    type: 'POST',
    url: 'logout.php',
    success: function() {
        console.log("Logged out")
        location.reload();
        main()
    },
    error: function (jqXHR, exception) {
        alert("Contact admin, Shits broken.");
    }
  });
};
  
time_sync()
main()
  
function time_sync(){
  $.ajax({
    type: 'POST',
    url: 'get_sync_time.php',
    success: function(data) {
        $('#sync_time').html("Synced: "+data);
    },
    error: function (jqXHR, exception) {
        $('#post').html("Contact admin, Shits broken.");
    }
  });
}


function main(){
    $(document).ready(function() {
    $.ajax({
        type: 'POST',
        url: 'get_part_name.php',
        dataType: "JSON",
        success: function(data) {
        $('#cutesy_time_thing').html(data.time_text);
        },
        error: function (jqXHR, exception) {
        alert("Contact admin, Shits broken.");
        }
    });
    $.ajax({
        type: 'POST',
        url: 'get_account_details.php',
        dataType: "JSON",
        success: function(data) {
            $('#main_username').html(data.username);
            document.getElementById("Username").value=data.username;
            document.getElementById("email").value=data.email;
            document.getElementById("joined").placeholder=data.created_at;
            document.getElementById("log_in").style.display = "none";
            document.getElementById("log_out").style.display = "block";
            $('#sync_status').html("Syncing to account");
            document.getElementById("guchi_image").src = data.image;
        },
        error: function (jqXHR, exception) {
            $('#main_username').html("Guest");
            $('#sync_status').html("Syncing to browser");
            document.getElementById("change_details").setAttribute("disabled", true);
            document.getElementById("sync_manual").setAttribute("disabled", true);
            document.getElementById("test").setAttribute("disabled", true);
            document.getElementById("log_out").style.display = "none";
            document.getElementById("log_in").style.display = "block";
            document.getElementById("guchi_image").src = "../img/guest.jpg";
        }
    });
    });
};
  

$("#test").click(function(){
    time_sync()
});





const dialog = document.getElementById("account_details")

dialog.addEventListener("click", e => {
  const dialogDimensions = dialog.getBoundingClientRect()
  if (
    e.clientX < dialogDimensions.left ||
    e.clientX > dialogDimensions.right ||
    e.clientY < dialogDimensions.top ||
    e.clientY > dialogDimensions.bottom
  ) {
    dialog.close()
  }
})

$("#change_details").click(function(){
  dialog.showModal()
});



$('form#details_form').submit(function () {
  dialog.close()
  $.ajax({
    url: 'change_account_details.php',
    type: 'POST',
    dataType: 'text',
    data: {
      username:$('#Username').val(),
      password:$('#Password').val(),
      email:$('#email').val(),
      pfp:$('#pfp').val()
    },
    success: function(php_script_response){
        console.log(php_script_response);
    }
  })
  if($('#pfp').val()){
    pfp_upload()
  };
});

function pfp_upload(){
  var file_data = $('#pfp').prop('files')[0];   
  var form_data = new FormData();                  
  form_data.append('fileToUpload', file_data);                            
  $.ajax({
    url: 'upload.php', 
    dataType: 'text',  
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,                         
    type: 'post',
    success: function(php_script_response){
      console.log(php_script_response);
      location.reload();
    }
  });
};

const dialog2 = document.getElementById("import_modal")

dialog2.addEventListener("click", e => {
  const dialogDimensions2 = dialog2.getBoundingClientRect()
  if (
    e.clientX < dialogDimensions2.left ||
    e.clientX > dialogDimensions2.right ||
    e.clientY < dialogDimensions2.top ||
    e.clientY > dialogDimensions2.bottom
  ) {
    dialog2.close()
  }
})

$("#import").click(function(){
  dialog2.showModal()
});

const dialog3 = document.getElementById("export_modal")

dialog3.addEventListener("click", e => {
  const dialogDimensions3 = dialog3.getBoundingClientRect()
  if (
    e.clientX < dialogDimensions3.left ||
    e.clientX > dialogDimensions3.right ||
    e.clientY < dialogDimensions3.top ||
    e.clientY > dialogDimensions3.bottom
  ) {
    dialog3.close()
  }
})

$("#export").click(function(){
  dialog3.showModal()
});





