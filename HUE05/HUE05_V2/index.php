<?php
session_name("sess_kwm226_hue05");
session_start();

spl_autoload_register(function ($sClassname) {
    require_once($sClassname.".php");
});

Database::loadConfig("inc_db_config.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDoList</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<body>
<?php

//Login and Registration Process incl. Error-Handling
$errors = [];
if(!isset($_SESSION["user"])){
    //case user is not logged in
    if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "login"){
        //case user wants to log in
        if(isset($_REQUEST["username"])
            && isset($_REQUEST["password"])
            && $_REQUEST["username"] != ""
            && $_REQUEST["password"] != ""
            && checkLogin($_REQUEST["username"], $_REQUEST["password"])){

            //case user data is correct
            $_SESSION["user"] = array("username" => $_REQUEST["username"],
                "userid" => getUserId($_REQUEST["username"]));
            showData($_SESSION["user"]["userid"]);
            showTaskForm();
            showLogoutForm();
        }
        else{
            //case user data is incorrect
            echo("<div class='notify red'>Login data incorrect!</div>");
            showLoginForm();
            showRegistrationForm();
        }
    }
    else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "register"){
        //case user wants to register
        if(checkRegistration() && usernameAvailable($_REQUEST["usernamereg"])){
            //data correct
            register($_REQUEST["usernamereg"], $_REQUEST["password1"], $_REQUEST["email"]);
            echo "<div class='notify green'>Successfully registered! You can now login with the username ".$_REQUEST["usernamereg"]."</div><br>";
        }
        else{
            //data incorrect
            echo("<div class='notify red'>Registration data not correct!</div>");
        }
        showLoginForm();
        showRegistrationForm();
    }
    else{
        //case user wants to view page
        showLoginForm();
        showRegistrationForm();
    }
}
//case user is logged in
else {
//case user creates new task
    if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "addTasks") {
        if (titleAvailable($_REQUEST["addTitle"]) && checkAddEntry()) {
            createTask($_SESSION["user"]["userid"], $_REQUEST["addTitle"], $_REQUEST["addNote"]);
            echo("<div class='notify green'>New task created.</div>");
        }
        else{
            echo("<div class='notify red'>Task could not be created - incorrect/duplicate content.</div>");
        }
        showData($_SESSION["user"]["userid"]);
        showTaskForm();
        showLogoutForm();
    }
    //case user wants to logout
    else if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "logout") {
        unset($_SESSION["user"]);
        showLoginForm();
        showRegistrationForm();
    }
    //case user wants to edit a task
    else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "edittask"){
        if(isset($_REQUEST["entryid"])) {
            $_SESSION["task"] = $_REQUEST["entryid"];
        }
        else{
            echo("<div class='notify red'>Task could not be edited.</div>");
        }
        showData($_SESSION["user"]["userid"]);
        showEditForm(getEditData($_REQUEST["entryid"], $_SESSION["user"]));
        showLogoutForm();
    }
    //case user wants to submit Edit-Form
    else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "editTaskSubmit") {
        if (checkEditEntry()) {
            if (editTask($_SESSION["task"], $_SESSION["user"])) {
                echo("<div class='notify green'>Task successfully edited.</div>");
            } else {
                echo("<div class='notify red'>Task could not be edited.</div>");
            }
            showData($_SESSION["user"]["userid"]);
            showTaskForm();
            showLogoutForm();
        }
        else {
            showData($_SESSION["user"]["userid"]);
            showEditForm(getEditData($_SESSION["task"], $_SESSION["user"]));
            showLogoutForm();
        }
    }
    //case user wants to delete task
    else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "deletetask"){
        if(isset($_REQUEST["entryid"])){
            if(deleteTask($_REQUEST["entryid"], $_SESSION["user"])){
                echo("<div class='notify green'>Task was successfully deleted.</div>");
            }
            else{
                echo("<div class='notify red'>Task could not be deleted.</div>");
            }
        }
        showData($_SESSION["user"]["userid"]);
        showTaskForm();
        showLogoutForm();
    }
    //case user wants to finish task
    else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "finishtask"){
        if(isset($_REQUEST["entryid"])){
            if(finishTask($_REQUEST["entryid"], $_SESSION["user"])){
                echo("<div class='notify green'>Task was successfully finished.</div>");
            }
            else{
                echo("<div class='notify red'>Task could not be finished.</div>");
            }
        }
        showData($_SESSION["user"]["userid"]);
        showTaskForm();
        showLogoutForm();
    }
    //case user wants to open task
    else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "opentask"){
        if(isset($_REQUEST["entryid"])){
            if(openTask($_REQUEST["entryid"], $_SESSION["user"])){
                echo("<div class='notify green'>Task was successfully opened.</div>");
            }
            else{
                echo("<div class='notify red'>Task could not be opened.</div>");
            }
        }
        showData($_SESSION["user"]["userid"]);
        showTaskForm();
        showLogoutForm();
    }
    //case user wants to view page
    else {
        showData($_SESSION["user"]["userid"]);
        showTaskForm();
        showLogoutForm();
    }
}

