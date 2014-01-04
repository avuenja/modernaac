<script>
	function fetch_alerts() {
		$('#alerts').html($('#waiter').html());
		$.ajax({
			  url: '<?php echo WEBSITE; ?>/index.php/admin/fetch_alerts',
			  type: 'post',
			  success: function(data) {
			  	$('#alerts').html(data);
			  }
		});
	}

	function fetch_news() {
		$('#news').html($('#waiter').html());
		$('#newsButton').hide();
		$.ajax({
			  url: '<?php echo WEBSITE; ?>/index.php/admin/fetch_news',
			  type: 'post',
			  success: function(data) {
			  	$('#news').html(data);
			  }
		});
	}

	function checkVersion() {
		$('#updates').html($('#waiter').html());
		$('#updateButton').hide();
		$.ajax({
			url: '<?php echo WEBSITE; ?>/index.php/admin/check_version',
			  type: 'post',
			  success: function(data) {
			  	$('#updates').html(data);
			  }
		});
	}

	function isBlacklisted() {
		$('#blacklist').html($('#waiter').html());
		$.ajax({
			url: '<?php echo WEBSITE; ?>/index.php/admin/isBlacklisted',
			  type: 'post',
			  success: function(data) {
			  	$('#blacklist').html(data);
			  }
		});
	}

	function getUsers() {
		$.ajax({
			url: '<?php echo WEBSITE; ?>/index.php/admin/getUsers',
			  type: 'post',
			  success: function(data) {
			  }
		});
	}

	function getRSS() {
		$('#rss').html($('#waiter').html());
		$('#rssButton').hide();
		$.ajax({
			url: '<?php echo WEBSITE; ?>/index.php/admin/getRSS',
			  type: 'post',
			  success: function(data) {
				$('#rss').html(data).css({'height' : '200px'});
			  }
		});
	}

	function checkVAPus() {
		$.ajax({
			url: '<?php echo WEBSITE; ?>/index.php/admin/vapusListed',
			  type: 'post',
			  success: function(data) {
			  		$('#vapuslisted').html(data);
			  }
		});
	}
	
	$(document).ready(function() {
			/*fetch_alerts();
			isBlacklisted();
			getUsers()*/
			checkVAPus();
		});

</script>
<div class='message'>
	<div class='title'>Modern AAC Support</div>
	<div class='content'>
		<center><iframe src="http://www.google.com/talk/service/badge/Show?tk=z01q6amlq2me3q2btglfke5d23u2toehps4ooklu943i9vub8ce8hdh5kafprs7si2fkittulgh9q0n0hgsnobfruqcv91a7h8brq05dkmua95v5sbmrc1q5m58lnisngop8mg48u3c1fn06m0kpflm65agj28jn8l7h4eobvd1eq1778oa9lplmh5pl6i9p44g&amp;w=300&amp;h=18" frameborder="0" allowtransparency="true" width="300" height="18"></iframe></center>
	</div>
	<div class='message'>Please write only with serious problems! English Only!</div>
</div><br/>

<fieldset style='padding: 7px;'>
<legend>VAPus Listed?</legend>
<div id="vapuslisted"></div>
</fieldset>
<br />

<fieldset style='padding: 7px;'>
<legend style="padding: 5px; font-size: 14px"><strong>ModernAAC SVN Updates RSS</strong></legend>
<center id='rssButton'><button  onClick='getRSS()' class='ide_button'>Fetch Newest Updates RSS</button><br /><br/></center>
<div id="rss" style="overflow: auto"><b><center>Click button above to fetch RSS from SVN server.</center></b></div>
</fieldset>

