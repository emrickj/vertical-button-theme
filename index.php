<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
   $name = $_POST['name'];
   $phone = $_POST['phone'];
   $email = $_POST['email'];
   $message = $_POST['message'];
   
   //ini_set('display_errors', 'On');
   //error_reporting(E_ALL);

   if(isset($_GET['u']) && $_GET['u']!="") $b = "_".$_GET['u'];
      else $b="";
   // echo "--".$b."--";

   if(isset($_GET['p'])) $p = $_GET['p'];
      else $p="1";

   if(isset($_GET['w'])) $w = $_GET['w'];
      else $w="1";

   $xml=simplexml_load_file("data/website.xml") or die("Error: Cannot create object");
   $xml2=simplexml_load_file("data/website2.xml") or die("Error: Cannot create object");
   //print_r($xml);
   //echo $xml->image[1];
   //echo $_SERVER['HTTP_HOST']."\n";
   //echo $_SERVER['SCRIPT_NAME'];
   
   require 'dspmenu.php';
   require 'dspcnt.php';
      
   if($_SERVER['HTTPS']) $mps="https://"; else $mps="http://";
   $mainpage = $mps.$_SERVER['HTTP_HOST'].str_replace("/index.php","",$_SERVER['SCRIPT_NAME']);
      if ($p=="2") echo "<link rel='prev' href='".$mainpage."'>"; else
   if ($p > "1") echo "<link rel='prev' href='?p=".($p - 1)."'>";
   if (($p < "6") && strlen($xml->page[intval($p)]->name)>2) echo "<link rel='next' href='?p=".($p + 1)."'>";
?>
	<title><?php echo strip_tags($xml->title) ?></title>
<style>
t1 { white-space: pre-wrap;}
<?php echo $xml->style ?>
</style>
</head>
<body id="demo">
<nav class="navbar navbar-default navbar-fixed-top hidden-sm hidden-md hidden-lg">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="<?php echo $mainpage ?>"><?php echo $xml->title ?></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <?php displayMenu($p) ?>
      </ul>
    </div>
  </div>
</nav>
   <div class="container">
      <div class="row">
         <div class="hidden-sm hidden-md hidden-lg" style="padding-top: 50px;"></div>
         <div class="col-sm-3" style="padding: 20px" id="title">
         <b><h2><center><?php echo $xml->title ?></center></h2></b>
         </div>
         <div class="col-sm-9">
            <?php
            if ($w=="1")
               if(strlen($xml->page[$p-1]->image)>4)
                  echo "<img class='img-responsive' src='".$xml->page[$p-1]->image."'>\n";
            if ($w=="2")
               if(strlen($xml2->page[$p-1]->image)>4)
                  echo "<img class='img-responsive' src='".$xml2->page[$p-1]->image."'>\n";
            ?>
            <br>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3 hidden-xs">
                  <br>
                  <div class="btn-group-vertical btn-group-lg">
                      <?php
                     for($i=1;$i<=6;$i++) {
                       if($i==$p && $w=="1" && $name=="") $bs="active"; else $bs="";
                       if($i==1) echo "<a href='".$mainpage."' class='btn btn-primary ".$bs."'>
                      ".ic_html($xml->page[0]->name)."</a>";
                       else if(strlen($xml->page[$i-1]->name)>2) 
                          echo "<a href='?p=".$i."' class='btn btn-primary "
                          .$bs."'>" . ic_html($xml->page[$i-1]->name) . "</a>";
                     }
                     ?>
                     <ul class="dropdown-menu" role="menu">
                     <?php displayMenu($p,"active",$w,2) ?>
                     </ul>
                     <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                     <?php echo strip_tags($xml2->title) ?> <span class="caret"></span></button>
                  </div>
                  <br>
                  <br>
         </div>
         <div class="col-sm-9">
           <div class="panel panel-primary">
               <div class="panel-body">
                     <?php
                        if($name=="") dispContents($p,ltrim($b,"_"),$w);
                        else if(sendDb($name,$phone,$email,$message))
                                echo "<b>Contact information submitted.  We will contact you as soon as possible.</b>";
                             else echo "<b>Missing Name or Contact Info.</b>";
                        echo "\n";
                        if((($xml->page[$p-1]['type']=="form" && $w=="1") || 
                           ($xml2->page[$p-1]['type']=="form" && $w=="2")) && $name=="") {
                     ?>
                           <form class="form-horizontal" role="form" method="post">
                              <div class="form-group">
                                 <label class="control-label col-sm-3" for="name">Name:</label>
                                 <div class="col-sm-6">
                                    <input type="text" class="form-control" name="name">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="control-label col-sm-3" for="phone">Contact&nbsp;Phone&nbsp;#:</label>
                                 <div class="col-sm-6">
                                    <input type="text" class="form-control" name="phone">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="control-label col-sm-3" for="email">Email Address:</label>
                                 <div class="col-sm-6">
                                    <input type="email" class="form-control" name="email">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="control-label col-sm-3" for="message">Message:</label>
                                 <div class="col-sm-6">
                                    <textarea class="form-control" rows="5" name="message"></textarea>
                                    <br>
                                    <input type="submit" class="btn btn-info" value="Submit" disabled>
                                 </div>
                              </div>
                           </form><?php
                        }
                        if($xml->page[$p-1]['type']=="comments" && $w=="1" && $name=="") {
                     ?>
                           <!-- begin htmlcommentbox.com -->
                           <div id="HCB_comment_box"><a href="http://www.htmlcommentbox.com">HTML Comment Box</a> is loading comments...</div>
                           <link rel="stylesheet" type="text/css" href="http://www.htmlcommentbox.com/static/skins/default/skin.css" />
                           <script type="text/javascript" language="javascript" id="hcb"> /*<!--*/ if(!window.hcb_user){hcb_user={  };} (function(){s=document.createElement("script");s.setAttribute("type","text/javascript");s.setAttribute("src", "http://www.htmlcommentbox.com/jread?page="+escape((window.hcb_user && hcb_user.PAGE)||(""+window.location)).replace("+","%2B")+"&opts=470&num=10");if (typeof s!="undefined") document.getElementsByTagName("head")[0].appendChild(s);})(); /*-->*/ </script>
                           <!-- end htmlcommentbox.com --><?php
                        }
                     ?>
               </div>          
           </div>
         </div>
      </div>
      <center><h6>This website was created using <a href="https://www.gem-editor.com">GEM</a>.</h6></center>
   </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
<script>

//if ($(".container").height() < window.outerHeight)
//   $(".container").height(window.outerHeight);

</script>
</html>