//getEditData()
//Fetches the entry-data of the old entry to show it in the edit-form.
function getEditData(int $taskID, array $user){
    $userQuery = "SELECT creatorid FROM todolist_item WHERE entryid=".$taskID.";";
    $taskOwner = Database::selectQuery($userQuery)->fetch_assoc()['creatorid'];

    if($taskOwner == $user['userid'] || isAdmin($user['userid'])){
        $deleteQuery = "SELECT title, text, entryid FROM todolist_item WHERE entryid = ".$taskID.";";
        $row = Database::selectQuery($deleteQuery)->fetch_assoc();
        if($row){
            return $row;
        }
        return null;
    }
    else return null;
}

//editTask()
//Checks if there are only allowed chars (escaped) to avoid SQL injection.
//Updates the DB row with new values.
function editTask(int $taskID, array $user){
    $escaped = Database::realEscape($_REQUEST["editTitle"], $_REQUEST["editNote"]);
    $userQuery = "SELECT creatorid FROM todolist_item WHERE entryid=".$taskID.";";
    $taskOwner = Database::selectQuery($userQuery)->fetch_assoc()['creatorid'];

    if($taskOwner == $user['userid'] || isAdmin($user['userid'])){
        $editQuery1 = "UPDATE todolist_item 
                        SET title = '".$escaped["val1"]."', 
                        text='".$escaped["val2"]."'  ,
                        editorid=".$_SESSION["user"]["userid"].",
                        editdate='".date("Y-m-d")."'
                        WHERE entryid=".$taskID.";";
        if(Database::updateQuery($editQuery1)){
            return true;
        }
        return false;
    }
    else return false;
}

//deleteTask()
//Deletes a task if the user is allowed to (creator or admin).
//Deletes the row in the todolist-item table.
function deleteTask(int $taskID, array $user){
    $userQuery = "SELECT creatorid FROM todolist_item WHERE entryid=".$taskID.";";
    $taskOwner = Database::selectQuery($userQuery)->fetch_assoc()['creatorid'];

    if($taskOwner == $user['userid'] || isAdmin($user['userid'])){
        $deleteQuery = "DELETE FROM todolist_item WHERE entryid=".$taskID.";";
        if(Database::deleteQuery($deleteQuery)){
            return true;
        }
        return false;
    }
    else return false;
}

//openTask()
//Opens a task if the user is allowed to (creator or admin).
//Updates the row in the todolist-item table -> status "aktiv".
function openTask(int $taskID, array $user){
    $userQuery = "SELECT creatorid FROM todolist_item WHERE entryid=".$taskID.";";
    $taskOwner = Database::selectQuery($userQuery)->fetch_assoc()['creatorid'];

    if($taskOwner == $user['userid'] || isAdmin($user['userid'])){
        $finishQuery = "UPDATE todolist_item SET status = 'aktiv' WHERE entryid=".$taskID.";";
        if(Database::updateQuery($finishQuery)){
            return true;
        }
        return false;
    }
    else return false;
}

