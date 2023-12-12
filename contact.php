<!DOCTYPE html>
<html lang="en">
<?php 
include './Header.php';
?>

<script>
// setTimeout(function() {
//     window.location.reload(1);
// }, 5000);
</script>
    <section class="contact-container">
        <div class="form_container contact_form">
            <h1>Contact ons voor meer nieuws en betere beveiliging </h1>
                <form method="" id="ticket_contact" action="">
                    <div class="from_input">
                        <input type="text" name="email" id="name" placeholder="Email">
                        <select name="type" id="aanmeld_option">
                            <option value="">--Please choose an option--</option>
                            <?php
                            for ($i = 0; $i < count($ticket_type_arr); $i++) { 
                                echo '<option value="'.$i.'">'.$ticket_type_arr[$i].'</option> ';
                            }
                            ?>
                        </select>
                        <input type="text" name="subject" id="name" placeholder="Onderwerp">
                        <input type="text" name="firstname" id="fullName" placeholder="Voor Naam">
                        <input type="text" name="lastname" id="fullName" placeholder="Achter Naam">
                        <input type="text" name="companyName" id="companyName" placeholder="Bedrijfs naam">
                        <!-- <input type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==10) return false;" name="Nummber" id="Nummber" placeholder="Telefoon nummer"> -->
                    </div>
                    <div class="description_input">
                        <textarea name="content" id="omschrijving" cols="40" rows="5" placeholder="Omschrijving..."></textarea>
                    </div>
                    <div class="submit_btn">
                        <button type="button" onclick="submit_new_ticket()">Versturen</button>
                    </div>
                </form>
        </div>
    </section>
</body>
<script>
    function submit_new_ticket(){
        $.post("functions/dashboard_functions.php",{
            function: "send_contact_ticket",
            ticket_email: $("#ticket_contact input[name='email']").val(),
            ticket_type: $("#ticket_contact select[name='type']").val(),
            ticket_subject: $("#ticket_contact input[name='subject']").val(),
            ticket_firstname: $("#ticket_contact input[name='firstname']").val(),
            ticket_lastname: $("#ticket_contact input[name='lastname']").val(),
            ticket_company: $("#ticket_contact input[name='companyName']").val(),
            ticket_content: $("#ticket_contact textarea[name='content']").val()
        }).done(function(data){
            if(data!=="false"){
                //alert("Uw ticket is verstuurd (check uw email bevestiging)");
                $("#ticket_contact input[name='email']").val("");
                $("#ticket_contact select[name='type']").val("");
                $("#ticket_contact input[name='subject']").val("");
                $("#ticket_contact input[name='firstname']").val("");
                $("#ticket_contact input[name='lastname']").val("");
                $("#ticket_contact input[name='companyName']").val("");
                $("#ticket_contact textarea[name='content']").val("");
            } else {
                alert("Niet alle velden zijn ingevuld");
            }
        });
    }
</script>
</html>