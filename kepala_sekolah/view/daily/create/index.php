<?php  

	$timeOut        = $_SESSION['expire_paud'];
    
  	$timeRunningOut = time() + 5;

  	$timeIsOut = 0;

	$diMenu = "create_daily";
	echo "OK";

?>

<script type="text/javascript">
	
	$(document).ready(function() {

	    $("#aList1").click();

	    $("#create_data").css({
	        "background-color" : "#ccc",
	        "color" : "black"
      	});

	    $("#isiMenu").css({
	      "margin-left" : "5px"
	    });

  	})  

</script>