//finishTask()
//Opens a task if the user is allowed to (creator or admin).
//Updates the row in the todolist-item table -> status "abgeschlossen".
function finishTask(int $taskID, array $user){
    $userQuery = "SELECT creatorid FROM todolist_item WHERE entryid=".$taskID.";";
    $taskOwner = Database::selectQuery($userQuery)->fetch_assoc()['creatorid'];

    if($taskOwner == $user['userid'] || isAdmin($user['userid'])){
        $finishQuery = "UPDATE todolist_item SET status = 'abgeschlossen' WHERE entryid=".$taskID.";";
        if(Database::updateQuery($finishQuery)){
            return true;
        }
        return false;
    }
    else return false;
}

//createTask()
//Creates a new task in the database
function createTask(int $creatorid, string $title, string $text){
    $escaped = Database::realEscape($title, $text);
    $insertQuery = "INSERT INTO todolist_item (creationdate, creatorid, title, text) 
                    VALUES ('".date("Y-m-d")."', '".$creatorid."', '".$escaped["val1"]."', '".$escaped["val2"]."');";
    $taskID = Database::insertQuery($insertQuery);

    if($taskID != 0){
        $insertQuery2 = "INSERT INTO user_item (entryid, userid) VALUES (".$taskID.", ".$creatorid.");";
        Database::insertQuery($insertQuery2);
        return true;
    }
    return false;
}


//getUserId()
//Gets the UserId of a user with a certain, unique username.
function getUserId(string $username){
    $escaped = Database::realEscape($username);
    $selectQuery = "SELECT userid FROM user WHERE username='".$escaped["val1"]."';";
    $result = Database::selectQuery($selectQuery);
    if($row = $result->fetch_assoc()){
        return $row["userid"];
    }
    else return null;
}

//usernameAvailable()
//Checks if the entered username in the registration form is already taken or not.
function usernameAvailable(string $username){
    $escaped = Database::realEscape($username);
    $selectQuery = "SELECT username FROM user WHERE username='".$escaped["val1"]."';";
    $result = Database::selectQuery($selectQuery);

    if($result->num_rows < 0){
        return false;
    }
    return true;
}

//isAdmin()
//Checks if recent user has the role admin in the database
function isAdmin(int $userID):bool{
    $selectQuery = "SELECT role FROM user
                    WHERE userid = " . $userID . ";";

    $data = Database::selectQuery($selectQuery);
    $row = mysqli_fetch_assoc($data);
    if($row["role"] != 1)
        return false;
    else
        return true;
}

//register()
//This function allows a new user to register by entering all
//data needed. If the user wants to register with a already
//existing username, a error message shows up.
function register(string $username, string $password, string $email):bool{
    $escaped = Database::realEscape($username, $email);
    $insertQuery = "INSERT into user (username, password, email) 
    VALUES ('".$escaped["val1"]."', 
    '".md5($password)."', 
    '".$escaped["val2"]."');";
    $id = Database::insertQuery($insertQuery);

    if($id != 0)
        return true;
    return false;
}

//titleAvailable()
//Checks if the title of the task is already used.
function titleAvailable(string $title){
    $selectQuery = "SELECT title FROM todolist_item 
                    WHERE creatorid='" .$_SESSION["user"]["userid"]."'
                    AND title='".$title."';";

    $result = Database::selectQuery($selectQuery);

    if($result->num_rows > 0){
        return false;
    }
    return true;
}

//checkAddEntry()
//Checks the input fields of the form to add new tasks
function checkAddEntry():bool{
    global $errors;
    if(!isset($_REQUEST["addTitle"]) || strlen($_REQUEST["addTitle"]) < 6)
        $errors["addTitle"] = "<i>Title must be at least 6 characters long!</i>";

    if(count($errors) > 0)
        return false;
    return true;
}

//checkEditEntry()
//Checks the input fields of the form to edit tasks
function checkEditEntry():bool{
    global $errors;
    if(!isset($_REQUEST["editTitle"]) || strlen($_REQUEST["editTitle"]) < 6)
        $errors["editTitle"] = "<i>Title must be at least 6 characters long!</i>";

    if(count($errors) > 0)
        return false;
    return true;
}

