<?	
	echo $html->css('wmd.css');
	echo $javascript->link('wmd/showdown.js');
	echo $javascript->link('wmd/wmd.js');
	echo $javascript->link('jquery/jquery.js');
?>
<script> //<![CDATA[    
  // When the page is ready
  $(document).ready(function(){
    $(".comment_area").hide();
    
    $(".comment_actions a").click(function(event){
      $(this).parents("div").prev(".comment_area").toggle();
	  $(this).toggle();
      
      // Stop the link click from doing its normal thing
      event.preventDefault();
    });

  });
//]]></script>


<div id="question" class="question">
	<div class="content_container wrapper">
		<div class="content_actions" style="float: left; width: 55px; margin-right: 10px;">
			<?php
                echo $html->image('arrow_up.png', array('alt' => 'Vote Up', 'url' => '/vote/' . $question['Post']['public_key'] . '/up'));
			?>
			<span class="large_text quiet" style="display: block; padding: 0px; margin: 0px;"><strong><?=$question['Post']['votes'];?></strong></span>
			<?php
                echo $html->image('arrow_down.png', array('alt' => 'Vote Down', 'url' => '/vote/' . $question['Post']['public_key'] . '/down'));
	        ?>

		</div>
		<div class="question_content" style="float: left; width: 600px;">
			<h2><?=$question['Post']['title'];?></h2>
			<?=$question['Post']['content'];?>
		</div>
	</div>

	<div class="post_actions wrapper">

		<div style="width: 100px; float: left;">
        <? if($question['Post']['user_id'] != $session->read('Auth.User.id')) { ?>
        <?=$html->link(
				'flag',
				'/flag/' . $question['Post']['public_key']
			 );
        ?>
        <? } ?>
		<? if($question['Post']['user_id'] == $session->read('Auth.User.id') || isset($rep_rights) || $admin) { ?>
		| 
		<?=$html->link(
				'edit',
				'/questions/' . $question['Post']['public_key'] . '/' . $question['Post']['url_title'] . '/edit');
		}
		?>

        <?php if($admin): ?>
               | <a href="/posts/delete/<?php echo $question['Post']['id'];?>">del</a>
        <?php endif; ?>

		</div>

		<?if(!empty($question['Post']['last_edited_timestamp'])) { ?>
			<div style="width: 275px; float: left; text-align: center;">
				edited <strong><?=$time->timeAgoInWords($question['Post']['last_edited_timestamp']);?></strong>
			</div>
		<? } ?>

		<div class="user_info wrapper">
			<div style="float: left;">
				<div class="thumb_with_border">
			<a href="/users/<?=$question['User']['public_key'];?>/<?=$question['User']['username'];?>"><?
				$thumbnail->show(array(
						        'save_path' => $_SERVER['DOCUMENT_ROOT'] . '/app/webroot/img/thumbs',
						        'display_path' => '/img/thumbs',
						        'error_image_path' => '/img/answerAvatar.png',
						        'src' => '/app/webroot' . $question['User']['image'],
						        'w' => 25,
								'h' => 25,
								'q' => 100,
								'border' => '1px solid gray')
				); 
			?></a>
				</div>
				<div style="float: left; line-height: .9;">
					<div>
			<?=$html->link(
					$question['User']['username'],
					'/users/' . $question['User']['public_key'] . '/' . $question['User']['username']
				);
			?> 
			<span style="font-size: 8pt;">&#8226;</span>
			<h4 style="display: inline;"><?=$question['User']['reputation'];?></h4>
					</div> 
			<span class="quiet">asked <?=$time->timeAgoInWords($question['Post']['timestamp']);?></span>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>

		<div id="tags" style="clear: left;">
			<? foreach($question['Tag'] as $tag) { ?>
				<div class="tag">
					<?=$html->link(
							$tag['tag'],
							'/tags/' . $tag['tag']
						);
					?>
				</div>
			<?  } ?>
		</div>

	</div>

	<? if(!empty($question['Comment'])) { ?>
		<div id="question_comments">
			<? foreach($question['Comment'] as $comment) { ?>
			<div class="comment">
				<?=$comment['content']?> &ndash;

				<?=$html->link(
						$comment['User']['username'],
						'/users/' . $comment['User']['public_key'] . '/' . $comment['User']['username']
					);
				?>
				<span class="quiet"><?=$time->timeAgoInWords($comment['timestamp']); ?></span>
			</div>
			<? } ?>
		</div>

	<? } ?>

	<div id="comment_<?=$question['Post']['public_key'];?>" class="comment_area">
		<?=$form->create(null, array(
				'url' => '/questions/' . $question['Post']['public_key'] . '/comment')
			);
		?>
		<?=$form->text('Comment.content', array('class' => 'comment_input'));?>
		<?=$form->end('Comment');?>
	</div>
	<div class="comment_actions">
	<?=$html->link(
			'add comment',
			'#');
	?>
	</div>

</div>

