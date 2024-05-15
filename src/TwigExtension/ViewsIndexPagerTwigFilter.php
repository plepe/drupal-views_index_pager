<?php
namespace Drupal\views_index_pager\TwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViewsIndexPagerTwigFilter extends AbstractExtension {
  public function getFunctions() {
    return [ new TwigFunction('views_index_pager', array($this, 'show'), array('is_safe' => array('html'))) ];
  }

  public function getname() {
    return 'views_index_pager.twig_extension';
  }

  public static function show($param=array()) {
    $view = \Drupal\views\Views::getView($param['view']);
    $view->setDisplay($param['display']);

    if ($view->usePager()) {
      $view->setItemsPerPage(0); // override pager to get all results
    }

    $view->execute();
    $list = [];

    $field = $view->field[$param['index_field']];
    
    foreach ($view->result as $i => $row) {
      $index = $field->advancedRender($row)->__toString();

      if (!array_key_exists($index, $list)) {
        $list[$index] = $i;
      }
    }

    $result = [];
    foreach ($list as $index => $i) {
      $page = floor($i / $param['page_size']);
      $onThisPage = ($_REQUEST['page'] ?? 0) == $page;

      $link = '#' . htmlspecialchars($index);
      if (!$onThisPage) {
        $r = $_REQUEST;
        $r['page'] = $page;

        $link = '?' . http_build_query($r) . $link;
      }

      $result[] = "<a href=\"{$link}\">" . htmlspecialchars($index) . '</a>';
    }

    return '<div class="views_pager_index">' . implode(' ', $result) . '</div>';
  }
}
