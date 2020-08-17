<?php
    class pagination{
        protected $_config = array(
            'current_page' => 1, //Trang hien tai
            'total_record' => 1, //Tong so record
            'total_page' => 1 , //Tong so trang
            'limit' => 10, //limit
            'start' => 0, //start
            'link_full' => '',
            'link_first' => '', //link trang dau tien
        );

        function init($config = array()){
            foreach($config as $key => $val){
                if(isset($this->_config[$key])){
                    $this->_config[$key] = $val;
                }
            }

            //check limit
            if($this->_config['limit']< 0){
                $this->_config['limit'] = 0;
            }

            //total_page = ceil(total_recore/limit)
            $this->_config['total_page'] = ceil($this->_config['total_record'] / $this->_config['limit']);

            //check total page
            if($this->_config['total_page'] <= 0){
                $this->_config['total_page'] = 1;
            }

            //check current page [1, total_page]
            if($this->_config['current_page']<1){
                $this->_config['current_page'] = 1;
            }else if($this->_config['current_page'] > $this->_config['total_page']){
                $this->_config['current_page'] = $this->_config['total_page'];
            }

            //start of each page
            //start = (current_page-1)*limit
            $this->_config['start'] = ($this->_config['current_page']-1)*$this->_config['limit'];
        }

        //lay link theo trang
        private function __link($page){
            //neu trang < 1 => first
            if($page < 1 && $this->_config['link_first']){
                return $this->_config['link_first'];
            }
            //link full
            //'index.php?page={page}'
            return str_replace('{page}', $page, $this->_config['link_full']);
        }

        //ma html
        function html(){
            $p = '';
            // Kiểm tra tổng số trang lớn hơn 1 mới phân trang
            if ($this->_config['total_record'] > $this->_config['limit'])
            {
                $p = '<ul>';
                
                // Nút prev và first
                if ($this->_config['current_page'] > 1)
                {
                    $p .= '<li><a href="'.$this->__link('1').'">First</a></li>';
                    $p .= '<li><a href="'.$this->__link($this->_config['current_page']-1).'">Prev</a></li>';
                }
                
                // lặp trong khoảng cách giữa min và max để hiển thị các nút
                for ($i = 1; $i <= $this->_config['total_page']; $i++)
                {
                    // Trang hiện tại
                    if ($this->_config['current_page'] == $i){
                        $p .= '<li><span>'.$i.'</span></li>';
                    }
                    else{
                        $p .= '<li><a href="'.$this->__link($i).'">'.$i.'</a></li>';
                    }
                }
    
                // Nút last và next
                if ($this->_config['current_page'] < $this->_config['total_page'])
                {
                    $p .= '<li><a href="'.$this->__link($this->_config['current_page'] + 1).'">Next</a></li>';
                    $p .= '<li><a href="'.$this->__link($this->_config['total_page']).'">Last</a></li>';
                }
                
                $p .= '</ul>';
            }
            return $p;
        }
    }

    $config = array(
        'current_page'  => isset($_GET['page']) ? $_GET['page'] : 1, // Trang hiện tại
        'total_record'  => 1900, // Tổng số record
        'limit'         => 10,// limit
        'link_full'     => 'index.php?page={page}',// Link full có dạng như sau: domain/com/page/{page}
        'link_first'    => 'index.php',// Link trang đầu tiên
    );

    $paging = new pagination();
 
    $paging->init($config);
    
    echo $paging->html();
?>
<style>
    li{float:left; margin: 3px; border: solid 1px gray;}
    a{padding: 5px;}
    span{display:inline-block; padding: 0px 3px; background: blue; color:white }
</style>