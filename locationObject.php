<?php

class locationObject {

    /**
     * TODO auto-generated object stu.. hang on, this isn't eclipse.
     * create fields according to the database structure
     */
    protected $id;
    protected $is_active;
    protected $name;
    protected $address;
    protected $price_beer;
    protected $price_softdrink;
    protected $has_food;
    protected $has_beer;
    protected $has_cocktails;
    protected $has_wifi;
    protected $has_togo;
    protected $url;
    protected $desc;
    protected $category;
    protected $last_update;
    protected $phone;
    protected $is_smokers;
    protected $is_nonsmokers;


    public function __destruct()
    {
       unset($id, $is_active, $name, $address, $price_beer, $price_softdrink, $has_food, $has_beer, $has_cocktails, $has_wifi, $has_togo, $url, $desc, $category, $last_update, $phone, $is_smokers, $is_nonsmokers);
    }
}