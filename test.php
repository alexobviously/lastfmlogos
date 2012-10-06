<?php
for($i=1;$i<=25;$i++)
{
	echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=yellow&layout=OneCol&blackbg=on">');
	echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=yellow&layout=TwoCols&blackbg=on">');
	//echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=green&layout=OneCol&blackbg=on">');
	//echo('<img src="http://localhost/lfl/banner.php?user=alexobviously&nb='.$i.'&type=overall&color=green&layout=TwoCols&blackbg=on">');
}
/*function a($b)
{
	if($b>5)
		return $b*2;
	else
		return false;
}
for($i=0;$i<9;$i++)
{
	if(($c=a($i)))
		echo("B".$i.", ");
	else
		echo("A, ");
}*/
?>