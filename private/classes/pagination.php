<?php

// this is a helper class making pagination easier

class Pagination {

  protected $current_page;
  protected $per_page;
  protected $total_count;

  // -------------------------------
  public function __construct($page=1, $per_page=10, $total_count=0) {
    $this->current_page = (int)$page;
    $this->per_page = (int)$per_page;
    $this->total_count = (int)$total_count;
  }

  public function get_currentpage() {
    return $this->current_page;
  }

  public function get_perpage() {
    return $this->per_page;
  }

  public function get_totalcount() {
    return $this->total_count;
  }

  public function offset() {
    return ($this->get_currentpage() - 1) * $this->get_perpage();
  }

  public function total_pages() {
    return ceil($this->get_totalcount()/$this->get_perpage());
  }

  public function previous_page() {
    return $this->get_currentpage() - 1;
  }

  public function next_page() {
    return $this->get_currentpage() + 1;
  }

  public function has_previous_page() {
    return $this->previous_page() >= 1 ? TRUE : FALSE;
  }

  public function has_next_page() {
    return $this->next_page() <= $this->total_pages() ? TRUE : FALSE;
  }

}

?>
