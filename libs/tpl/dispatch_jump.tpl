{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>跳转提示</title>
    <link rel="stylesheet" href="/public/admin/css/icon.css" />
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #eff3f7; font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif; color: #333; font-size: 16px; }
        .system-message{ padding:50px 48px; background:#fff; text-align:center; border:1px solid #eee; box-shadow:#eee 0 0 20px; margin:100px auto; width:300px; }
        .system-message h1{ font-size:50px; color:#0d93f0; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
        .system-message .jump{ padding-top: 10px; color:#999; }
        .system-message .jump a{ color: #0d93f0; }
        .system-message .success,.system-message .error{ line-height: 1.8em; font-size:20px; }
		.system-message .success{ color:#0d93f0;}
		.system-message .error{ color:red;}
		.system-message .icon-frown-o{ color:red;}
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display: none; color:#999; }
		.system-message #wait{ color:#0d93f0;}
    </style>
</head>
<body>
    <div class="system-message">
        <?php switch ($code) {?>
            <?php case 1:?>
            <h1 class="icon-smile-o"></h1>
            <p class="success"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
            <?php case 0:?>
            <h1 class="icon-frown-o"></h1>
            <p class="error"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
        <?php } ?>
        <p class="detail"></p>
        <p class="jump">
            页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
        </p>
    </div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
