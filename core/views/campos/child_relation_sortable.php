<?php

/**
 * @var $key
 * @var $controller_id
 * @var $unselected
 * @var $selected
 */
$sortable_key = 'child_relation_sortable_'. $key . '_'. $controller_id;

?>
<div class="child_relation_sortable_container" id="<?= $sortable_key ?>">

    <div class="child_relation_sortable">
        <select
            id="<?= $sortable_key.'_list_selected' ?>"
            name="<?= $sortable_key.'_list_selected[]' ?>"
            class="form-control"
            size="<?= count($selected) + 1 ?>"
            multiple="multiple"
        >
            <?php foreach($selected as $item){
                echo '<option value="'.$item->id.'">'.$item->nombre.'</option>'.PHP_EOL;
            } ?>
        </select>
    </div>

    <div class="child_relation_sortable_botonera">
        <button type="button" id="js_right_All_<?= $sortable_key ?>" class="btn btn-block">
            <i class="fa fa-forward"></i>
        </button>
        <button type="button" id="js_right_Selected_<?= $sortable_key ?>" class="btn btn-block">
            <i class="fa fa-chevron-right"></i>
        </button>
        <button type="button" id="js_left_Selected_<?= $sortable_key ?>" class="btn btn-block">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button type="button" id="js_left_All_<?= $sortable_key ?>" class="btn btn-block">
            <i class="fa fa-backward"></i>
        </button>
    </div>

    <div class="child_relation_sortable">
        <select
            id="<?= $sortable_key.'_list' ?>"
            name="<?= $sortable_key.'_list[]' ?>"
            class="form-control"
            size="<?= count($unselected) + 1 ?>"
            multiple="multiple"
        >
            <?php foreach($unselected as $item){
                echo '<option value="'.$item->id.'">'.$item->nombre.'</option>'.PHP_EOL;
            } ?>
        </select>
    </div>

    <?php
    echo form_hidden($key, implode(',', array_map(function($item){
        return $item->id;
    }, $selected)));
    ?>

    <style>
    .child_relation_sortable_container {
        display: flex !important;
        float: none !important;
        flex-direction: row;
        position: relative;
    }
    .child_relation_sortable {
        list-style: none;
        padding: 0;
        display: inline-block;
        width: 45%;
        position: relative;
    }
    .child_relation_sortable select {
        border: 0 !important;
    }
    .child_relation_sortable:first-child {
        padding-right: 10%;
    }
    .child_relation_sortable_botonera {
        height: 50px;
        width: auto;
        position: absolute;
        top: 50%;
        left: calc(50% - 155px);
    }
    #js_left_Selected_<?= $sortable_key ?>,
    #js_left_All_<?= $sortable_key ?> {
        margin-top: -50px;
        right: 30px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        line-height: 40px;
        text-align: center;
        letter-spacing: 3px;
        color: white;
        background-color: green;
        opacity: .8;
    }
    #js_right_Selected_<?= $sortable_key ?>,
    #js_right_All_<?= $sortable_key ?> {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        line-height: 40px;
        text-align: center;
        letter-spacing: -3px;
        color: white;
        background-color: red;
        opacity: .8;
    }
    .child_relation_sortable option {
        display: block;
        width: 93%;
        color: grey;
        font-size: 12px;
        background: #eee;
        margin: 1px;
        padding: 5px 10px;
    }
    .child_relation_sortable:first-child option {
        font-weight: bold;
        color: black;
    }
    .sortable-ghost {
        opacity: .6;
    }
    </style>

    <script src="<?= base_url() ?>core/js/campos/child_relation_sortable/multiselect.2.5.5.min.js"></script>
    <script>
    $(function(){
        // https://crlcu.github.io/multiselect/#documentation
        $('#<?= $sortable_key.'_list_selected' ?>').multiselect({
            right: '#<?= $sortable_key.'_list' ?>',
            rightAll: '#js_right_All_<?= $sortable_key ?>',
            rightSelected: '#js_right_Selected_<?= $sortable_key ?>',
            leftSelected: '#js_left_Selected_<?= $sortable_key ?>',
            leftAll: '#js_left_All_<?= $sortable_key ?>',
            afterMoveToRight: function($left, $right){
                updateSortable($left, $right);
            },
            afterMoveToLeft: function($left, $right){
                updateSortable($left, $right);
            }
        });
        function updateSortable($left, $right){
            var selected = $left.find('option').map(function(){
                return $(this).val();
            }).get().join(',');
            $('[name=<?= $key ?>]').val(selected);
            if($left.find('option')){
                $left.attr('size', $left.find('option').length + 1);
            }
            if($right.find('option')){
                $right.attr('size', $right.find('option').length + 1);
            }
        }
    });
    </script>
</div>