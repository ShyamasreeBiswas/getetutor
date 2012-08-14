<?php 
$link = mysql_connect("localhost", "root", "");
mysql_select_db("getetutor", $link);

$q=$_GET["q"];

$query_course = "select * from course where `department_id`='".$q."'";
$row_course = mysql_query($query_course);
?>

			  <select name="course_id">
				<option value="">---Select----</option>	
					<?php while($res_course = mysql_fetch_array($row_course)) {?>
					<option value="<?php echo $res_course['course_id'];?>"><?php echo $res_course['course_name'];?>	</option>
					<?php }?>
					
  				</select>