//checkLogin()
//Checks the input fields of the login form by comparing it with the saved data of the userList.
//Only if username and password are correct, the user can login.
function checkLogin(string $username, string $password):bool{
    $escaped = Database::realEscape($username, $password);
    $selectQuery = "SELECT username FROM user WHERE username='".$escaped["val1"]."' AND password='".md5($password)."';";
    $result = Database::selectQuery($selectQuery);
    if($result->num_rows > 0){
        return true;
    }
    else {
        global $errors;
        //username
        if (!isset($_REQUEST["username"]))
            $errors["username"] = "<br><i>Please enter valid username.</i>";
        //password
        if (!isset($_REQUEST["password"]))
            $errors["password"] = "<br><i>Please enter valid password.</i>";
        return false;
    }
}

//checkRegistration()
//Checks if various rules of the input fields of the registration form
//are followed. Otherwise the errors will be saved.
function checkRegistration():bool{
    global $errors;
    //username
    if(!isset($_REQUEST["usernamereg"]) || strlen($_REQUEST["usernamereg"]) < 6)
        $errors["usernamereg"] = "<br><i>Username must be at least 6 characters long</i>";

    //password
    if(!isset($_REQUEST["password1"]) ||
        !isset($_REQUEST["password2"]) ||
        $_REQUEST["password1"] != $_REQUEST["password2"] ||
        strlen($_REQUEST["password1"]) < 8)
        $errors["password1"] = "<br><i>Password must be at least 8 characters long and must match</i>";

    //email
    if(!isset($_REQUEST["email"]) || filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL) === false)
        $errors["email"] = "<br><i>Must be a valid email address</i>";

    if(count($errors) > 0)
        return false;
    return true;
}

//showData()
//This function prints the TodoList. Only tasks of the
//user who is logged in will be shown, all other tasks wont be
//visible for this user. (admin sees all tasks)
function showData($userID)
{
    if (!isAdmin($userID))
        $selectQuery = "SELECT * FROM todolist_item WHERE creatorid = " . $userID . ";";
    else
        $selectQuery = "SELECT * FROM todolist_item;";

    $data = Database::selectQuery($selectQuery);
    $todolist = new TodoList($_SESSION["user"]["userid"], $_SESSION["user"]["username"]);

    while ($row = $data->fetch_assoc()) {
        $todolistitem = new TodoListItem($row["entryid"], $row["creationdate"], $row["creatorid"], $row["title"], $row["editdate"], $row["text"], $row["status"], $row["editorid"]);
        array_push($todolist->entries, $todolistitem);
    }
    echo $todolist;
}

//showTaskForm()
//Output of the TaskForm - if errors occur, the incorrect fields
//will be highlighted and the error will be described
function showTaskForm(){
    global $errors;
    $title = "";

    if(isset($_REQUEST["addTitle"]) && count($errors) > 0){
        $title = $_REQUEST["addTitle"];
    }
    ?>

    <div class="form-style-5">
        <form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post">
            <fieldset>
                <legend><span class="number">+</span>Eintrag hinzufügen</legend>
                <?php
                if(isset($errors["addTitle"])){
                    echo('<input type="text" name="addTitle" id="addTitle" placeholder="Titel*" class="error" value="'.$title.'">');
                    echo($errors["addTitle"]);
                }
                else echo('<input type="text" name="addTitle" id="addTitle" placeholder="Titel*" value="'.$title.'">');

                if(isset($_REQUEST["addNote"]) && count($errors) > 0){
                    $desc = $_REQUEST["addNote"];
                    echo('<textarea name="addNote" id="addNote">'.$desc.'</textarea>');
                }
                else echo('<textarea name="addNote" id="addNote" placeholder="Beschreibung"></textarea>');
                ?>
            </fieldset>
            <input type="hidden" name="action" value="addTasks">
            <input type="submit" id="addTasks" value="Eintrag hinzufügen">
        </form>
    </div>
    <?php
}

