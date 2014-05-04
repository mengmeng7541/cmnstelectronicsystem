<script src="/js/jquery-1.8.3.min.js"></script>
<html>
	<head>
		
	</head>
	<body>
	<form action="http://eadm.ncku.edu.tw/welldoc/ncku/iftwd/doSignIn.php" method="POST">
	</form>
	
	</body>
</html>
<script>

$(document).ready(function(){

	function clockIn()
	{
		$.post("http://eadm.ncku.edu.tw/welldoc/ncku/iftwd/doSignIn.php", {
            'Content-Type': 'application/json',
            data: {
                fn: "signIn",
                mtime: "A", 
                psnCode: "10210058",
                password: "cysh1204"
            }
        }).always(function(data){
        	console.log(data);
        });
		
	}
	clockIn();

	
});
</script>