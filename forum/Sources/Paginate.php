<?php

if (!defined('SMF'))
	die('Hacking attempt...');

function Paginate($url, $page, $pages, $numPerPage)
{
	global $scripturl;
	$result = '';

	for ($i = 1; $i <= $pages; $i++)
	{
		parse_str($url, $test);
		$test = http_build_query(array_merge($test, array('page' => $i)));

		// Current page
		if ($page == $i)
		{
			$result .= "<strong>$i</strong>";
		}
		else
		{
			$result .= "<a class='navPages' href='?$test'>$i</a>";
		}
	}


	return $result;
}

?>
