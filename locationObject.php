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
    protected $url;
    protected $desc;
    protected $category;
    protected $last_update;

    /**
     * locationObject constructor.
     * @param $id
     * @param $is_active
     * @param $name
     * @param $address
     * @param $price_beer
     * @param $price_softdrink
     * @param $has_food
     * @param $url
     * @param $desc
     * @param $category
     * @param $last_update
     */
    public function __construct($id, $is_active, $name, $address, $price_beer, $price_softdrink, $has_food, $url, $desc, $category, $last_update)
    {
        $this->id = $id;
        $this->is_active = $is_active;
        $this->name = $name;
        $this->address = $address;
        $this->price_beer = $price_beer;
        $this->price_softdrink = $price_softdrink;
        $this->has_food = $has_food;
        $this->url = $url;
        $this->desc = $desc;
        $this->category = $category;
        $this->last_update = $last_update;
    }

    public function __destruct()
    {
        unset($id, $is_active, $name, $address, $price_beer, $price_softdrink, $has_food, $url, $desc, $category, $last_update);

    }


}