<?php
require_once "data.php";
session_start(); //Zugriff auf Session Array
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDoList</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

<?php
global $toDoList; //TodoList of data.php
global $userList; //UserList of data.php
//Vordefinierte Todoliste soll nur beim ersten Mal eingespielt werden
//Ermöglicht Aktualisieren der Todoliste
//und der Userliste mittels Session-Variable

//Pre-defined TodoList should only be loaded at the first time
//So the Todolist can be updated frequently with new tasks
//and wont be overwritten by the old TodoList
if(!isset($_SESSION["todolist"])){
    $_SESSION["todolist"] = $toDoList;
}

//Pre-defined UserList should only be loaded at the first time
//So the UserList can be updated frequently with new users
//and wont be overwritten by the old UserList
else if(!isset($_SESSION["userlist"])){
    $_SESSION["userlist"] = $userList;
}
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
            $_SESSION["user"] = $_REQUEST["username"];
            showData();
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
        if(checkRegistration()){

            //data correct
            register();
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
else{
    //case user creates new task
    if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "addTasks"){
        if(checkAddEntry())
            actualizeData();
        showData();
        showTaskForm();
        showLogoutForm();
    }
    //case user wants to logout
    else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "logout"){
        unset($_SESSION["user"]);
        showLoginForm();
        showRegistrationForm();
    }
    //case user wants to view page
    else{
        showData();
        showTaskForm();
        showLogoutForm();
    }
}

//register()
//This function allows a new user to register by entering all
//data needed. If the user wants to register with a already
//existing username, a error message shows up.
function register(){
    try {
        $newUser = new User($_REQUEST["usernamereg"], $_REQUEST["password1"], $_REQUEST["email"]);
        $_SESSION["userlist"]->addUser($newUser);
        echo("<div class='notify green'>Registration successful!</div>");
    } catch (Exception $e){
        echo("<div class='notify red'>".$e->getMessage()."</div>");
    }
}

//actualizeData()
//The TodoList will be updated by adding a new TodoListItem.
function actualizeData(){
    $entry = new TodoListItem("e".rand(), new DateTime("now"), $_SESSION["user"], $_REQUEST["addTitle"], new DateTime("now"), $_REQUEST["addNote"]);
    $_SESSION["todolist"]->addEntry($entry);
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

//checkLogin()
//Checks the input fields of the login form by comparing it with the saved data of the userList.
//Only if username and password are correct, the user can login.
function checkLogin(string $username, string $password):bool{
    return($username == $_SESSION["userlist"]->users[$username]->userId && $password == $_SESSION["userlist"]->users[$username]->password);
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
//This function prints the (updated) TodoList. Only tasks of the
//user who is logged in will be shown, all other tasks wont be
//visible for this user.
function showData(){
    $user = $_SESSION["userlist"]->users[$_SESSION["user"]];
    $_SESSION["todolist"]->loginUser = $user;
    echo($_SESSION["todolist"]);
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
function showLoginForm(){?>
    <div class="login_form">
    <h3>Login:</h3>
    <form action="<?php echo($_SERVER['PHP_SELF']);?>" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username"/><br>
        <label for="password">Passwort:</label>
        <input type="password" name="password" id="password"/><br>
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