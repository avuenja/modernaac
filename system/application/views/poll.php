<?php if($poll['question']): ?>
<h1>Poll</h1>
<div id="poll">
	<h2><?php echo $poll['question']; ?></h2>
	<?php if(!$poll['voted']): ?>
	<form method="post">
		<input type="hidden" value="<?php echo $poll['poll_id']; ?>" name="poll_id" />
		<ul>
			<?php foreach($poll['answers'] as $k => $v): ?>
			<li>
				<label>
					<input type="radio" value="<?php echo $k; ?>" name="answer_id" />
					<?php echo $v; ?> </label>
			</li>
			<?php endforeach; ?>
		</ul>
		<input type="submit" class="button" value="Vote" />
	</form>
	<?php else: ?>
	<ul>
		<?php foreach($poll['answers'] as $k => $v): ?>
		<li><?php echo $v; ?> - <?php echo number_format(($poll['votes'][$k] * 100 / $poll['total']), 2); ?>%</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>
<?php endif; ?>
