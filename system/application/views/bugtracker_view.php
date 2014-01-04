<?php 
if($bug[0]['closed'] == 1) alert("This submission is closed.");
?>

<div class="bugtrackerBugTable">
	<div class="bugtrackerTitle"><?php echo $bug[0]['title']; ?></div>
	<label>Category</label> <?php echo bugtracker_getCategory($bug[0]['category']); ?><div style='clear: both;'></div>
	<label>Priority</label> <?php echo bugtracker_getPriority($bug[0]['priority']); ?><div style='clear: both;'></div>
	<label>Author</label> <a href="<?php echo WEBSITE; ?>"/index.php/character/view/<?php echo $bug[0]['name']; ?>"><?php echo $bug[0]['name']; ?></a><div style='clear: both;'></div>
	<label>Done</label> <?php echo $bug[0]['done'];?>%
	<br />
	<div class='bugtracker_text'>
	<?php echo $bug[0]['text']; ?>
	</div>
</div>