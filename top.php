<?php $utility = new utility(); ?>
<style>
.paratext
{
	font-family:verdana,Arial, Helvetica, sans-serif;
	font-weight:bold;
	color:#000000;
	font-size:16pt;
	text-align:center;
	padding-left:10px;
}
.paratext1
{
	font-family:verdana,Arial, Helvetica, sans-serif;
	font-weight: bold;
	color:#FFFFFF;
	font-size:10pt;
	text-align:center;
	padding-left:10px;
	line-height:20px;
}
.paratext_small
{
	font-family:verdana,Arial, Helvetica, sans-serif;
	font-weight: normal;
	color:#FFFFFF;
	font-size:8pt;
	text-align:right;
	padding-left:10px;
	line-height:20px;
}
</style>
<table width="100%" align="center" border="0"  cellpadding="0" cellspacing="0">
	<tr>
    	<td align="center" colspan="3" valign="top" style="padding-top:5px; padding-bottom:10px;height:100px; background-color:#ffffff;"><!--#0EA3F1-->
		<!--<img src="images/header-logo.jpg" width="250" height="100" border="0" />-->
        <a href="javascript:showindex();"><font style="animation-iteration-count:infinite" color="#000099" size="6">GetETutor</font></a>
		</td>
	</tr>
	<tr bgcolor="#1A449B" > 
		<td class="paratext1" width="20%">Welcome <?php echo $_SESSION['username'];?>!</td>
		<td class="paratext1" width="60%"><span>Administrator Control Panel</span></td>
		<td class="paratext1" width="20%">
        
		<?php if($_SESSION['username']!=''){?><a href="javascript:logout();"><span><font color="#FFFFFF">Log Out</font></span></a><?php }?>
        </td>
	</tr>
</table>
