<?php 
interface fieldsFetch
{
    public function getFields($id, $searchall = false);
    public function getItemFields($id);
    public function recordNewData($post);
    //getFieldValues($field, $itemid) <---different in the models
}
?>
