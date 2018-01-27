<div id="node-<?php print $node->nid; ?>" class="published-article node <?php print $node_classes; ?>">
  <div class="inner">
    <h2 class="title"><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
    <?php if ($submitted): ?><div class="meta"><span class="submitted"><?php print $submitted ?></span></div><?php endif; ?>
    <?php if ($node_top && !$teaser): ?>
      <div id="node-top" class="node-top row nested">
        <div id="node-top-inner" class="node-top-inner inner">
          <?php print $node_top; ?>
        </div>
      </div>
    <?php endif; ?>
    <div class="content clearfix">
      <?php if ($subhead): ?><h2 class="subhead"><?php print $subhead; ?></h2><?php endif; ?>
        <div id="article-image">
          <?php if ($image): ?><div id="image"><?php print $image ?></div><?php endif; ?>
        </div>
        <?php if ($node->field_backref_a0e889285eab265d39[0]['view']) { ?><div id="in-cluster" class="clearfix"><label class="excerpt">in cluster:</label> <?php print $node->field_backref_a0e889285eab265d39[0]['view']; ?></div><?php } ?>
        <div id="source-info">
          <span class="label"><?php print t('by') ?>:</span> <?php print $reporters; ?> | 
          <span class="label"><?php print t('publication date') ?>:</span> <?php print $pubdate; ?> |
    	    <?php if ($node->field_publication[0]['view']) { ?><span class="label"><?php print t('Publication') ?>:</span> <?php print $node->field_publication[0]['view'] ?>  | <?php } ?>
          <?php if ($node->field_volume[0]['view']) { ?><span class="label"><?php print t('volume') ?>:</span> <?php print $node->field_volume[0]['view'] ?>  | <?php } ?>
          <?php if ($node->field_journal[0]['view']) { ?> <span class="label"><?php print t('journal issue') ?>:</span> <?php print $node->field_journal[0]['view'] ?> | <?php } ?>
          <?php if ($node->field_pages[0]['view']) { ?><span class="label"><?php print t('pages') ?>:</span> <?php print $node->field_pages[0]['view'] ?><?php } ?>
        </div>
        <?php if ($node->field_excerpt[0]['view']) { ?><div id="excerpt"><label class="excerpt"><?php print t('Excerpt') ?>:</label> <?php print $node->field_excerpt[0]['view']; ?></div><?php } ?>
        <?php if ($node->field_docfile[0]['view']) { ?><div id="full-article"><label class="full-article"><?php print t('Full Article') ?>:</label> <?php print $node->field_docfile[0]['view']; ?></div><?php } ?>
        <?php if ($node->field_description[0]['view']) { ?><div id="description"><label class="description"><?php print t('Description') ?>:</label> <?php print $node->field_description[0]['view']; ?></div><?php } ?>
        <?php if ($rights) { ?><div id="rights-info"><label class="rights-info"><?php print t('Rights information') ?>:</label> <p><?php print $rights; ?></p></div><?php } ?>
      </div>
      <?php if ($terms): ?><div class="terms"><?php print $terms; ?></div><?php endif;?>
      
      <?php if ($links): ?>
        <div id="links">
          <label class="links"><?php print t('Links') ?>:</label>
          <br />
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
