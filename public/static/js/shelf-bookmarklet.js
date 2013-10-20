function create(htmlStr) {
    var frag = document.createDocumentFragment(),
        temp = document.createElement('div');
    temp.innerHTML = htmlStr;
    while (temp.firstChild) {
        frag.appendChild(temp.firstChild);
    }
    return frag;
}

function parse_url_to_chapter_number()
{
	chapter-[0-9].match(document.location.href)
	
	return chapter;
}

var fragment = create('<div class="bookshelf"><form method="post" action="http://localhost/muses-success/accounts/bookshelf/bookmarklet"><strong>Update Bookshelf: </strong> <input type="text" size="2" /> Chapters Read <input type="checkbox" /> Completed Reading <input type="submit" name="update" value="Update Bookshelf" /> <a href="javascript:closebookmarklet();">X</a></form></div><link rel="stylesheet" href="http://localhost/muses-success/static/css/bookmarklet.css" type="text/css" />');
// You can use native DOM methods to insert the fragment:
document.body.insertBefore(fragment, document.body.childNodes[0]);