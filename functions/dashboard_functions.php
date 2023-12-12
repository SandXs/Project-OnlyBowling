<?php session_start();       // Start the session
require("../tools.php");
//$user = Get_user_info(Fast_decrypt($_SESSION['id']));
$user = Get_user_info($_SESSION['id']);

switch($_POST['function']){
    case 'load_Tickets':
        $con = connectdb();
        if($GLOBALS['user']['user_is_admin'] == 1){
            $query = 'SELECT * FROM tickets WHERE ticket_del = 0';
            $result = mysqli_query($con, $query);
        } else {
            $query = 'SELECT * FROM tickets WHERE ticket_del = 0 AND ticket_email = "'.$GLOBALS['user']['user_email'].'"';
            $result = mysqli_query($con, $query);
        }
        $ticket_row = '';
        while($row = mysqli_fetch_array($result)){
            $ticket_row .= '
            <tr class="clickable-row" data-ticket_id="'. $row['ticket_id'] .'">
                <td><input type="checkbox" name="ticket_checkbox" id="'. $row['ticket_id'] .'"></td>
                <td>'. $row['ticket_id'] .'</td>
                <td>'. $ticket_priority_arr[$row['ticket_priority']][0] .'</td>
                <td>'. $ticket_type_arr[$row['ticket_type']] .'</td>
                <td>'. $row['ticket_subject'] .'</td>
                <td>'. $row['ticket_content'] .'</td>
                <td>'. $row['ticket_email'] .'</td>
                <td>'. (($row['ticket_response']!=='' && isset($row['ticket_response'])) ? "<i class='fa fa-envelope' aria-hidden='true'></i>
                " : "") .'</td>
            </tr>';
        }
        echo $ticket_row;
        mysqli_close($con);
        break;

    case 'create_ticket':
        $con = connectdb();
        $query = 'INSERT INTO tickets SET 
            ticket_email = "'.test_input($con,(($_POST['ticket_email'])!==""?$_POST['ticket_email']:$GLOBALS['user']['user_email'])).'",
            ticket_subject = "'.test_input($con,$_POST['ticket_subject']).'",
            ticket_type = '.intval(test_input($con,$_POST['ticket_type'])).',
            ticket_content = "'.test_input($con,$_POST['ticket_content']).'",
            ticket_priority = '.intval(test_input($con,$_POST['ticket_priority'])).',
            ticket_del = 0,
            ticket_create_date = "'.currentDate().'"';
        mysqli_query($con, $query);
        mysqli_close($con);
        break;

    case 'del_ticket':
        $con = connectdb();
        $query = 'UPDATE tickets SET ticket_del = 1 WHERE ticket_id IN ('.implode(",",$_POST['ticket_id']).')';
        mysqli_query($con, $query);
        mysqli_close($con);
        break;
    
    case 'save_edited_ticket':
        $con = connectdb();
        //echo $_POST['ticket_id'];
        $query = "UPDATE tickets SET 
            ticket_email = '".test_input($con,(($_POST['ticket_email'])!==""?$_POST['ticket_email']:$GLOBALS['user']['user_email']))."',
            ticket_subject = '".test_input($con,$_POST['ticket_subject'])."',
            ticket_type = ".intval(test_input($con,$_POST['ticket_type'])).",
            ticket_content = '".test_input($con,$_POST['ticket_content'])."',
            ticket_priority = ".intval(test_input($con,$_POST['ticket_priority'])).",
            ticket_response = '".test_input($con,$_POST['ticket_response'])."'
        WHERE ticket_id = ".$_POST['ticket_id'];
        //echo $query;
        mysqli_query($con, $query);
        mysqli_close($con);
        break;

    case 'load_Users':
        $con = connectdb();
        $query = 'SELECT * FROM users WHERE user_del = 0';
        $result = mysqli_query($con, $query);
        
        $user_row = '';
        while($row = mysqli_fetch_array($result)){
            $user_row .= '
            <tr class="clickable-row" data-user_id="'. $row['user_id'] .'">
                <td><input type="checkbox" name="user_checkbox" id="'. $row['user_id'] .'"></td>
                <td>'. $row['user_id'] .'</td>
                <td>'. $row['user_firstname'] .' '. $row['user_lastname'] .'</td>
                <td>'. $row['user_company'] .'</td>
                <td>'. $row['user_email'] .'</td>
                <td>'. (($row['user_is_admin']==1) ? "<i class='fa fa-check-circle-o' aria-hidden='true'></i>
                " : "") .'</td>
            </tr>';
        }
        echo $user_row;
        mysqli_close($con);
        break;

    case 'create_user':
        $hash = password_hash("Welkom1234", PASSWORD_DEFAULT);
        $con = connectdb();
        $query = 'INSERT INTO users SET 
            user_email = "'.strtolower(test_input($con,$_POST['user_email'])).'",
            user_firstname = "'.test_input($con,$_POST['user_firstname']).'",
            user_lastname = "'.test_input($con,$_POST['user_lastname']).'",
            user_company = "'.test_input($con,$_POST['user_company']).'",
            user_is_admin = '.intval(test_input($con,$_POST['user_is_admin'])).',
            user_del = 0,
            user_create_date = "'.currentDate().'",
            user_pass = "'.$hash.'",
            user_is_new = 1';
        mysqli_query($con, $query);
        mysqli_close($con);
        break;

    case 'save_edit_user':
        $con = connectdb();
        $query = "UPDATE users SET 
            user_email = '".test_input($con,$_POST['user_email'])."',
            user_firstname = '".test_input($con,$_POST['user_firstname'])."',
            user_lastname = '".test_input($con,$_POST['user_lastname'])."',
            user_company = '".test_input($con,$_POST['user_company'])."',
            user_is_admin = ".intval(test_input($con,$_POST['user_is_admin'])).",
            user_last_edited_date = '".currentDate()."'
        WHERE user_id = ".$_POST['user_id'];
        mysqli_query($con, $query);
        mysqli_close($con);
        break;
    
    case 'del_user':
        $con = connectdb();
        $query = 'UPDATE users SET user_del = 1 WHERE user_id IN ('.implode(",",$_POST['user_id']).')';
        mysqli_query($con, $query);
        mysqli_close($con);
        break;
    
    case 'send_contact_ticket':
        if(($_POST['ticket_email']!=="")&&($_POST['ticket_type']!=="")&&($_POST['ticket_firstname']!=="")&&($_POST['ticket_lastname']!=="")&&($_POST['ticket_company']!=="")&&($_POST['ticket_content']!=="")){
            $conent = 'Bedrijf: '.$_POST['ticket_company'].' Naam: '.$_POST['ticket_firstname'].' '.$_POST['ticket_lastname'].' Inhoud: '.$_POST['ticket_content'];
            $con = connectdb();
            $query = 'INSERT INTO tickets SET 
                ticket_email = "'.test_input($con,$_POST['ticket_email']).'",
                ticket_subject = "'.test_input($con,$_POST['ticket_subject']).'",
                ticket_type = '.intval(test_input($con,$_POST['ticket_type'])).',
                ticket_content = "'.test_input($con,$conent).'",
                ticket_priority = 0,
                ticket_del = 0,
                ticket_create_date = "'.currentDate().'"';
            mysqli_query($con, $query);
            mysqli_close($con);
            echo 'true';
        } else {
            echo 'false';
        }
        break;

    case'savePassword':
        $con = connectdb();
        $hash = password_hash(test_input($con,$_POST['password']), PASSWORD_DEFAULT);
        $query = "UPDATE users SET 
            user_pass = '".$hash."',
            user_is_new = 0,
            user_last_edited_date = '".currentDate()."'
        WHERE user_id = ".$GLOBALS['user']['user_id'];
        mysqli_query($con, $query);
        mysqli_close($con);
        break;

    case 'popups':
        switch ($_POST['type_popup']) {

            case 'popup_ticket_create':
                echo '
                <div class="form-popup Popup_wrapper" id="Ticket_Create_Dialog">
                <form method="" class="form-container">
                    <h1>Create ticket</h1>';
                    if ($GLOBALS['user']['user_is_admin'] == 1) {
                        echo '
                        <div>
                            <label for="ticket_subject"><b>Email</b></label>
                            <input type="email" placeholder="Enter an Email" name="ticket_email" required>
                        </div>';
                    }
                    echo'
                    <div>
                    <label for="ticket_subject"><b>Subject</b></label>
                    <input type="text" placeholder="Enter Subject" name="ticket_subject" required>
                    </div>
                    <div>
                    <label for="ticket_type"><b>Type</b></label>
                    <select name="ticket_type" required>
                        <option value="">--Please choose an option--</option>';
                        for ($i = 0; $i < count($ticket_type_arr); $i++) { 
                            echo '<option value="'.$i.'">'.$ticket_type_arr[$i].'</option> ';
                        }
                        echo '
                    </select>
                    </div>
                    <div>
                    <label for="ticket_priority"><b>Priority</b></label>
                    <select name="ticket_priority" required>
                        <option value="">--Please choose an option--</option>';
                        for ($i = 0; $i < count($ticket_priority_arr); $i++) { 
                            echo '<option value="'.$i.'">'.$ticket_priority_arr[$i][0].'</option> ';
                        }
                        echo '
                    </select>
                    </div>
                    <div>
                    <label for="ticket_content"><b>Content</b></label>
                    <textarea placeholder="Enter Content" name="ticket_content" required></textarea>
                    </div>
                    <button type="button" onclick="createTicket()" class="btn">Send</button>
                    <button type="button" class="btn cancel" onclick="closePopup()">Close</button>
                </form>
                </div>';
                break;

            case 'popup_sure_del_ticket':
                echo '
                <div class="form-popup Popup_wrapper">
                    <div class="form-container">
                        <h1>Weet u zeker dat u deze ticket(s) wilt verwijderen</h1>
                        <button type="button" onclick="delTicket()" class="btn cancel">Verwijderen</button>
                        <button type="button" class="btn" onclick="closePopup()">Annuleren</button>
                    </div>
                </div>';
                break;

            case 'popup_ticket_edit':
                $con = connectdb();
                $query = 'SELECT tickets.* ,users.user_company FROM tickets LEFT JOIN users ON tickets.ticket_email = users.user_email WHERE tickets.ticket_del = 0 AND tickets.ticket_id = "'.$_POST['ticket_id'].'"';
                $result = mysqli_query($con, $query);
                $ticket = mysqli_fetch_array($result);
                echo '
                <div class="form-popup Popup_wrapper" id="Ticket_Edit_Dialog">
                <form method="" class="form-container">
                    <input type="hidden" value="'.$ticket['ticket_id'].'" name="ticket_id">
                    <h1>Edit</h1>
                    <div>
                        <label for="user_company"><b>Company name</b></label>
                        <p>'.$ticket['user_company'].'</p>
                    </div>';
                    if ($GLOBALS['user']['user_is_admin'] == 1) {
                        echo '
                        <div>
                            <label for="ticket_email"><b>Email</b></label>
                            <input type="email" placeholder="Enter an Email" name="ticket_email" value="'.(($ticket['ticket_email']!=='')?$ticket['ticket_email']:"").'" required>
                        </div>';
                    } else {
                        echo'
                        <div>
                            <label for="ticket_email"><b>Email</b></label>
                            <p>'.(($ticket['ticket_email']!=='')?$ticket['ticket_email']:"").'</p>
                        </div>';
                    }
                    echo'
                    <div>
                    <div>
                        <label for="ticket_subject"><b>Subject</b></label>
                        <input type="text" placeholder="Enter Subject" name="ticket_subject" value="'.(($ticket['ticket_subject']!=='') ? $ticket['ticket_subject'] : "").'" required>
                    </div>
                    <div>
                        <label for="ticket_type"><b>Type</b></label>
                        <select name="ticket_type" required>
                            <option value="">--Please choose an option--</option>';
                            for ($i = 0; $i < count($ticket_type_arr); $i++) { 
                                echo '<option '.(($ticket['ticket_type'] == $i)?"selected":"").' value="'.$i.'">'.$ticket_type_arr[$i].'</option> ';
                            }
                            echo '
                        </select>
                    </div>
                    <div>
                        <label for="ticket_priority"><b>Priority</b></label>
                        <select name="ticket_priority" required>
                            <option value="">--Please choose an option--</option>';
                            for ($i = 0; $i < count($ticket_priority_arr); $i++) { 
                                echo '<option '.(($ticket['ticket_priority'] == $i)?"selected":"").' value="'.$i.'">'.$ticket_priority_arr[$i][0].'</option> ';
                            }
                            echo '
                        </select>
                    </div>
                    <div>
                        <label for="ticket_content"><b>Content</b></label>
                        <textarea placeholder="Enter Content" name="ticket_content" required>'.(($ticket['ticket_content']!=='')?$ticket['ticket_content']:"").'</textarea>
                    </div>
                    ';
                    if ($GLOBALS['user']['user_is_admin'] == 1) {
                        echo'<div>
                            <label for="ticket_response"><b>Response</b></label>
                            <textarea placeholder="Enter Response" name="ticket_response">'.(($ticket['ticket_response']!=='')?$ticket['ticket_response']:"").'</textarea>
                        </div>';
                    } else {
                        echo'<div>
                            <h3>Response: </h3><p>'.(($ticket['ticket_response']!=='')?$ticket['ticket_response']:"").'</p>
                        </div>';
                    }
                    echo'
                    
                    <button type="button" onclick="save_edited_ticket()" class="btn">Save</button>
                    <button type="button" class="btn cancel" onclick="closePopup()">Close</button>
                </form>
                </div>';
                mysqli_close($con);
                break;
                
            case 'popup_user_create':
                echo '
                <div class="form-popup Popup_wrapper" id="User_Create_Dialog">
                    <form method="" class="form-container">
                        <h1>Create user</h1>
                        <div>
                            <label for="user_subject"><b>Email</b></label>
                            <input type="email" placeholder="Enter an Email" name="user_email" required>
                        </div>
                        <div>
                            <label for="user_firstname"><b>First name</b></label>
                            <input type="text" placeholder="Enter First Name" name="user_firstname" required>
                        </div>
                        <div>
                            <label for="user_lastname"><b>Last name</b></label>
                            <input type="text" placeholder="Enter Last Name" name="user_lastname" required>
                        </div>
                        <div>
                            <label for="user_company"><b>Company</b></label>
                            <input type="text" placeholder="Enter Company Name" name="user_company" required>
                        </div>
                        <div>
                            <label for="user_is_admin"><b>is Admin</b></label>
                            <input type="checkbox" name="user_is_admin">
                        </div>
                        <button type="button" onclick="createUser()" class="btn">Send</button>
                        <button type="button" class="btn cancel" onclick="closePopup()">Close</button>
                    </form>
                </div>';
                break;

            case 'popup_user_edit':
                $con = connectdb();
                $query = 'SELECT * FROM users WHERE user_del = 0 AND user_id = "'.$_POST['user_id'].'"';
                $result = mysqli_query($con, $query);
                $user = mysqli_fetch_array($result);
                echo '
                <div class="form-popup Popup_wrapper" id="User_Create_Dialog">
                    <form method="" class="form-container">
                        <input type="hidden" value="'.$user['user_id'].'" name="user_id">
                        <h1>Create user</h1>
                        <div>
                            <label for="user_subject"><b>Email</b></label>
                            <input type="email" placeholder="Enter an Email" value="'.$user['user_email'].'" name="user_email" required>
                        </div>
                        <div>
                            <label for="user_firstname"><b>First name</b></label>
                            <input type="text" placeholder="Enter First Name" value="'.$user['user_firstname'].'" name="user_firstname" required>
                        </div>
                        <div>
                            <label for="user_lastname"><b>Last name</b></label>
                            <input type="text" placeholder="Enter Last Name" value="'.$user['user_lastname'].'" name="user_lastname" required>
                        </div>
                        <div>
                            <label for="user_company"><b>Company</b></label>
                            <input type="text" placeholder="Enter Company Name" value="'.$user['user_company'].'" name="user_company" required>
                        </div>
                        <div>
                            <label for="user_is_admin"><b>is Admin</b></label>
                            <input type="checkbox" '.(($user['user_id']==1)?"checked":"").' name="user_is_admin">
                        </div>
                        <button type="button" onclick="saveEditedUser()" class="btn">Send</button>
                        <button type="button" class="btn cancel" onclick="closePopup()">Close</button>
                    </form>
                </div>';
                break;

            case 'popup_sure_del_user':
                echo '
                <div class="form-popup Popup_wrapper">
                    <div class="form-container">
                        <h1>Weet u zeker dat u deze user(s) wilt verwijderen</h1>
                        <button type="button" onclick="delUsers()" class="btn cancel">Verwijderen</button>
                        <button type="button" class="btn" onclick="closePopup()">Annuleren</button>
                    </div>
                </div>';
                break;
        };
        break;
};
?>