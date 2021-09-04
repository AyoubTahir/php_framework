<?php

namespace Tahir\Datatable;

use Tahir\Datatable\AbstractDatatable;

class Datatable extends AbstractDatatable
{
    private $app;
    protected string $element = '';
    private $tableColumns=[];
    private $data=[];
    private $totalRecord=0;
    private $perPage = 2;
    private $pagination=true;
    private $search = true;

    public function __construct($app)
    {
        $this->app = $app;
        parent::__construct();
    }

    public function create($datatableColumnClass,$data,$pagination=true,$search=true)
    {
        $obj                    = new $datatableColumnClass();
        $this->tableColumns     = $obj->columns();
        
        $this->setAttr('tableParams',$obj->tableAttribute());
        $this->setAttr('tablePagination',$obj->tablePagination());
        $this->setAttr('tableSearch',$obj->tableSearch());
        $this->setAttr('tablePerPage',$obj->tablePerPage());


        $this->pagination       = $pagination;
        $this->search           = $search;
        $this->perPage          = $this->getperPage();

        if($search)
        {
            $data         = $data->search($this->searchable());
        }
        if($pagination)
        {
            $this->totalRecord      = $data->count(); 
            $data                   = $data->paginate($this->perPage,$this->offset());
        }

        $data = $data->get();

        $this->data             = $this->sortData($data);

        return $this;
    }

    public function table()
    {
        if (!is_array($this->tableColumns) || count($this->tableColumns) == 0 )
        {
            return;
        }

        $this->element .= $this->tableParams['before'];
        $this->element .= $this->search ? $this->search() : '';
        $this->element .= '<table id="'. ($this->tableParams['before'] ?? '') .'" class="'. implode(' ', $this->tableParams['table_class']) .'">'.PHP_EOL;
        $this->element .= $this->tableHead($this->tableParams['status']);
        $this->element .= $this->tableBody();
        //$this->element .= $this->tableFooter();
        $this->element .= '</table>'.PHP_EOL;
        $this->element .= $this->pagination ? $this->pagination()   : '';
        $this->element .= $this->pagination ? $this->perPage()      : '';
        $this->element .= $this->tableParams['after'];

        return $this->element;
    }

    public function tableBody() : string
    {
        $bodyString = '';

        $bodyString .= '<tbody>'.PHP_EOL;

            foreach ($this->data as $row)
            {
                $bodyString .= '<tr>'.PHP_EOL;

                    foreach ($this->tableColumns as $column)
                    {
                        if (isset($column['visible']) && $column['visible'] != false)
                        {
                            $bodyString .= '<td class="' . $column['class'] . '">';

                                if (is_callable($column['formatter']))
                                {
                                    $bodyString .= call_user_func_array($column['formatter'], [$row]);
                                }
                                else
                                {
                                    $bodyString .= (isset($row[$column['db_field']]) ? $row[$column['db_field']] : '');
                                }

                            $bodyString .= '</td>'.PHP_EOL;
                        }
                    }

                $bodyString .= '</tr>'.PHP_EOL;
            }

        $bodyString .= '</tbody>'.PHP_EOL;

        return $bodyString;
    }

    protected function tableHead(string $status) : string
    {
        $headString = '<thead>';

            $headString .= '<tr>';

                foreach ($this->tableColumns as $column)
                {
                    if (isset($column['visible']) && $column['visible'] != false)
                    {
                        $headString .= '<th>';
                        $headString .= $this->tableSorting($column, $status);
                        $headString .= '</th>';
                    }
                }

            $headString .= '</tr>';

        $headString .= '</thead>';

        return $headString;
    }

    private function tableSorting(array $column, string $status) : string
    {
        $sort = '';

        if (isset($column['sortable']) && $column['sortable'] != false)
        {
            if($status)
            {
                $sort .= '<a class="" href="?status=' . $status . '&column=' . $column['db_field'] . '&order=' . $this->sortDirection;
            }
            else
            {
                $order = $this->order();

                $sort .= '<a class="" href="?column=' . $column['db_field'] . '&order='.$order.'">';
            }
            
            $sort .= $column['dt_title'];
            $sort .= '<i class="fas fa-sort-up"></i>';
            $sort .= '</a>';
        }
        else
        {
            $sort .= $column['dt_title'];
        }

        return $sort;
    }

