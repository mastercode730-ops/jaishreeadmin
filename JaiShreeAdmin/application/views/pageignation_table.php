<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<?php
$limit_per_page = 2;

$page = "";

$input = $this->input->post('page_no');

if(isset($input)){
    $page = $this->input->post('page_no');
}else{
    $page = 1;
}

$offset = ($page - 1) * $limit_per_page;

$get_all = $this->db->get('country',$limit_per_page,$offset);

echo "<pre>";
print_r($get_all->result());



$total_records = $this->db->get('country')->num_rows();

echo $total_records."<br><br><br><br><br>";

$previous = $page - 1;

$next = $page + 1;

$total_pages = ceil($total_records / $limit_per_page); ?>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item"><a class="page-link" id="<?= $previous;?>" href="#">Previous</a></li>
        <li class="page-item"><a class="page-link" id="1" href="#">1</a></li>
        <?php if($total_pages >= 5){
            echo "<li class='page-item'><a class='page-link' href=''>...</a></li>";
        } ?>

        <li class="page-item"><a id="<?= $total_pages;?>" class="page-link" href="#"><?= $total_pages;?></a></li>
        
    <li class="page-item"><a class="page-link" id="<?= $next;?>" href="#">Next</a></li>
</ul>
</nav>