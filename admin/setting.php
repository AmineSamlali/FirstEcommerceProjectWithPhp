<?php
session_start();
session_regenerate_id();

include 'check_auth.php';

$pageName = 'Settings';

include 'init.php';


include $functions_directory . 'sql_functions.php';


$settingsData = fetchMyColumn('*','shop.settings','id=1','data','One');

?>

        <div class="container">
                    <h1 class="text-center">Setting Profile</h1>
                    <form class="form-horizontal" id='exampleMaintenance1' method="POST">

                    <div id="update_form">
                        <div class="form-group">
                            <label for="exampleInputFullName1">Contact Email</label>
                        <input name="fullName" type="email" class="form-control" value="<?php echo $settingsData['email']; ?>" id='emailAddres' placeholder="Contact email" autocomplete="off"  required>
                        </div>
                    </div>
                    <div id="update_form">
                        <div class="form-group">
                            <label for="exampleInputFullName1">Maintenance Mode</label>
                        <select name="fullName" type="select" class="form-control" id='exampleMaintenance' placeholder="Full Name" autocomplete="off"  required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                        </select>   
                        </div>
                    </div>


                    <center><button type="submit" class="btn btn-primary"  id ='btnBtn' >Set Informations</button></center>

                    </form>
            </div>




<?php include $template_directory . 'footer.php'?>;


<script>
    $("#exampleMaintenance").val("<?php echo $settingsData['maintenance_mode'] ?>");

    $("#exampleMaintenance1").on("submit" , (e)=>{
        e.preventDefault();
        $.post('ajax_Check.php',{
            formType:'setting-update',
            maintenanceMode:$('#exampleMaintenance').val(),
            email:$('#emailAddres').val()
        })
    })
</script>