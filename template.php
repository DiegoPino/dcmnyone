<?php
/**
 * @file
 * The primary PHP file for this theme.
 */
function dcmnyone_form_islandora_solr_simple_search_form_alter(&$form, &$form_state, $form_id) {
  $form['#tree'] = FALSE;

  // Add a clearfix class so the results don't overflow onto the form.
  $form['simple']['#type'] = 'actions';
  $form['simple']['#attributes']['class'][] = 'clearfix';

  // Remove container-inline from the container classes.
  $form['simple']['islandora_simple_search_query']['#attributes']['class'] = array();
  $form['simple']['islandora_simple_search_query']['#size'] = 30;
  $form['simple']['islandora_simple_search_query']['#attributes']=array('placeholder'=>t('Search our repository'));
 
  // Hide the default button from display.
  $form['simple']['submit']['#attributes']['class'][] = 'element-invisible';
  unset($form['simple']['islandora_simple_search_query']['#title']);
  // Implement a theme wrapper to add a submit button containing a search
  // icon directly after the input element.
  $form['simple']['#theme_wrappers'] = array('bootstrap_search_form_wrapper');
  $form['simple']['keys']['#title'] = '';
  $form['simple']['keys']['#attributes']['placeholder'] = t('Search');
  
  dpm($form);

}


/**
 * Override or insert variables into the about_collection content type.
 *
 * Add an additional theme_hook_suggestion for the 'about_collection'
 * content type and construct the return link used on that page. This link
 * is added into the variables array as 'return_link'.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 */
function dcmnyone_theme_preprocess_node(&$variables) {
  // Handle additional processing for the 'about_collection' content type.
  if (isset($variables['node']) && $variables['node']->type ==="about_collection") {
    $variables['theme_hook_suggestions'][] =  "node__" . $variables['node']->type;
    if (isset($variables['field_collection_pid']['und']['0']['value'])) {
      $pid = $variables['field_collection_pid']['und']['0']['value'];
      $variables['return_link'] = url("islandora/object/$pid");
    }
    if (isset($variables['field_institutions_website']['und']['0']['value'])) {
      $variables['inst_link'] = $variables['field_institutions_website']['und']['0']['value'];
    }
  }
}

/**
 * Override or insert variables into the page template.
 *
 * Construct the "About '{COLLECTION_LABEL}'" url and
 * add it into the variables array as 'about_collection_link'.
 * This will be rendered on every page that represents a collection
 * and has a relevent 'about_collection' content type
 * created with the same pid.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 */
function dcmnyone_theme_preprocess_page(&$variables) {
  $object = menu_get_object('islandora_object', 2);
  if (isset($object) && in_array("islandora:collectionCModel", $object->models)) {
    $query = new EntityFieldQuery;
    $query->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'about_collection')
      ->propertyCondition('status', 1)
      ->fieldCondition('field_collection_pid', 'value', $object->id);
    $results = $query->execute();
    if (isset($results['node'])) {
      $nodes = node_load_multiple(array_keys($results['node']));
      $node = reset($nodes);
      $node_id = $node->nid;
      $variables['about_collection_link'] = url("node/$node_id");
    }
  }
}

function dcmnmyone_theme_form_islandora_solr_simple_search_form_alter(&$form, &$form_state, $form_id) {
  $link = array(
    '#markup' => l(t("Advanced Search"), "advanced-search", array('attributes' => array('class' => array('adv_search')))),
  );
  $form['simple']['advanced_link'] = $link;
}





/**
 * DCMNY one Form Alter for searching within a given collection (or site wide).
 */
function dcmnyone_form_islandora_collection_search_form_alter(&$form, &$form_state, $form_id) {
  $form['#tree'] = FALSE;
  dpm($form);
  // Add a clearfix class so the results don't overflow onto the form.
/*  $form['simple']['#type'] = 'actions';
  $form['simple']['#attributes']['class'] = array('clearfix');
  // Remove container-inline from the container classes.
  $form['simple']['islandora_simple_search_query']['#attributes']['class'] = array();
  $form['simple']['islandora_simple_search_query']['#size'] = 30;
  $form['simple']['islandora_simple_search_query']['#attributes']=array('placeholder'=>t('Search our repository'));
  // Hide the default button from display.
  $form['simple']['submit']['#attributes']['class'][] = 'element-invisible';
  $form['simple']['islandora_simple_search_query']['#attributes']=array('placeholder'=>t('Search our repository'));
  unset($form['simple']['islandora_simple_search_query']['#title']);
  // Implement a theme wrapper to add a submit button containing a search
  // icon directly after the input element.
  $form['simple']['#theme_wrappers'] = array('bootstrap_search_form_wrapper');
  $form['simple']['keys']['#title'] = '';
  $form['simple']['keys']['#attributes']['placeholder'] = t('Search');
  
  $form['simple']['collection_select']['#attributes'] = array(
      'class' => array('selectpicker'),
      'data-width'=> 'fit',
    ); */
  unset($form['simple']);
  $form['simple']['#type'] = 'markup';
  $form['simple']['#markup'] = '<form action="/islandora/search?page=4&amp;type=edismax&amp;cp=lesbianherstory%3Acollection" method="post" id="islandora-collection-search-form" accept-charset="UTF-8">

  <div class="input-group"> 
      <!-- Select-->
      <span class="input-group-addon" id="basic-addon1">search inside</span>
      <div class="form-control">
      <select class="selectpicker" data-width="fit" aria-describedby="basic-addon2" id="edit-collection-select" data-header="Select a Collection" name="collection_select">
      <option value="all">All Collections</option>
      <option value="albadigitallibrary:collection">Abraham Lincoln Brigade Archives, ALBA Digital Library</option>
    </select>
  </div>
</div>


</form>';

               
     $form['simple']['#markup'] = '<div class="input-group"><div class="input-group-btn">
                       <button type="button" class="btn btn-search btn-default dropdown-toggle" data-toggle="dropdown">
                           <span class="glyphicon glyphicon-search"></span>
                           <span class="label-icon">Search</span>
                           <span class="caret"></span>
                       </button>
                       <ul class="dropdown-menu pull-left" role="menu">
                          <li>
                               <a href="#">
                                   <span class="glyphicon glyphicon-user"></span>
                                   <span class="label-icon">Search By User</span>
                               </a>
                           </li>
                           <li>
                               <a href="#">
                               <span class="glyphicon glyphicon-book"></span>
                               <span class="label-icon">Search By Organization</span>
                               </a>
                           </li>
                       </ul>
                   </div>
       
                   <input type="text" class="form-control">
               
                   <div class="input-group-btn">
                       <button type="button" class="btn btn-search btn-default">
                       GO
                       </button>
                        
                        
                   </div>
               </div> ';
               $form['simple']['#markup'] = '<div class="input-group"><div class="input-group-btn">
                 <select class="selectpicker" data-live-search="true" title="All Collections" data-style="btn-search btn-default" data-width="fit" data-header="Please Select a Collection">
               
                 <option value="all" selected data-icon="glyphicon-search">All Collections</option>
                 <option value="albadigitallibrary:collection">Abraham Lincoln Brigade Archives, ALBA Digital Library</option>
                  </select>
                 
                             </div>
       
                             <input type="text" class="form-control" size="30">
               
                             <div class="input-group-btn">
                                 <button type="button" class="btn btn-search btn-primary form-submit">
                                 GO
                                 </button>
                        
                        
                             </div>
                         </div> ';       
               
              
  dpm($form);

}