//showEditForm()
//Output of the EditForm - if errors occur, the incorrect fields
//will be highlighted and the error will be described
function showEditForm(array $row){
    global $errors;
    $title = "";

    if(isset($_REQUEST["editTitle"]) && count($errors) > 0){
        $title = $_REQUEST["editTitle"];
    }
    ?>

    <div class="form-style-5">
        <form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post">
            <fieldset>
                <legend><span class="number">!</span>Eintrag bearbeiten</legend>
                <?php
                if(isset($errors["editTitle"])){
                    echo('<input type="text" name="editTitle" id="editTitle" placeholder="Titel*" class="error" value="'.$title.'">');
                    echo($errors["editTitle"]);
                }
                else echo('<input type="text" name="editTitle" id="editTitle" placeholder="Titel*" value="'.$row["title"].'">');

                if(isset($_REQUEST["editNote"]) && count($errors) > 0){
                    $desc = $_REQUEST["editNote"];
                    echo('<textarea name="editNote" id="editNote">'.$desc.'</textarea>');
                }
                else echo('<textarea name="editNote" id="editNote" placeholder="Beschreibung">'.$row["text"].'</textarea>');
                ?>
            </fieldset>
            <input type="hidden" name="action" value="editTaskSubmit">
            <input type="submit" id="editTasks" value="Eintrag ändern">
        </form>
    </div>
    <?php
}

//showRegistrationForm()
//Output of the registration form - if errors occur, the incorrect fields
//will be highlighted and the error will be described
function showRegistrationForm(){
    global $errors;
    $usernameVal = "";
    $emailVal = "";

    if(isset($_REQUEST["usernamereg"]) && count($errors) > 0){
        $usernameVal = $_REQUEST["usernamereg"];
    }
    if(isset($_REQUEST["email"]) && count($errors) > 0){
        $emailVal = $_REQUEST["email"];
    }
    ?>
    <div class="registration_form">
        <h3>Registrierung:</h3>
        <form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post">
            <label for="usernamereg">Username:</label>
            <?php
            if(isset($errors["usernamereg"])){
                echo('<input type="text" name="usernamereg" id="usernamereg" value="'.$usernameVal.'" class="error">');
                echo($errors["usernamereg"]);
            }
            else
                echo('<input type="text" name="usernamereg" id="usernamereg" value="'.$usernameVal.'">');
            ?>
            <br>
            <label for="password1">Passwort:</label>
            <?php
            if(isset($errors["password1"])){
                echo('<input type="password" name="password1" id="password1" class="error">');
                echo($errors["password1"]);
            }
            else
                echo('<input type="password" name="password1" id="password1">');
            ?>
            <br>
            <label for="password2">Wiederholen:</label>
            <input type="password" name="password2" id="password2"><br>
            <label for="email">Email:</label>
            <?php
            if(isset($errors["email"])){
                echo('<input type="email" name="email" id="email" class="error" value="'.$emailVal.'">');
                echo($errors["email"]);
            }
            else echo('<input type="email" name="email" id="email" value="'.$emailVal.'">');
            ?>
            <br>
            <input type="hidden" name="action" value="register">
            <input type="submit" value="Registrieren" class="reg_button">
        </form>
    </div>
    <?php
}

//showLoginForm()
//Output of the login form
function showLoginForm(){
    global $errors;
    $usernameVal = "";

    if(isset($_REQUEST["username"]) && count($errors) > 0){
        $usernameVal = $_REQUEST["username"];
    }?>

    <div class="login_form">
    <h3>Login:</h3>
    <form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post">
        <label for="username">Username:</label>
        <?php
        if(isset($errors["username"])){
            echo('<input type="text" name="username" id="username"/>'.$usernameVal.'<br>');
            echo($errors["username"]);
        }
        else echo('<input type="text" name="username" id="username"/><br>');
        ?>
        <label for="password">Passwort:</label>
        <?php
        if(isset($errors["password"])){
            echo('<input type="password" name="password" id="password"/><br>');
            echo($errors["username"]);
        }
        else echo('<input type="password" name="password" id="password"/><br>');
        ?>
        <input type="hidden" name="action" value="login">
        <input type="submit" class="login_button" value="Login">
    </form>
    </div><?php
}

//showLogoutForm()
//Output of the logout button
function showLogoutForm(){ ?>
    <form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logout" class="logout_button">
    </form>
    <?php
}?>

</body>
</html>