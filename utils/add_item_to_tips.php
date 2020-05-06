<?php
include_once("../forum/Settings.php");

$mysql = new mysqli($db_server, $db_user, $db_passwd, $db_name);
if ($mysql->connect_errno)
{
	print("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	exit();
}

if (isset($argv[1]))
{
	switch(strtolower($argv[1]))
	{
		case "up": Up(); break;
		case "down": Down(); break;
	}
}
else
{
	print("Usage: 'php migrate_topic_bans.php [options]'\nOptions: 'Up', 'Down'\n");
}

function query($string)
{
	global $mysql;

	$result = $mysql->query($string);
	if ($result == false)
	{
		print("Error: {$mysql->error}\n");
		exit();
	}
	return $result;
}

// Apply migration
function Up()
{
	global $db_prefix;

	// Add item column
	query("ALTER TABLE {$db_prefix}message_tips ADD item INT;");
}

// Undo migration
function Down()
{
	global $db_prefix;

	query("ALTER TABLE {$db_prefix}message_tips DROP COLUMN item;");
}
?>
