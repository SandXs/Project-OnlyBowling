<?php session_start();       // Start the session
include("Header.php");

if (!isset($_SESSION['id'])) {         // condition Check: if session is not set. 
  header('location: login.php');   // if not set the user is sendback to login page.
}

//$user = Get_user_info(Fast_decrypt($_SESSION['id']));
$user = Get_user_info($_SESSION['id']);
//echo $_SESSION['id'];
//print_r($user);
if ($user['user_is_new']==0){
  echo'
  <!-- <div class="menu">
    <button class="open-button" onclick="showTickets()">Tickets</button>
    <button class="open-button" onclick="showUsers();">Users</button>
    <form action="" method="post">
      <button type="submit" name="signout" class=" btn btn-warning mb-3"> Sign Out</button>
    </form>
  </div> -->
  <!-- <div>
  <input id="Reservatie_search" type="text" placeholder="Search" name="Reservatie_search" required>
  </div> -->
  <div style="z-index: 1;position:absolute;height: 100%;
  width: 100%;">
    <form action="" method="post">
      <button type="submit" name="signout" class=" btn btn-warning mb-3"> Sign Out</button>
    </form>
    <div id="tickets" class="container col-12 border rounded mt-3">
      <h1 class=" mt-3 text-center">Welcome, this is your dashboard!! </h1>
      <!-- A button to open the popup form -->
      <button class="open-button" onclick="openReserveringCreate()">Maak reservatie</button>
      <button class="open-button" onclick="sure_del_reservering()">Delete reservatie(s)</button>
      <h2>'.((($GLOBALS['user']['user_group_id'] == 1)&&( $GLOBALS['user']['user_group_id'] == 2)) ? "All active tickets" : "My tickets").'</h2>
      <table id="reservationlist" class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <td>'.(($GLOBALS['user']['user_group_id'] == 2) ? "<input type='checkbox' id='checkAllReservering'>" : "").'</td>
            <td>ID</td>
            <td>Priority</td>
            <td>Type</td>
            <td>Subject</td>
            <td>Content</td>
            <td>Email</td>
            <td></td>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <script>
        $(document).ready(function() {
          loadReservering();
        });
      </script>
    </div>';
    if($GLOBALS['user']['user_group_id'] == 2){
      echo'
      <div id="users" class="container col-12 border rounded mt-3">
        <button class="open-button" onclick="openUserCreate()">Create user</button>
        <button class="open-button" onclick="sure_del_user()">Delete user(s)</button>
        <h2>All users</h2>
        <table id="userslist" class="table table-striped table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <td><input type="checkbox" id="checkAllUsers"></td>
              <td>ID</td>
              <td>Name</td>
              <td>Company</td>
              <td>Email</td>
              <td></td>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <script>
      $(document).ready(function() {
        loadUsers();
      });
      </script>
      ';
    }
  echo '
  </div>
  <div class="popups" style="height:100%;width:100%;"></div>
  ';
} else {
  echo'
  <div class="" id="User_Updatepass_Dialog">
    <form method="" class="form-container">
        <h1>Verander uw wachtwoord</h1>
        <div>
            <label for="user_pass1"><b>Wachtwoord</b></label>
            <input type="password" placeholder="" name="user_pass1" required>
        </div>
        <div>
            <label for="user_pass2"><b>Herhaal wachtwoord</b></label>
            <input type="password" placeholder="" name="user_pass2" required>
        </div>
        <button type="button" onclick="confirmChangePass()" class="btn">Stel in</button>
    </form>
  </div>';
}
echo"
</body>";
?>
<script>
// console.log(validateNumber(1.5));
// function validateNumber(value){
//    return value % 0.5 == 0; 
// }
$("Reservatie_search").keydown(function(){
  var searchInput = $(this).val();
  if(searchInput.length >= 3){
    loadReservering(searchInput);
  }
});

  function closePopup() {
    $(".popups").empty();
    $("body .popups").css({ "position" : "", "z-index": "" });
    e.preventdefault
  }

  function openReserveringCreate() {
    load_popup("popup_reservering_create");
  }

  function load_popup (type_popup,functions) {
    $.post("functions/dashboard_functions.php",{ 
      function: "popups",
      type_popup: type_popup
    }).done(function(data){
      $("body .popups").empty();
      $("body .popups").last().append(data);
      $("body .popups").css({"position": "absolute","z-index": "999"});
    });
  }

  function loadReservering(searchInput) {
    $.post("functions/dashboard_functions.php",{ 
      function: "loadReservering",
      searchInput: searchInput
    }).done(function(data){
      $("#reservationlist tbody").empty();
      $("#reservationlist tbody").last().append(data);
    });
  }

  function sure_del_reservering(){
    load_popup("popup_sure_del_reservering");
  }

  //delete ticket
  function delReservering(){
    var reservering_ids = [];
    $("#reservationlist tbody tr input:checkbox").each(function(){
      var isChecked = $(this);
      if(isChecked.is(":checked")){
        reservering_ids.push(isChecked.attr("id"));
      }
    });
    $.post("functions/dashboard_functions.php",{ 
      function: "del_reservering",
      reservering_id: reservering_ids 
    }).done(function(data) {
      loadReservering();
      $("#checkAllReservering").prop( "checked", false );
    });
  }

  function createReservering(){
    $.post("functions/dashboard_functions.php",{ 
      function: "create_reservering",
      reservering_pers: $("#Reservation_Create_Dialog input[name='reservering_pers']").val(),
      reservering_time: $("#Reservation_Create_Dialog input[name='reservering_time']").val(),
      reservering_date: $("#Reservation_Create_Dialog input[name='reservering_date']").val()
    }).done(function(data) {
      $("#Reservation_Create_Dialog input[name='reservering_pers']").val("");
      $("#Reservation_Create_Dialog input[name='reservering_time']").val("");
      $("#Reservation_Create_Dialog input[name='reservering_date']").val("");
      closePopup();
      loadReservering();
    });
  }

  function save_edited_ticket(){
    if(($("#Reservation_Edit_Dialog input[name='reservering_time']").val() % 0.5 == 0)&&($("#Reservation_Edit_Dialog input[name='reservering_pers']").val() >= 1 && $("#Reservation_Edit_Dialog input[name='reservering_pers']").val() <= 10)){
      $.post("functions/dashboard_functions.php",{ 
        function: "save_edited_ticket",
        reservering_id: $("#Reservation_Edit_Dialog input[name='reservering_id']").val(),
        reservering_pers: $("#Reservation_Edit_Dialog input[name='reservering_pers']").val(),
        reservering_time: $("#Reservation_Edit_Dialog input[name='reservering_time']").val(),
        reservering_firstname: $("#Reservation_Edit_Dialog input[name='reservering_firstname']").val(),
        reservering_lastname: $("#Reservation_Edit_Dialog input[name='reservering_lastname']").val(),
        reservering_email: $("#Reservation_Edit_Dialog input[name='reservering_email']").val(),
        reservering_date: $("#Reservation_Edit_Dialog input[name='reservering_date']").val(),
        reservering_country: $("#Reservation_Edit_Dialog select[name='reservering_country']").val(),
        reservering_adress: $("#Reservation_Edit_Dialog input[name='reservering_adress']").val(),
        reservering_housenum: $("#Reservation_Edit_Dialog input[name='reservering_housenum']").val(),
        reservering_place: $("#Reservation_Edit_Dialog input[name='reservering_place']").val()
      }).done(function(data) {
        $("#Reservation_Edit_Dialog input[name='reservering_id']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_pers']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_time']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_firstname']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_lastname']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_email']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_date']").val("");
        $("#Reservation_Edit_Dialog select[name='reservering_country']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_adress']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_housenum']").val("");
        $("#Reservation_Edit_Dialog input[name='reservering_place']").val("");
        closePopup();
        loadReservering();
      });
    }
  }

  //edit ticket
  $("#reservationlist").on("click","tbody tr", function(){
    $.post("functions/dashboard_functions.php",{ 
      function: "popups",
      type_popup: "popup_reservering_edit",
      reservering_id: $(this).data("reservering_id")
    }).done(function(data){
      $("body .popups").empty();
      $("body .popups").last().append(data);
      $("body .popups").css({"position": "absolute","z-index": "999"});
    });
  });

  function loadUsers() {
    $.post("functions/dashboard_functions.php",{ 
      function: "load_Users"
    }).done(function(data){
      $("#userslist tbody").empty();
      $("#userslist tbody").last().append(data);
    });
  }

  function openUserCreate() {
    load_popup("popup_user_create");
  }

  function sure_del_user(){
    load_popup("popup_sure_del_user");
  }

  //delete users
  function delUsers(){
    var user_ids = [];
    $("#userslist tbody tr input:checkbox").each(function(){
      var isChecked = $(this);
      if(isChecked.is(":checked")){
        user_ids.push(isChecked.attr("id"));
      }
    });
    $.post("functions/dashboard_functions.php",{ 
      function: "del_user",
      user_id: user_ids 
    }).done(function(data) {
      loadUsers();
      $("#checkAllUsers").prop( "checked", false );
    });
  }

  function createUser(){
    $.post("functions/dashboard_functions.php",{ 
      function: "create_user",
      user_email: $("#User_Create_Dialog input[name='user_email']").val(),
      user_firstname: $("#User_Create_Dialog input[name='user_firstname']").val(),
      user_lastname: $("#User_Create_Dialog input[name='user_lastname']").val(),
      user_company: $("#User_Create_Dialog input[name='user_company']").val(),
      user_group_id: $("#User_Create_Dialog input[name='user_group_id']:checked").length
    }).done(function(data) {
      $("#User_Create_Dialog input[name='user_email']").val("");
      $("#User_Create_Dialog input[name='user_firstname']").val("");
      $("#User_Create_Dialog input[name='user_lastname']").val("");
      $("#User_Create_Dialog input[name='user_company']").val("");
      $("#User_Create_Dialog input[name='user_group_id']").val("");
      closePopup();
      loadUsers();
    });
  }

  function saveEditedUser(){
    $.post("functions/dashboard_functions.php",{ 
      function: "save_edit_user",
      user_email: $("#User_Create_Dialog input[name='user_email']").val(),
      user_firstname: $("#User_Create_Dialog input[name='user_firstname']").val(),
      user_lastname: $("#User_Create_Dialog input[name='user_lastname']").val(),
      user_company: $("#User_Create_Dialog input[name='user_company']").val(),
      user_group_id: $("#User_Create_Dialog input[name='user_group_id']").val()
    }).done(function(data) {
      $("#User_Create_Dialog input[name='user_email']").val("");
      $("#User_Create_Dialog input[name='user_firstname']").val("");
      $("#User_Create_Dialog input[name='user_lastname']").val("");
      $("#User_Create_Dialog input[name='user_company']").val("");
      $("#User_Create_Dialog input[name='user_group_id']").val("");
      closePopup();
      loadUsers();
    });
  }

  //edit users
  $("#userslist").on("click","tbody tr", function(){
    $.post("functions/dashboard_functions.php",{ 
      function: "popups",
      type_popup: "popup_user_edit",
      user_id: $(this).data("user_id")
    }).done(function(data){
      $("body .popups").empty();
      $("body .popups").last().append(data);
      $("body .popups").css({"position": "absolute","z-index": "999"});
    });
  });

  function confirmChangePass(){
    firstInput = $("#User_Updatepass_Dialog input[name='user_pass1']").val();
    secondInput = $("#User_Updatepass_Dialog input[name='user_pass2']").val();
    if(firstInput !== "" && secondInput !== ""){
      if (firstInput === secondInput) {
        $.post("functions/dashboard_functions.php",{
          function: "savePassword",
          password: firstInput
        }).done(function(data){
          window.location.reload();
          //console.log(data);

        });
      }
    }
  }

  function getIp(callback) {
    fetch('https://ipinfo.io/json?token=<your token>', { headers: { 'Accept': 'application/json' }})
      .then((resp) => resp.json())
      .catch(() => {
        return {
          country: 'nl',
        };
      })
      .then((resp) => callback(resp.country));
  }
</script>
<?php
if (isset($_POST['signout'])) {
  session_destroy();            //  destroys session 
  header('location: index.php');
}
?>