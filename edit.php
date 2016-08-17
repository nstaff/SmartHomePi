<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script>
        <?php require_once 'lib/Commands.php'; ?>
            
            function appendHidden(formName, fieldName, fieldValue){
                var newField = document.createElement("input"); 
                newField.setAttribute("type", "hidden");
                newField.setAttribute("name", fieldName);
                newField.setAttribute("value", fieldValue);
                formName.appendChild(newField);
                
            }
            
            function runTest(cmdName){
                //scrape the init value
                var initPhp = document.getElementById("<?php echo TestCommands::INIT; ?>").value;
                
                //scrape the command value
                var cmdPhp = document.getElementById(cmdName).value;
                
                //scrape the vars stuff here
                
                var varsPhp = document.getElementById("<?php echo TestCommands::VARS; ?>").value;
                
                var form = document.createElement("form");
                form.setAttribute("method", "post");
                form.setAttribute("action", "exec/testCommand.php");
                form.setAttribute("target", "view");

                appendHidden(form, "<?php echo TestCommands::INIT; ?>", initPhp);
                appendHidden(form, "<?php echo TestCommands::CMD; ?>", cmdPhp);
                appendHidden(form, "<?php echo TestCommands::VARS; ?>", varsPhp);
                
                document.body.appendChild(form);

                //open testCommand.php sending init and command in PUT/POST
                window.open('', 'view');
                form.submit();
            }
            
        </script>
    </head>
    <body>
        
    <?php include 'top-nav.php';?>
        <?php include 'sidebar.php';?>
        <div id="main" class="col-xs-6">
            <?php
        
            require_once 'conf/dbInfo.php';
            require_once 'lib/DeviceType.php';
            require_once 'lib/DataBroker.php';
            
            
            //GEt the variables needed from URL
            $idNum = $_GET['id'];
            //$type = $_GET['type'];
            $broker = new DataBroker($servername, $username, $password, $dbname);
            
        ?>
	<style>
	.sidebar{
		height:220%;
	}
	</style>
        <form id="editFields" action="updateTables.php" method="post">
            <?php
            //echo "<input type=\"hidden\" name=\"type\" value=\"".$type."\" />";
            
            //print all rows to the form
            $row = $broker->getAllFieldsAsKeyValue($idNum);
            //iterate through each row of the recordset and echo the field for editing
            foreach($row as $fieldName => $value){
                //disable the ID field.
                if($fieldName == "ID" || $fieldName == "TYPE"){
                    //Do nothing. This field should not be seen by the user
                    echo "<textarea id=\"idNum\" name=\"$fieldName\" style=\"display:none\">$value</textarea><br />";
                } 
                else if($fieldName == "NAME"){
                    echo "<h2>$fieldName</h2><textarea id=\"$fieldName\" name=\"$fieldName\">$value</textarea><br />";
                } else {
                    echo "<h2>$fieldName</h2><textarea id=\"$fieldName\" name=\"$fieldName\" rows=\"10\" cols=\"70\">$value"
                            . "</textarea><button type=\"button\" onclick=\"runTest('$fieldName')\">Test</button><br />";
                }
            }
            
            ?>
            <input type="submit" value="Submit"/>
        </form>
        </div>
        <br />
        <br />
        <script>
            //Make the ID field read only.
            document.getElementById("idNum").readOnly = true;
        </script>
        </div>
    </body>
</html>