    private function order()
    {
        $order = $this->app->request->get('order');

        if($order == null || $order == 'DESC')
        {
            $order = 'ASC';
        }
        else
        {
            $order = 'DESC';
        }

        return $order;
    }

    private function sortData($data)
    {
        $data = json_decode(json_encode($data), true);

        if($this->order() == 'ASC')
        {
            asort($data);
        }
        elseif($this->order() == 'DESC')
        {
            arsort($data);
        }

        return $data;
    }

    public function pagination() : string
    {
        $p = $this->tablePagination;

        $curentPage = $this->currentPage();

        $totalPages = (int)ceil($this->totalRecord / $this->perPage);

        $op = $this->getSearch() ? '?search='.$this->getSearch().'&' : '?';

        $element = '<ul id="'.$p['ul_id'].'" class="'.$p['ul_class'].'">';

        for($page=1;$page<=$totalPages;$page++)
        {
            if($curentPage == 1 && $page == 1)
            {
                $element .= '<li class="'.$p['li_class'].'" ><a class="'.$p['a_class'].'" href="javascript:void(0);">Previous</a></li>';
            }
            elseif($page == 1)
            {
                $element .= '<li class="'.$p['li_class'].'" ><a class="'.$p['a_class'].'" href="'.$op.'page='.($curentPage-1).'">Previous</a></li>';
            }

            if($page == $curentPage)
            {
                $element .= '<li class="'.$p['li_class'].'" ><a class="'.$p['a_class'].'" href="javascript:void(0);">'.$page.'</a></li>';
            }
            else
            {
                $element .= '<li class="'.$p['li_class'].'" ><a class="'.$p['a_class'].'" href="'.$op.'page='.$page.'">'.$page.'</a></li>';
            }

            if($curentPage == $totalPages && $page == $totalPages)
            {
                $element .= '<li class="'.$p['li_class'].'" ><a class="'.$p['a_class'].'" href="javascript:void(0);">Next</a></li>';
            }
            elseif($page == $totalPages)
            {
                $element .= '<li class="'.$p['li_class'].'" ><a class="'.$p['a_class'].'" href="'.$op.'page='.($curentPage+1).'">Next</a></li>';
            }
        }
        
        $element .= '</ul>';

        return $element;
    }

    private function currentPage()
    {
        $curentPage = $this->app->request->get('page');

        $curentPage = (int)($curentPage ? $curentPage : 1);

        return $curentPage;
    }

    private function offset()
    {
        $curentPage = $this->currentPage();

        return $this->perPage * ($curentPage - 1);
    }

    private function search()
    {
        $searchString = '<form method="get" action="" class="'.$this->tableSearch['form_class'].'">';
        $searchString .= '<input class="'.$this->tableSearch['input_class'].'" type="search" name="search" placeholder="Search"/>';
        $searchString .= $this->tableSearch['button'] ? '<button class="'.$this->tableSearch['button_class'].'" type="submit">Search</button>' : '';
        $searchString .= '</form>';

        return $searchString;
    }

    private function searchable()
    {
        if (!is_array($this->tableColumns) || count($this->tableColumns) == 0 )
        {
            return;
        }

        $search = $this->getSearch();

        
        $searchArr = [];

        if($search)
        {
            foreach($this->tableColumns as $column)
            {
                if(isset($column['searchable']) && $column['searchable'])
                {
                    $searchArr = array_merge($searchArr,[$column['db_field']=>$search]);
                }
            }
        }

        return $searchArr;
        
    }

    private function getSearch()
    {
        return $this->app->request->get('search') ? $this->app->request->get('search') : '';
    }

    private function perPage()
    {
        $search = $this->getSearch() ? '<input type="hidden" name="search" value="'.$this->getSearch().'" />' : '';

        $select = '<form method="get" action="" class="'.$this->tablePerPage['form_class'].'">';
        $select .= '<select class="'.$this->tablePerPage['select_class'].'" name="perpage" onchange="this.form.submit()">';
        
        foreach($this->tablePerPage['perPage'] as $index=>$perPage)
        {

            $select .= '<option '.$index = 0 ? 'selected' : '' .' value="'.$perPage.'">'.$perPage.'</option>';  

        }

        $select .= '</select>';
        $select .= $search;
        $select .= '</form>';

        return $select;
    }

    private function getperPage()
    {
        return $this->app->request->get('perpage') ? (int)$this->app->request->get('perpage') : 2;       
    }
}