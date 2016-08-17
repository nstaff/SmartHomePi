<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/bootstrap.css" rel="stylesheet" />
    <link href="styles/styles.css" rel="stylesheet" />
    <link href="styles/font-awesome.css" rel="stylesheet" />
</head>
<body>
<?php include 'top-nav.php';?>
<?php include 'sidebar.php';?>

<div>

    <div id="main" class="col-xs-6" style="padding-top: 70px">
        <form action="authenticate.php" method="POST">
            Username: <input id="username" name="username" type="text"><br>
            Password: <input id=password name="password" type="password"><br>

            <input type="submit" value="Login">
        </form>
        <a href="addUser.php">Create User</a>
    </div>
</div>
</body>
</html>