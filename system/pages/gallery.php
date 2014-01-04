<script src="<?php echo WEBSITE;?>/public/js/ea.js" type="text/javascript"></script>
<script src="<?php echo WEBSITE;?>/public/js/slide.js" type="text/javascript"></script>
<style>
/*preload classes*/
.svw {width: 50px; height: 20px; background: #fff;}
.svw ul {position: relative; left: -999em;}

/*core classes*/
.stripViewer { 
position: relative;
overflow: hidden; 
border: 1px groove silver;  
padding: 1px;
overflow: hidden;
margin: 0 0 1px 0;
}
.stripViewer ul { /* this is your UL of images */
margin: 0;
padding: 0;
position: relative;
left: 0;
top: 0;
width: 1%;
list-style-type: none;
}
.stripViewer ul li { 
float:left;
}
.stripTransmitter {
overflow: auto;
width: 1%;
}
.stripTransmitter ul {
margin: 0;
padding: 0;
position: relative;
list-style-type: none;
}
.stripTransmitter ul li{
width: 20px;
float:left;
margin: 0 1px 1px 0;
}
.stripTransmitter a{
font: bold 10px Verdana, Arial;
text-align: center;
line-height: 22px;
background-color: #CCCCCC;
color: #fff;
text-decoration: none;
display: block;
}
.stripTransmitter a:hover, a.current{
background: #fff;
color: #ff0000;
}

/*tooltips formatting*/
.tooltip
{
padding: 0.5em;
background: #fff;
color: #000;
border: 5px solid #dedede;
}
</style>
<script type="text/javascript">
	$(window).bind("load", function() {
	$("div#mygalone").slideView()
});
</script>
<?php 
	$files = @glob("public/gallery/*.{png,jpg,bmp}", GLOB_BRACE);
	if(empty($files)) 
		alert("There are no pictures in the gallery.");
	else {
?>


<div id="mygalone" class="svw">
	<ul>
		<?php 
			foreach($files as $file) {
				echo '<li><img width="540" src="'.WEBSITE.'/'.$file.'"/></li>';
			}
		?>
	</ul>
</div>
<?php 
	}
?>
