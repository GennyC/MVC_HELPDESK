<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>StartUI - Premium Bootstrap 4 Admin Dashboard Template</title>

	<link href="public/img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="public/img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="public/img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="public/img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
	<link href="public/img/favicon.png" rel="icon" type="image/png">
	<link href="public/img/favicon.ico" rel="shortcut icon">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
<link rel="stylesheet" href="public/css/lib/lobipanel/lobipanel.min.css">
<link rel="stylesheet" href="public/css/separate/vendor/lobipanel.min.css">
<link rel="stylesheet" href="public/css/lib/jqueryui/jquery-ui.min.css">
<link rel="stylesheet" href="public/css/separate/pages/widgets.min.css">
    <link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body class="with-side-menu dark-theme">

<div class="mobile-menu-left-overlay"></div>
	<nav class="side-menu">
	    <div class="side-menu-avatar">
	        <div class="avatar-preview avatar-preview-100">
	            <img src="public/img/avatar-1-256.png" alt="">
	        </div>
	    </div>
	    <ul class="side-menu-list">
	        <li class="brown">
	            <a href="index.php?axn=home">
	                <i class="font-icon font-icon-home"></i>
	                <span class="lbl">Overview</span>
	            </a>
	        </li>
        
        <!-- resto de menús normales -->

        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <li class="gold">
            <a href="index.php?axn=usuarios">
                <i class="font-icon font-icon-users"></i>
                <span class="lbl">Usuarios</span>
            </a>
        </li>
        <?php endif; ?>

        <li class="magenta">
            <a href="index.php?axn=tickets">
                <i class="font-icon font-icon-calend"></i>
                <span class="lbl">Tickets</span>
            </a>
        </li>
	        <li class="gold opened">
	            <a href="#">
	                <i class="font-icon font-icon-speed"></i>
	                <span class="lbl">Performance</span>
	            </a>
	        </li>
	        <li class="blue">
	            <a href="#">
	                <i class="font-icon font-icon-users"></i>
	                <span class="lbl">Community</span>
	            </a>
	        </li>
	        <li class="purple with-sub">
	            <span>
	                <i class="font-icon font-icon-comments active"></i>
	                <span class="lbl">Messages</span>
	            </span>
	            <ul>
	                <li><a href="#"><span class="lbl">Inbox</span><span class="label label-custom label-pill label-danger">4</span></a></li>
	                <li><a href="#"><span class="lbl">Sent mail</span></a></li>
	                <li><a href="#"><span class="lbl">Bin</span></a></li>
	            </ul>
	        </li>
	        <li class="orange-red with-sub">
	            <span>
	                <i class="font-icon font-icon-help"></i>
	                <span class="lbl">Support</span>
	            </span>
	            <ul>
	                <li><a href="#"><span class="lbl">Feedback</span></a></li>
	                <li><a href="#"><span class="lbl">FAQ</span></a></li>
	            </ul>
	        </li>
	        <li class="grey">
	            <a href="#">
	                <i class="font-icon font-icon-dashboard"></i>
	                <span class="lbl">Dashboards</span>
	            </a>
	        </li>
	        <li class="red">
	            <a href="#" class="label-right">
	                <i class="font-icon font-icon-contacts"></i>
	                <span class="lbl">Contacts</span>
	                <span class="label label-custom label-pill label-danger">35</span>
	            </a>
	        </li>
	        <li class="aquamarine">
	            <a href="#">
	                <i class="font-icon font-icon-build"></i>
	                <span class="lbl">Companies</span>
	            </a>
	        </li>
	        <li class="blue-dirty">
	            <a href="#">
	                <i class="font-icon font-icon-edit"></i>
	                <span class="lbl">Forms</span>
	            </a>
	        </li>
	        <li class="coral">
	            <a href="#">
	                <i class="font-icon font-icon-chart"></i>
	                <span class="lbl">Reports</span>
	            </a>
	        </li>
	        <li class="pink-red">
	            <a href="#">
	                <i class="font-icon font-icon-zigzag"></i>
	                <span class="lbl">Activity</span>
	            </a>
	        </li>
	        <li class="gold">
	            <a href="#">
	                <i class="font-icon font-icon-tablet"></i>
	                <span class="lbl">Tables</span>
	            </a>
	        </li>
	        <li class="magenta">
	            <a href="#">
	                <i class="font-icon font-icon-widget"></i>
	                <span class="lbl">Widges</span>
	            </a>
	        </li>
	        <li class="pink">
	            <a href="#">
	                <i class="font-icon font-icon-map"></i>
	                <span class="lbl">Maps</span>
	            </a>
	        </li>
	        <li class="blue-darker">
	            <a href="#">
	                <i class="font-icon font-icon-chart-2"></i>
	                <span class="lbl">Charts</span>
	            </a>
	        </li>
	        <li class="grey">
	            <a href="#">
	                <i class="font-icon font-icon-doc"></i>
	                <span class="lbl">Documentation</span>
	            </a>
	        </li>
	        <li class="blue-sky">
	            <a href="#">
	                <i class="font-icon font-icon-question"></i>
	                <span class="lbl">Help</span>
	            </a>
	        </li>
	        <li class="coral">
	            <a href="#">
	                <i class="font-icon font-icon-cogwheel"></i>
	                <span class="lbl">Settings</span>
	            </a>
	        </li>
	        <li class="magenta">
	            <a href="#">
	                <i class="font-icon font-icon-user"></i>
	                <span class="lbl">Profile</span>
	            </a>
	        </li>
	        <li class="blue-dirty">
	            <a href="#">
	                <i class="font-icon font-icon-notebook"></i>
	                <span class="lbl">Tasks</span>
	            </a>
	        </li>
	        <li class="aquamarine">
	            <a href="#">
	                <i class="font-icon font-icon-mail"></i>
	                <span class="lbl">Contact form</span>
	            </a>
	        </li>
	        <li class="pink">
	            <a href="#">
	                <i class="font-icon font-icon-users-group"></i>
	                <span class="lbl">Group</span>
	            </a>
	        </li>
	        <li class="gold">
	            <a href="#">
	                <i class="font-icon font-icon-picture-2"></i>
	                <span class="lbl">Gallery</span>
	            </a>
	        </li>
	        <li class="brown">
	            <a href="#">
	                <i class="font-icon font-icon-event"></i>
	                <span class="lbl">Event</span>
	            </a>
	        </li>
	        <li class="red">
	            <a href="#">
	                <i class="font-icon font-icon-case-2"></i>
	                <span class="lbl">Project</span>
	            </a>
	        </li>
	    </ul>
	</nav>
    
    </body>
</html>