<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{title}</title>
<meta name="description" content="{description}">
<meta name="keywords" content="{keyword}">
{head}
</head>
<body>
{navigation} {menu}
<section id="main-content" class="<?php if(get_cookie("smsLIBRARYAdminSidebarToggleBox") == 'YES'): echo 'merge-left'; endif; ?>">
  <section class="wrapper"> {content} </section>
  {footer} </section>
{footer_js}
</body>
</html>
