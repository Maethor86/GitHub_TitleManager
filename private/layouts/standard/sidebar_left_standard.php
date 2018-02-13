
<?php //find_current_page(); ?>
<div id="main">
  <div id="sidebar_left">


  <?php
    // make a list of subjects in the sidebar_left
    $admin = FALSE;
    if ($session->is_admin()) {
      $admin = TRUE;
    }

    $output = "<ul class=\"subjects\">";
    $subjects = Subject::find_all();
    foreach ($subjects as $subject) {
      if (!($admin) && $subject->is_admin()) {
        continue;
      }
      if (!$subject->get_visible()) {
        continue;
      }
      // find all pages associated with subject
      $pages = Page::find_pages($subject->get_subjectid());
      $subject_menuname = $subject->get_menuname();
      $output .= "<li";
      if (isset($_SESSION["subject_id"]) && $_SESSION["subject_id"] == $subject->get_subjectid()) {
        $output .= " class=\"selected\"";
      }
      $output .= "><a href=\"main.php?subject=".$subject->get_subjectid()."\">$subject_menuname</a>";
      foreach ($pages as $page) {
        if (!$page->get_visible()) {
          continue;
        }
        $page_menuname = $page->get_menuname();
        $page_id = $page->get_pageid();
        $output .= "<ul class=\"pages\">";
        $output .= "<li";
        if (isset($_SESSION["page_id"]) && $_SESSION["page_id"] == $page->get_pageid()) {
          $output .= " class=\"selected\"";
        }
        $output .= "><a href=\"main.php?page=".$page_id."\">$page_menuname</a>";
        $output .= "</li>";
        $output .= "</ul>";
      }
      $output .= "</li>";


    }
    $output .= "</ul>";
    echo $output;
  ?>


  </div>
  <div id="pagesidebar_right">
    <div id="page">
