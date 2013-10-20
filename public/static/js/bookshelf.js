function addchapter(id) {
	$.post("/api/bookshelfs/addchapter", {
		bookID : id
	}, function(data) {
	});
	if (document.getElementById("read_num_" + id).innerHTML == "??") {
		document.getElementById("read_num_" + id).innerHTML = "1";
	} else {
		var newnum = parseFloat(document.getElementById("read_num_" + id).innerHTML) + 1;
		document.getElementById("read_num_" + id).innerHTML = newnum;
	}
}

function minuschapter(id) {
	$.post("/api/bookshelfs/minuschapter", {
		bookID : id
	}, function(data) {
	});
	if (document.getElementById("read_num_" + id).innerHTML == "??") {
		document.getElementById("read_num_" + id).innerHTML = "1";
	} else {
		var newnum = parseFloat(document.getElementById("read_num_" + id).innerHTML) - 1;
		document.getElementById("read_num_" + id).innerHTML = newnum;
	}
}