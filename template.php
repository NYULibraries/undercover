<?php

/*
 * This function added by Brian 2011-03-09
 */
function undercover_preprocess_page( &$vars ) {
  /** Why is this here? why this? aof1 */
  if ( $GLOBALS['user']->uid == 0 ) {
    $vars['log_in_out_link'] = l( t('Login'), 'user/login', array('attributes' => array('title' => t('Login to comment or contribute'), 'class' => 'login-link' ) ) );
  }
  else {
    $vars['log_in_out_link'] = l( t('Logout'), 'logout', array('attributes' => array('title' => t('Logout'), 'class' => 'logout-link' ) ) );
  }
}

function undercover_preprocess_node(&$vars) {
  if ( preg_match('/(reportage|cluster)/i', $vars['node']->type ) ) {
    undercover_preprocess_reportage_and_cluster($vars);
  }
  elseif ($vars['node']->type == 'published_article') {
    undercover_preprocess_published_article($vars);
  }
}

function undercover_preprocess_published_article(&$vars) {
  // https://jira.nyu.edu/jira/browse/UNDERCOVER-38
  $node = $vars['node'];  
  if ( $vars['node']->field_reporter ) {
    $reps = '';
    $j = count($vars['node']->field_reporter);
    $i = 1;
    foreach ( $vars['node']->field_reporter as $rep ) {
        $reps .= $rep['view'];
        if ( $i != $j) {
          $reps .= ', ';
        }
        $i++;
    }
    
    $vars['reporters'] = str_replace('&amp;', '&', $reps);
  
  }
      
  $vars['image'] = undercover_render_representative_image($vars['node']);
  
  $vars['links'] = undercover_render_xlinks($node);  

  if ($vars['node']->field_subhead[0]['view']) {
    $vars['subhead'] = $vars['node']->field_subhead[0]['view'];
  }

  if ($vars['node']->field_pubdate) {
    $vars['pubdate'] = date( 'F j, Y', strtotime( $vars['node']->field_pubdate[0]['value']));
  }
	
  if ($vars['node']->field_docsource) {
    $vars['rights'] = $vars['node']->field_docsource[0]['view'];
  }
}

function undercover_preprocess_reportage_and_cluster(&$vars) {

  $vars['page'] = 1;
   
  /** Reportage-specific logic here */
  $g = $all_reporters = array();
    
  /** Need to get all descendent articles - articles of sub-reportages */
  if ( $vars['node']->field_sub_reportage ) {
      
    foreach( $vars['node']->field_sub_reportage as $subrep ) {
      
      $subrep_node = node_load( $subrep['nid'] );
        
      foreach( $subrep_node->field_document as $doc ) {
        
        $g[] = $doc;

      }
    }
  }
  
  $documents = array_merge( (array)$g, (array)$vars['node']->field_document);

  $articles = undercover_render_documents($documents, $all_reporters);
  
  $vars['articles'] = undercover_render_articles($articles, $sorttmp);

  $vars['reporters'] = undercover_render_reporters($all_reporters);
    
  $vars['description'] = $vars['node']->content['body']['#value'];
    
  $vars['xlinks'] = undercover_render_xlinks($vars['node']);
    
  $vars['supplementary'] = undercover_render_supplementary($vars['node']);
    
  $vars['media'] = undercover_render_media($vars['node']);
    
  $vars['image'] = undercover_render_representative_image($vars['node']);
    
  $vars['effects'] = undercover_render_effects($vars['node']);

   //print '<div style="display: none; visibility: hidden;">';
   //print undercover_render_articles(undercover_render_documents($g, $all_reporters), array());
   //print '</div>';
  
}