<div id="answers">
	<h2>Answers</h2>
	<hr/>
	<? foreach($answers as $answer) { ?>
	<div class="<?=($answer['Answer']['status'] == 'correct') ? 'answered' : 'answer';?>" id="a_<?=$answer['Answer']['public_key']?>">

		<div class="content_container wrapper">
			<div class="content_actions" style="float: left; width: 55px; margin-right: 10px;">
				<?=$html->link(
				    $html->image("arrow_up.png", array("alt" => "Vote Up")),
				    '/vote/' . $answer['Answer']['public_key'] . '/up',
				    null, null, false
				);
				?>
				<span class="large_text quiet" style="display: block; padding: 0px; margin: 0px;"><strong><?=$answer['Answer']['votes'];?></strong></span>
				<?=$html->link(
				    $html->image("arrow_down.png", array("alt" => "Vote Down")),
				    '/vote/' . $answer['Answer']['public_key'] . '/down',
				    null, null, false
				);
				?>
				<? if($question['Post']['user_id'] == $session->read('Auth.User.id') && $answer['Answer']['status'] != 'correct' && $question['Post']['status'] != 'closed') {?>
				<div class="checkmark">
					<?=$html->link(
							'',
							'/questions/' .  $answer['Answer']['public_key'] . '/' . 'correct'
						);
					?>
				</div>
				<? } if($answer['Answer']['status'] == 'correct') {
					echo $html->image('checkmark_green.png');
				} ?>
				
			</div>
			<div class="answer_content" style="float: left; width: 600px;">
				<?=$answer['Answer']['content'];?>
			</div>
		</div>

		<div class="post_actions wrapper">
			<div class="user_info wrapper">
				<div style="float: left;">
				<div class="thumb_with_border">
			<a href="/users/<?=$answer['User']['public_key'];?>/<?=$answer['User']['username'];?>"><?
				$thumbnail->show(array(
						        'save_path' => $_SERVER['DOCUMENT_ROOT'] . '/app/webroot/img/thumbs',
						        'display_path' => '/img/thumbs',
						        'error_image_path' => '/img/answerAvatar.png',
						        'src' => '/app/webroot' . $answer['User']['image'],
						        'w' => 25,
								'h' => 25,
								'q' => 100,
								'border' => '1px solid gray')
				); 
			?></a>
				</div>
				<div style="float: left; line-height: .9;">
					<div>
			<?=$html->link(
					$answer['User']['username'],
					'/users/' . $answer['User']['public_key'] . '/' . $answer['User']['username']
				);
			?> 
			<span style="font-size: 8pt;">&#8226;</span>
			<h4 style="display: inline;"><?=$answer['User']['reputation'];?></h4>
					</div> 
			<span class="quiet">answered <?=$time->timeAgoInWords($answer['Answer']['timestamp']);?></span>
				</div>
				<div style="clear: both;"></div>
			</div>
			</div>
	
			<?=$html->link(
					'flag',
					'/flag/' . $answer['Answer']['public_key']
				);
			?>
			<span class="quiet">|</span> 
			<?=$html->link(
					'link',
					'http://ej.com/questions/'
					. $question['Post']['public_key'] . '/' 
					. $question['Post']['url_title'] 
					. '#a_' . $answer['Answer']['public_key']
				);
			?>
			<? if($answer['Answer']['user_id'] == $session->read('Auth.User.id') || isset($rep_rights)) { ?>
			<span class="quiet">|</span>
			<?=$html->link(
					'edit',
					'/answers/' . $answer['Answer']['public_key'] . '/edit');
			}
			?>
	
		</div>

		<? if(!empty($answer['Comment'])) { ?>
			<div id="comments">
				<? foreach($answer['Comment'] as $comment) { ?>
				<div class="comment">
					<?=$comment['content']?> &ndash; 
				
					<?=$html->link(
							$comment['User']['username'],
							array('controller' => 'users', 'action' => 'view', $comment['User']['public_key'], $comment['User']['username'])
						);
					?>
					<span class="quiet"><?=$time->timeAgoInWords($comment['timestamp']); ?></span>
				</div>
				<? } ?>
			</div>
			
		<? } ?>
	
		<div id="comment_<?=$answer['Answer']['public_key'];?>" class="comment_area">
			<?=$form->create(null, array(
					'url' => '/questions/' . $answer['Answer']['public_key'] . '/comment')
				); 
			?>
			<?=$form->text('Comment.content', array('class' => 'comment_input'));?> 
			<?=$form->end('Comment');?>
		</div>
		<div class="comment_actions">
		<?=$html->link(
				'add comment',
				'#');
		?>
		</div>	
	
	</div>
	<? } ?>
</div>

<div id="user_answer">
	<? if ($session->read('errors')) {
			foreach($session->read('errors.errors') as $error) {
				echo '<div class="error">' . $error . '</div>';
			}
		}
	?>
	<h3>Your Answer</h3>
	<?=$form->create(null, array(
			'url' => '/questions/' . $question['Post']['public_key'] . '/' . $question['Post']['url_title'] . '/answer')
		); ?>
	<div id="wmd-button-bar" class="wmd-panel"></div>
	<?=$form->textarea('content', array(
		'id' => 'wmd-input', 'class' => 'wmd-panel', 'value' => $session->read('errors.data.Post.content')
		));
	 ?>

	<div id="wmd-preview" class="wmd-panel"></div>

	<? if(!$session->check('Auth.User.id')) { ?>
	<h2>Who Are You?</h2>
	<span class="quiet">Have an account already? <a href="/login">Login before answering!</a></span><br/>
		<?=$form->label('name');?><br/>
		<?=$form->text('User.username', array('class' => 'big_input medium_input '));?><br/>
		<?=$form->label('email');?><br/>
		<?=$form->text('User.email', array('class' => 'big_input medium_input '));?><br/>		
	<? } ?>
	
	<?$recaptcha->display_form('echo');?>
	
	<br/>
	<?=$form->end('Answer');?>
</div>
