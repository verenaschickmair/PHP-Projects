<?php
//Loading classes
spl_autoload_register('autoload');
function autoload($sClassname): void
{
    require_once($sClassname . ".php");
}
//Load pre-defined users and add to UserList
$user = new User("admin1", "abcdefgh", "email", 1); //ADMIN
$user1 = new User("PhpLover1", "abcdefgh", "email"); //USER
$user2 = new User("jdoe", "abcdefgh", "email"); //USER
$userList = new UserList(); //VERWALTUNG ALLER USER
$userList->addUser($user);
$userList->addUser($user1);
$userList->addUser($user2);

//An admin is logged in in the recent TodoList
$toDoList = new TodoList($user);

//Creating various tasks
$entry = new ToDoListItem("e1", new DateTime("2021-02-01"), $user2->userId, "Hausarbeit", new DateTime("2021-02-01"));
$entry1 = new ToDoListItem("e2", new DateTime("2021-04-05"), $user1->userId, "Hausübung machen", new DateTime("2021-04-02"), "Dringend die PHP Hausübung abgeben :D");
$entry2 = new ToDoListItem("e3", new DateTime("2021-03-06"), $user1->userId, "Yoga", new DateTime("2021-03-02"));
$entry3 = new ToDoListItem("e4", new DateTime("2021-08-05"), $user2->userId, "Prokrastinieren", new DateTime("2021-03-02"), "Ich bin eine Notiz, hallo!");

//Add tasks to TodoList
$toDoList->addEntry($entry);
$toDoList->addEntry($entry1);
$toDoList->addEntry($entry2);
$toDoList->addEntry($entry3);
?>