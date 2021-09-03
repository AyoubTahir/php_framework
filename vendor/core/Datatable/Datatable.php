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

    public function __construct($app)
    {
        $this->app = $app;
        parent::__construct();
    }

    public function create($datatableColumnClass,$data)
    {
        $obj                    = new $datatableColumnClass();
        $this->tableColumns     = $obj->columns();
        $this->totalRecord      = $data->count();
        $this->data             = $this->sortData($data->paginate($this->perPage,$this->offset())->get());
        return $this;
    }

    public function table()
    {
        if (!is_array($this->tableColumns) || count($this->tableColumns) == 0 )
        {
            return;
        }

        $this->element .= $this->attr['before'];
        $this->element .= '<table id="'. ($this->attr['before'] ?? '') .'" class="'. implode(' ', $this->attr['table_class']) .'">'.PHP_EOL;
        $this->element .= $this->tableHead($this->attr['status']);
        $this->element .= $this->tableBody();
        //$this->element .= $this->tableFooter();
        $this->element .= '</table>'.PHP_EOL;
        $this->element .= $this->attr['after'];

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
        $curentPage = $this->currentPage();
        $totalPages = (int)ceil($this->totalRecord / $this->perPage);

        $element = '<ul>';

        for($page=1;$page<=$totalPages;$page++)
        {
            if($curentPage == 1 && $page == 1)
            {
                $element .= "<li><a href='javascript:void(0);'>Previous</li>";
            }
            elseif($page == 1)
            {
                $element .= '<li><a href="?page='.($curentPage-1).'">Previous</li>';
            }

            $element .= '<li><a href="?page='.$page.'">'.$page.'</li>';

            if($curentPage == $totalPages && $page == $totalPages)
            {
                $element .= "<li><a href='javascript:void(0);'>Next</li>";
            }
            elseif($page == $totalPages)
            {
                $element .= '<li><a href="?page='.($curentPage+1).'">Next</li>';
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

        return $this->perPage * ($curentPage - 1);;
    }
}