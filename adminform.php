<script>
function consadd(line)
{
	var t = document.getElementById("cons").value;
	document.getElementById("cons").value = "> "+line+"\n"+t;
}
function ajax(url, callbackFunction)
{
	var request =  new XMLHttpRequest();
	request.open("GET", url, true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.onreadystatechange = function()
	{
		var done = 4, ok = 200;
		if (request.readyState == done && request.status == ok) {
		  if (request.responseText) {
			callbackFunction(request.responseText);
		  }
		}
	};
	request.send();
}

function emptycache() {
	ajax("?c=emptycache", consadd);
}
function filldb() {
	consadd("Filling Artists Database. Please be patient, this could take some time.");
	ajax("?c=filldb", consadd);
}
function clearlist() {
	ajax("?c=clearlist", consadd);
}
function bans() {
	ajax("?c=bans", consadd);
}
function enter() {
	var command = document.getElementById("comm").value;
	var c = command.split(" ");
	if(c.length==1)
	{
		if(c[0]=="filldb") consadd("Filling Artists Database. Please be patient, this could take some time.");
		ajax("?c="+c[0],consadd);
	}
	else if(c.length==2)
		ajax("?c="+c[0]+"&v="+c[1],consadd);
	document.getElementById("comm").value = "";
}
document.onkeypress = keyPress;

function keyPress(e)
{
	var x = e || window.event;
	var key = (x.keyCode || x.which);
    if(key == 13 || key == 3) enter();
}
function getComplete(text)
{
	consadd(text);
}
function help()
{
	var help = document.getElementById("help");
	if(help.style.visibility=="visible")
		help.style.visibility = "hidden";
	else
		help.style.visibility = "visible";
}
</script>
<title>Lastfmlogos Admin Page</title>
<button onclick="emptycache()">Empty Cache</button> <button onclick="clearlist()">Clear Artists List</button> <button onclick="filldb()">Fill Artists List</button> <button onclick="bans()">List Banned Users</button>
<br />
<br />
<input name="comm" type="text" id="comm" size="64" />
<button onclick="enter()">Enter</button>
<br />
<textarea name="cons" cols="100" rows="20" readonly="readonly" id="cons"></textarea>
<br />
<br />
<strong><a href="javascript:help();">Show/hide help</a></strong><br />
<br />
<div id="help" style="visibility:hidden"><strong>List of commands:</strong><br />
emptycache - Empties the banner cache<br />
clearlist - Clears the artists table in the database<br />
filldb - Populates the artists table with all artists that have a logo<br />
bans - Lists banned users<br />
ban x - Bans user x<br />
unban x - Unbans user x<br />
<br />
Note: you can press enter to send a command instead of pressing the button
</div>