function undercover_preprocess_search_result(&$variables) {

  $result = $variables['result'];
  $variables['url'] = check_url($result['link']);
  $variables['title'] = check_plain($result['title']);

  $info = array();
  if (!empty($result['type'])) {
    $info['type'] = check_plain($result['type']);
  }
  if (!empty($result['user'])) {
    $info['user'] = $result['user'];
  }
  if (!empty($result['date'])) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    $info = array_merge($info, $result['extra']);
  }

  if (!empty($result['fields']['nid'])) {
    $resultNode = node_load(array("nid" => $result['fields']['nid']));

    if ( isset($resultNode->field_pubdate ) && is_array( $resultNode->field_pubdate ) ) {
      $variables['pubdate'] = date_format_date( date_make_date( $resultNode->field_pubdate[0]['value'] ), 'custom', 'l, F j, Y' );
    }

    if (isset($resultNode->field_doctype) && is_array($resultNode->field_doctype)) {
      $doctypes = array();
      foreach ($resultNode->field_doctype as $doctype) {
        $doctypes[] = $doctype['value'];
      }
      $variables['doctype'] = implode($doctypes, ', ');
    }

    if (isset($resultNode->field_docsource) && is_array($resultNode->field_docsource)) {
      $docsources = array();
      foreach ($resultNode->field_docsource as $docsource) {
        $docsources[] = $doctype['value'];
      }
      $variables['docsource'] = implode($docsources, ', ');
    }

    if ( isset( $resultNode->field_reporter ) && is_array( $resultNode->field_reporter ) && module_exists('name') ) {
      $reporters = array();
      foreach ($resultNode->field_reporter as $reporter) {
        $reporters[] = preg_replace('~\s{2,}~', ' ', trim ( name_format( $reporter, 't+ig+im +if+is+kc' ) ) );
      }
      $variables['reporter'] = implode( $reporters, ', ' );
    }

    if (isset($resultNode->field_document) && is_array($resultNode->field_document)) {
      $variables['files'] = count($resultNode->field_document);
    }
    if (isset($resultNode->taxonomy) && is_array($resultNode->taxonomy)) {
      $terms = array();
      foreach ($resultNode->taxonomy as $tag) {
        $terms[] = l($tag->name, taxonomy_term_path($tag));
      }

      /*
       * $variables['tags'] can be theme using Drupal theme().
       * e.g., $vars['tags'] = theme('item_list', $terms, NULL, 'ul', array('class' => 'tags inline'));
       */
      $variables['tags'] = implode($terms, ', ');
    }
  }

  /** Check for existence. User search does not include snippets. */
  $variables['snippet'] = isset($result['snippet']) ? $result['snippet'] : '';

  /** Provide separated and grouped meta information. */
  $variables['info_split'] = $info;
  
  $variables['info'] = implode(' - ', $info);
  
  /** Provide alternate search result template. */
  $variables['template_files'][] = 'search-result-'. $variables['type'];

}

function undercover_render_reporters($all_reporters) {

    $all_reporters = array_unique($all_reporters);

    $reporters = '<ul>';
    
    foreach( $all_reporters as $rep ) {
      $rep = trim($rep);
      if (strlen($rep) ) {
        $reporters .= '<li>' . l(  $rep, 'search/apachesolr_search/' , array( 'query' => 'filters=sm_reporter_dlts_undercover:"' . $rep . '"') )  . '</li>';
      }
    }
      
    $reporters .= '</ul>';
    
    return $reporters;
}

function undercover_render_articles($all_articles = array(), $sorttmp = array()) {

  // array_multisort($sorttmp, SORT_ASC, SORT_STRING, &$all_articles);

  $articles = '<ul>';
      
  foreach ($all_articles as $art ) {
        
    $data_one = ( ( isset( $art['field_backref_a0e889285eab265d39']) ) ? ' data-backref-one="' . $art['field_backref_a0e889285eab265d39'] . '"' : '');       

    $data_two = ( ( isset( $art['field_backref_dca0e9a074ec80b79c']) ) ? ' data-backref-two="' . $art['field_backref_dca0e9a074ec80b79c'] . '"' : '');  

    // $hasBackref = ((isset($art['hasBackref']) && $art['hasBackref']) ? ' style="display: none; visibility: hidden"' : '');       

    $articles .= '<li class="' . $art['type'] . '" ' . $data_one . $data_two . '>';
       
    $articles .= '<h4>';
 
    $articles .= '<span class="heading">' . $art['view'] . '</span>';

    if ( strlen( $art['subhead'] ) ) {
      $articles .= '<span class="pipe"> | </span>' . '<span class="subheading">' . $art['subhead'] . '</span>' . '<br />';
    }
        
    $articles .= '</h4>';
        
    $articles .= $art['display_date'] . '<br />';
        
    if ( strlen( $art['blurb'] ) ) {
      $articles .= $art['blurb'];
    }
        
    $articles .= '</li>';
  }

  $articles .= '</ul>';      

  return $articles;
  
}

