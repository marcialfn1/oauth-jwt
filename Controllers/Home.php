<?php
class Home extends Controllers {
    public function __construct() {
        parent::__construct();
    }

    public function home($params) {
        $data['page_tag'] = "Home";
        $data['page_title'] = "Pagina principal de Marcial FN";
        $data['page_name'] = "home"; 
        $this->views->getView($this, "home", $data);
    }
}


?>