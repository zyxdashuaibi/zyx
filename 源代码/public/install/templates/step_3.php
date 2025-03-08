<?php if(!defined('IN_INSTALL')) exit('Request Error!'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>萤火商城 安装向导 - 执行配置文件</title>
<link href="templates/style/install.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/common.js"></script>
</head>
<body>
<div class="header"></div>
<div class="mainBody">
	<div class="text">
		<h4>正在安装...</h4>
		<div id="install"></div>
	</div>
</div>
<div class="footer"> <span class="step3"></span> <span class="copyright"><?php echo $cfg_copyright; ?></span> </div>
</body>
</html>