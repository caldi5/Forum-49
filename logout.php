<script type="text/javascript">
	
	$.removeCookie('cn');
	$.removeCookie('c0');
	$.removeCookie('c1');
	$.removeCookie('c2');
	$.removeCookie('c3');


</script>



<?php

	session_start();
	if(session_destroy())
	{
		header("location: index.php");
	}
?>