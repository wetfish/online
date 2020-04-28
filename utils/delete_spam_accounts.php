<?php
include_once("../forum/Settings.php");

// Boilerplate
$mysql = new mysqli($db_server, $db_user, $db_passwd, $db_name);
if ($mysql->connect_errno)
{
	print("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	exit();
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
// End Boilerplate

// Find all emails belonging to users with no posts or are banned already.
$emails = query("
	SELECT id_member, member_name, email_address, is_activated, posts
	FROM {$db_prefix}members
	WHERE posts = 0 OR is_activated = 11
    ORDER BY id_member ASC;");

function LoadBlacklist($string)
{
	global $BlacklistDir;
	$handle = fopen("{$BlacklistDir}/{$string}", "r");
	$blacklist = explode("\n", fread($handle, filesize("{$BlacklistDir}/{$string}")));
	fclose($handle);
	return $blacklist;
}

// A CSV file will be generated and placed into the results directory, along with an account deletion sql file.
$BlacklistDir = "./blacklists";
$ResultDir = "./results";

// These blacklists were acquired from stopforumspam.com and archive.org archives of their lists
// 3 different types are used:
//      All email addresses from the last year (listed_email_365.zip)
//      Domains (toxic_domains_whole.txt)
//      Partial domains (toxic_domains_partial.txt)
// I didn't bother combining the lists and removing duplicates.

// Load StopForumSpam blacklists into arrays
// Flip the array as checking with isset is much faster than using in_array
// Load complete emails
$emailBlacklist = array_flip(LoadBlacklist("listed_email_365.txt"));
$emailBlacklist2017 = array_flip(LoadBlacklist("listed_email_365-2017.txt"));
$emailBlacklist2018 = array_flip(LoadBlacklist("listed_email_365-2018.txt"));
// Load complete domains
$domainBlacklist = array_flip(LoadBlacklist("toxic_domains_whole_filtered_50000.txt"));
$domainBlacklist2017 = array_flip(LoadBlacklist("toxic_domains_whole-2017.txt"));
$domainBlacklist2018 = array_flip(LoadBlacklist("toxic_domains_whole-2018.txt"));
// Load partial domains
$partialDomainBlacklist = LoadBlacklist("toxic_domains_partial.txt");
$partialDomainBlacklist2017 = LoadBlacklist("toxic_domains_partial-2017.txt");
$partialDomainBlacklist2018 = LoadBlacklist("toxic_domains_partial-2018.txt");

$manualBlacklist = Array(
	"ffdfgsjgieyriwffdf",
	"rtuwr87468treyeew",
	"viagra",
	"worldnews24h",
	"viceshow",
	"moretrend"
);

$idsToBeDeleted = array();

$handle = fopen("{$ResultDir}/report.csv", "w");
while ($email = $emails->fetch_assoc())
{
	$deleted = true;
	$detection_method = '';

	if ($email['is_activated'] == 11)
	{
		$detection_method .= "Already banned. ";
	}
	if (substr_count(explode('@', $email['email_address'])[0], '.') >= 3 && $email['posts'] == 0)
	{
		$detection_method .= "Over 3 dots. ";
	}
	if (compare_partial_blacklist_to_string($email['email_address'], $manualBlacklist))
	{
		$detection_method .= "Manual blacklist. ";
	}
	if (check_email_address($email['email_address']))
	{
		$detection_method .= "Spammer email. ";
	} 
	if (check_email_domain($email['email_address']))
	{
		$detection_method .= "Spammer domain. ";
	}

	if ($detection_method == '')
	{
		$detection_method = 'No detections. ';
		$deleted = false;
	}
	
	if ($deleted && $email['posts'] == 0)
	{
		$idsToBeDeleted[] = $email['id_member'];
	}
	fputcsv($handle, array($email['id_member'], $email['member_name'], $email['email_address'], $email['posts'], $deleted ? 'true' : 'false', $detection_method));
}
fclose($handle);

GenerateDeletionSchema($idsToBeDeleted);

function GenerateDeletionSchema($ids)
{
	global $db_prefix, $ResultDir;
	$sqlFile = fopen("{$ResultDir}/delete_accounts.sql", "w");

    $idString = implode(',', $ids);

	$query = "DELETE FROM {$db_prefix}members WHERE id_member in ({$idString});\n";
    $query .= "DELETE FROM {$db_prefix}inventory WHERE id_member in ({$idString});\n";
    $query .= "SET @newTotalMembers = (SELECT COUNT(*) from {$db_prefix}members WHERE is_activated = 1);\n";
    $query .= "UPDATE {$db_prefix}settings SET value = @newTotalMembers WHERE variable = 'totalMembers';\n";

    fputs($sqlFile, $query . "\n");
	fclose($sqlFile);
}

// Compares a given email's domain to the partial domain blacklist
function check_email_domain($email)
{
	global $partialDomainBlacklist, $partialDomainBlacklist2017, $partialDomainBlacklist2018;
	global $domainBlacklist, $domainBlacklist2017, $domainBlacklist2018;

	$domain = explode('@', $email)[1];

	// Check complete domains.
	if (isset($domainBlacklist[$domain]) || isset($domainBlacklist2017[$domain]) || isset($domainBlacklist2018[$domain]))
	{
		return true;
	}

	// Check partials.
	if (compare_partial_blacklist_to_string($domain, $partialDomainBlacklist))
	{
		return true;
	}
	if (compare_partial_blacklist_to_string($domain, $partialDomainBlacklist2017))
	{
		return true;
	}
	if (compare_partial_blacklist_to_string($domain, $partialDomainBlacklist2018))
	{
		return true;
	}
	return false;
}

//Compares a given email address to the email blacklists.
function check_email_address($email)
{
	global $emailBlacklist, $emailBlacklist2017, $emailBlacklist2018;
	
	if (isset($emailBlacklist[sanitize_email($email)]) || isset($emailBlacklist2017[sanitize_email($email)]) || isset($emailBlacklist2018[sanitize_email($email)]))
	{
		return true;
	} 
	return false;
}

function compare_partial_blacklist_to_string($string, $blacklist)
{
	for ($i = 0; $i < count($blacklist); $i++)
	{
		if (strpos($string, $blacklist[$i]) !== false)
		{
			return true;
		}
	}
	return false;
}

function sanitize_email($string)
{
	$atPos = strpos($string, '@');
	$name = str_replace('.', '', substr($string, 0, $atPos));
	$plusPos = strpos($name, '+');
	if ($plusPos)
	{
		$name = substr($name, 0, $plusPos);
	}

	return $name . substr($string, $atPos);
}
?>