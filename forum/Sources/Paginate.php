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
		// Always show first or last page links
		else if ($i == 1 || $i == $pages)
		{
			$result .= "<a class='navPages' href='?$test'>$i</a>";
		}
		// 3 pages away from current? show a ... expansion button
		else if (abs($page - $i) == 3)
		{
			parse_str($_GET, $_GET);
			unset($_GET['page']);
			$_GET = http_build_query($_GET);
			// Left side ...
			if ($page - $i > 0)
			{
				$result .= "<span style='font-weight: bold;' onclick='" . htmlspecialchars("expandPagination(this, \"$scripturl?$_GET&page=%1\$d\", 2, $i)") . "'><a>...</a></span>";
			}
			// Right side ...
			else if ($page - $i < 0)
			{
				$result .= "<span style='font-weight: bold;' onclick='" . htmlspecialchars("expandPagination(this, \"$scripturl?$_GET&page=%1\$d\", $i, " . strval($pages-1) . ")") . "'><a>...</a></span>";
			}
		}
		// Don't show things further than 3 away
		else if(abs($page - $i) > 3)
		{
			continue;
		}
		else
		{
			$result .= "<a class='navPages' href='?$test'>$i</a>";
		}
	}


	return $result;
}

?>
