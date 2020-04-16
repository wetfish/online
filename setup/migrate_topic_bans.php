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

	// Add id_msg column;
	$res = query("ALTER TABLE {$db_prefix}topic_bans ADD id_msg int(11);");
	
	// Get topics for bans without a post associated
	$topics = query("SELECT id_topic, id_msg, id_member FROM {$db_prefix}topic_bans WHERE id_msg IS NULL;");

	while ($topic = $topics->fetch_assoc())
	{
		// Get post id for latest post by banned user in topic
		$posts = query("
		SELECT id_msg from {$db_prefix}messages
		WHERE id_member = '{$topic['id_member']}'
		AND id_topic = '{$topic['id_topic']}'
		ORDER BY id_msg DESC LIMIT 1");

		// Populate relevant topic_bans row with post_id
		$post = $posts->fetch_assoc();
		query("
		UPDATE {$db_prefix}topic_bans
		SET id_msg = '{$post['id_msg']}'
		WHERE id_member = '{$topic['id_member']}'
		AND id_topic = '{$topic['id_topic']}'");
	}
}

// Undo migration
function Down()
{
	global $db_prefix;

	$res = query("ALTER TABLE {$db_prefix}topic_bans DROP COLUMN id_msg;");
}