function undercover_render_xlinks($node) {
  $xlinks = '';
  if ( strlen($node->field_external_link[0]['view']) ) {
    $xlinks .= '<ul>';
    foreach( $node->field_external_link as $link ) {
	  $xlinks .= '<li>'. $link['view'] .'</li>';
    }
    $xlinks .= '</ul>';
  }
  
  return $xlinks;
  
}

function undercover_render_supplementary($node) {

  $output = '';
  
  if ($node->field_supplementary[0]['view'] ) {
    
    $output .= '<ul>';
    
    foreach( $node->field_supplementary as $sup ) {
      $output .= '<li>' . $sup['view'] . '</li>';
    }
    
    $output .= '</ul>';
  
  }

  return $output;
}

function undercover_render_media($node) {

  $output = '';
  
  if ( $node->field_media[0]['view'] ) {
    
    $output .= '<p>' . t('The reporting was intended for these media types') . ': ';
    
    $j = count($node->field_media);
    
    $i = 1;
    
    foreach( $node->field_media as $med ){
      
      $output .= $med['view'];
      
      if ( $i != $j) {
        
        $output .= ', ';
      }
      
      $i++;
    
    }
  
  }
      
  return $output;
}

function undercover_render_representative_image($node) {

  $output = '';
  
  if ( $node->field_representative_image[0]['filepath'] ) {
    $output .= '<img src="' . $node->field_representative_image[0]['filepath'] . '" width="240px" />';
  }
  
  return $output;

}

function undercover_render_effects($node) {

  $output = '';

  if ($node->field_effects[0]['view']) {
    $output .= $node->field_effects[0]['view'];
  }

  return $output;

}

function undercover_render_documents($documents, &$all_reporters) {
  
  $backref = $articles = array();
  
  foreach($documents as $doc) {
  
    $doc_node = node_load( $doc['nid'] );
    
    $alias = drupal_get_path_alias('node/' . $doc_node->nid);
          
    $doc_node_path = base_path() . $alias;    
      
    $doc['type'] = $doc_node->type;

    $doc['pubdate_sort'] = $doc_node->field_pubdate[0]['value'];        

    if ( $doc_node->type == 'book' ) {
      $doc['display_date'] = date( 'Y', strtotime( $doc_node->field_pubyear[0]['value'] ) );
    }
		
    else {
      $doc['display_date'] = date( 'F j, Y', strtotime( $doc_node->field_pubdate[0]['value'] ) );	
   }
		
    $sorttmp[] = $doc['pubdate_sort'];
  
    if (isset($doc['view'])) {
      
      $doc['title'] = $doc['view'];

    }
    
    else {

      $doc['view'] = $doc['title'] = '<span id="thmr_18" class="thmr_call">' . l($doc_node->title, $alias) . '</span>'; 

    }
 
    $doc['subhead'] = $doc_node->field_subhead[0]['value'];
        
    if (strlen($doc_node->field_description[0]['value'])) {
      $doc['blurb'] = truncate_utf8( $doc_node->field_description[0]['value'], 120, TRUE, TRUE );
    }

    if ($doc_node->field_reporter) {
      foreach ( $doc_node->field_reporter as $rep ) {
        if ( module_exists('name') ) {
          $all_reporters[] =  preg_replace('~\s{2,}~', ' ', trim ( name_format( $rep, 't+ig+im +if+is+kc' ) ) );
        }
      }
    }
      
    // https://drupal.org/node/849816#comment-3290920
    foreach (content_fields(NULL, $doc_node->type) as $field_name => $field) {
      if ($field['type'] == 'noderelationships_backref') {
        $value = content_format($field, $doc_node->$field_name, 'default', $doc_node);
        if (!empty($value)) {
          preg_match('/<a href="(.*)">/', $value, $matches);
          if (isset($matches[1])) {
            
            $doc['hasBackref'] = TRUE;
            
            $m = str_replace(base_path(), '', $matches[1]);
            
            $doc[$field_name] = $matches[1];
            
            $backref[$m][$doc_node->nid] = array(
                'nid' => $doc_node->nid,
                'hasBackref' => TRUE,
                'title' => $doc_node->title,
                'alias' => $doc_node_path,
                'type' => $doc_node->type,
                'pubdate_sort' => $doc_node->field_pubyear[0]['value'], 
                'subhead' => $doc_node->field_subhead[0]['value'],
                'blurb' => $doc['blurb'],
                'display_date' => $doc['display_date'],
            );
          }
        }
      }
    }
    
    $articles[] = $doc;
    
  }

  return $articles;
  
}
