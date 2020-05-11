function expandPagination(node, url, first, last)
{
	node.style.fontWeight = 'normal';
	node.onclick = '';

	replace = '';

	for (i = first; i <= last; i++)
	{
		replace += '<a class="navPages" href="' + url.replace(/%1\$d/, i).replace(/%%/g, '%') + '">' + i + '</a> ';
	}
	setInnerHTML(node, replace);
}