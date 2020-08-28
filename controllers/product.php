<?php
/**
 * wp, wooCommerce product eseménykezelők
 * @author utopszkij
 */
class ProductController extends Controller {
    /**
     * product save és product lomtárba helyezés után fut,
     * a product már tárolva van az adatbázisba, van ID -je
     * @params array $params [$project_id, $project]
     */
    public function afterSave($params) {
        foreach ($params as $fn => $fv) {
            $this->$fn = $fv;
        }
        $this->view->display('product_after_save');
    }

    /**
     * product save és product lomtárba helyezés után fut,
     * @params array $params [$product_id]
     */
    public function afterDelete($params) {
        foreach ($params as $fn => $fv) {
            $this->$fn = $fv;
        }
        // valoójában itt most sql -el törölni kell a tárolt poligon pont adatokat
        // ........
    }
}
?>