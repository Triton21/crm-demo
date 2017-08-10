<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace AppBundle\Model;

use Symfony\Component\HttpFoundation\Request;

/**
 * Description of MaininboxSearch
 *
 * @author Peter
 */
class MaininboxSearch {
    
    protected $dateFrom;

    protected $dateTo;
    
    protected $texthtml;
    
    protected $settings;
    
    public function __construct()
    {
        // initialise the dateFrom to "one month ago", and the dateTo to "today"
        $date = new \DateTime();
        $month = new \DateInterval('P1Y');
        $date->sub($month);
        $date->setTime('00','00','00');

        $this->dateFrom = $date;
        $this->dateTo = new \DateTime();
        $this->dateTo->setTime('23','59','59');
        $this->initSortSelect();
    }
    
    public function setDateFrom($dateFrom)
    {
        if($dateFrom != ""){
            $dateFrom->setTime('00','00','00');
            $this->dateFrom = $dateFrom;
        }

        return $this;
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function setDateTo($dateTo)
    {
        if($dateTo != ""){
            $dateTo->setTime('23','59','59');
            $this->dateTo = $dateTo;
        }

        return $this;
    }

    public function clearDates(){
        $this->dateTo = null;
        $this->dateFrom = null;
    }

    public function getDateTo()
    {
        return $this->dateTo;
    }
    
    public function getTexthtml()
    {
        return $this->texthtml;
    }

    public function setTexthtml($texthtml)
    {
        $this->texthtml = $texthtml;

        return $this;
    }
    
    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }
    
    //new properties for pagination
    public static $sortChoices = array(
        'desc' => 'Mail date : new to old',
        'asc' => 'Mail date : old to new',
    );

    // define the default field used for the sorting
    protected $sort = 'maildate';

    // define the default sort order
    protected $direction = 'desc';

    // a "virtual" property to add a select tag
    protected $sortSelect;

    // the default page number
    protected $page;

    // the default number of items per page
    protected $perPage = 10;

    // other getters and setters

    public function handleRequest(Request $request)
    {
        $this->setPage($request->get('page', 1));
        $this->setSort($request->get('sort', 'maildate'));
        $this->setDirection($request->get('direction', 'desc'));
    }

    public function getPage()
    {
        return $this->page;
    }


    public function setPage($page)
    {   
        $this->page = $page;
        return $this;
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function setPerPage($perPage=null)
    {
        if($perPage != null){
            $this->perPage = $perPage;
        }

        return $this;
    }

    public function setSortSelect($sortSelect)
    {
        if ($sortSelect != null) {
            $this->sortSelect =  $sortSelect;
        }
    }

    public function getSortSelect()
    {
        return $this->sort.' '.$this->direction;
    }

    public function initSortSelect()
    {
        $this->sortSelect = $this->sort.' '.$this->direction;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function setSort($sort)
    {
        if ($sort != null) {
            $this->sort = $sort;
            $this->initSortSelect();
        }

        return $this;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function setDirection($direction)
    {
        if ($direction != null) {
            $this->direction = $direction;
            $this->initSortSelect();
        }

        return $this;
    }
    
}
