<?php
include('classes/util/colors.php');
$coptions = "";
$y = 0;
for($x=0;$x<sizeof($cnames);$x++)
{
	$coptions .= '<option value="'.$cnames[$x].'">'.$cn2[$x].'</option>';
}
?>
<html>
<head>
	<title>Band Logos - LastFM banner</title>

	<link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>


<h2>Band logos - LastFM banner</h2>


<form name="bannerlink" action="link.php" method="get">
	<fieldset class="generate_form">
	<table>
	<tr>
	<td><nobr>Username :</nobr></td> <td><input type="text" name="user" value=""/></td>
	</tr><tr>
	<td><nobr>Number of logos :</nobr></td>
	<td>
		<select name="nb">
			<option value="5">5</option>
			<option value="10" selected>10</option>
			<option value="15">15</option>
			<option value="20">20</option>
			<option value="25">25</option>
            <option value="30">30</option>
			<option value="40">40</option>
            <option value="50">50</option>
		</select>
		</td>
	</tr><tr>
	<td><nobr>Type :</nobr></td>
	<td>
		<select name="type">
			<option value="overall">Overall</option>
			<option value="12month">Last year</option>
			<option value="6month">Last 6 months</option>
			<option value="3month">Last 3 months</option>
		</select>
		
	</td>
	</tr><tr>
	<td><nobr>Logo color :</nobr></td>
	<td>
		<select name="color">
			<option value="white">Black on White</option>
			<option value="black">White on Black</option>
			<option value="gray">Black on Gray</option>
			<option value="trans">Black on Transparent</option>
			<?php echo($coptions); ?></select>
	</td>
	</tr><tr>
	<td><nobr>Black background*</nobr></td>
	<td><input type="checkbox" name="blackbg" id="blackbg"></td>
	</tr><tr>
	<td><nobr>Layout :</nobr></td>
	<td>
		<select name="layout">
			<option value="OneCol">One Column</option>
			<option value="TwoCols">Two Columns</option>
		</select>
	</td>
	</tr>
	</table>
	* Only works with non-black/gray/white colors. The background will be transparent if you don't select this.</br><br/>
	<input type="submit" class="btn" value="Generate"/>
	</fieldset>
</form>

</body>
</html>