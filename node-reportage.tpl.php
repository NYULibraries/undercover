<h1>Reportage</h1>
<div id="node-<?php print $node->nid; ?>" class="node <?php print $node_classes; ?>">
  <div class="inner">
    <?php print $picture ?>
    <?php if ($page == 0): ?>
      <h2 class="title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
    <?php endif; ?>
    <?php if ($submitted): ?>
    <div class="meta">
      <span class="submitted"><?php print $submitted ?></span>
    </div>
    <?php endif; ?>
    <?php if ($node_top && !$teaser): ?>
    <div id="node-top" class="node-top row nested">
      <div id="node-top-inner" class="node-top-inner inner">
        <?php print $node_top; ?>
      </div><!-- /node-top-inner -->
    </div><!-- /node-top -->
    <?php endif; ?>

    <div class="content clearfix">
    
                                
     <div id="reportage-left">
		<?php if ($image): ?>
			<div id="image">
				<?php print $image ?>
			</div>
			
			 <?php if ($terms): ?>
	    <div class="terms terms-inline"><h3><?php print t( 'Tags' ) ?></h3><?php print $terms ?></div>
	  <?php endif;?>
			
		<?php endif; ?>
		
		<?php if($description): ?>
		<div class="description">
			<h3><?php print t( 'Description' ) ?></h3>
			<?php print $description ?>
		</div>
		<?php endif;?>
		<?php if($reporters): ?>
			<div id="reporters"><h3><?php print t( 'Reporters' ) ?></h3>
				<?php print $reporters ?></div>
		<?php endif;?>
		<?php if ($xlinks): ?>
			<div id="xlinks"><h3><?php print t( 'Links' ) ?></h3>
			<?php print $xlinks ?></div>
   	<?php endif;?>
		<?php if($media): ?>
			<div id="media">
				<h3><?php print t( 'Media History' ) ?></h3>
				<?php print $media ?>
			</div>
		<?php endif;?>
		<?php if($supplementary): ?>
			<div id="supplementary">
				<?php // print t( 'Primary Documents' ) // aof1 Jul 16, 2012 ?>
				<h3><?php print t( 'Additional Resources' ) ?></h3>
				<?php print $supplementary ?>
			</div>
		<?php endif;?>

		<?php if($effects): ?>
			<div id="effect">
				<h3><?php print t( 'Effects and Outcomes' ) ?></h3>
				<?php print $effects ?>
			</div>
		<?php endif; ?>
	</div>
	<div id="reportage-right">
		<h3><?php print t( 'Articles, Books, Video/Film' ) ?></h3>
		<div id="articles">
			<?php print $articles ?>
		</div>
	</div>
     
     
    </div>

    <?php if ($links): ?>
    <div class="links">
      <?php print $links; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php if ($node_bottom && !$teaser): ?>
  <div id="node-bottom" class="node-bottom row nested">
    <div id="node-bottom-inner" class="node-bottom-inner inner">
      <?php print $node_bottom; ?>
    </div>
  </div>
  <?php endif; ?>
</div>