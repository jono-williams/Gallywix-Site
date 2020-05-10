<?php
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
.ui-accordion .ui-accordion-content {
  overflow: visible;
}

#accordion li, .staffMember {
  list-style-type: none;
  border: 1px solid #000;
  padding: 2.5%;
  margin-bottom: 5px;
}

#accordion2, #accordion3, #accordion2 .ui-accordion-content, #accordion3 .ui-accordion-content {
  height: auto !important;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>Control panel</small>
      </h1>
    </section>
    <section class="content container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="box">
            <div class="box-header">
              EU Staff
            </div>
            <div class="box-body">
              <?php foreach ($euStaff as $key => $euS) { ?>
                <?php $color = random_color(); ?>
                <div class="staffMember droppable" data-userId="<?=$euS->userId?>" style="background:#<?=$color?>" data-colour="<?=$color?>"><?=$euS->name?></div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div id="accordion">
            <h3>
                <th>EU</th>
            </h3>
            <?php $cats = []; foreach ($tasks_eu as $key => $task) {
              $cats[] = $task->category;
            }
            $cats = array_unique($cats);
            ?>
            <div id="accordion2">
              <?php foreach ($cats as $key => $cat) { ?>
                <h3>
                    <th><?=$cat?></th>
                </h3>
                <div>
                  <ul>
                    <?php foreach ($tasks_eu as $key => $task) { ?>
                      <?php if($task->category == $cat) { ?>
                        <li class="draggable" data-taskId="<?=$task->id?>" <?=($task->assigned_user ? 'data-assigned_user="'.$task->assigned_user.'"' : '')?>><?=$task->task_name?></li>
                      <?php } ?>
                    <?php } ?>
                  </ul>
                </div>
              <?php } ?>
            </div>
            <h3>
                <th>NA</th>
            </h3>
            <?php $cats = []; foreach ($tasks_na as $key => $task) {
              $cats[] = $task->category;
            }
            $cats = array_unique($cats);
            ?>
            <div id="accordion3">
              <?php foreach ($cats as $key => $cat) { ?>
                <h3>
                    <th><?=$cat?></th>
                </h3>
                <div>
                  <ul>
                    <?php foreach ($tasks_na as $key => $task) { ?>
                      <?php if($task->category == $cat) { ?>
                        <li class="draggable" data-taskId="<?=$task->id?>" <?=($task->assigned_user ? 'data-assigned_user="'.$task->assigned_user.'"' : '')?>><?=$task->task_name?></li>
                      <?php } ?>
                    <?php } ?>
                  </ul>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="box">
            <div class="box-header">
              NA Staff
            </div>
            <div class="box-body">
              <?php foreach ($naStaff as $key => $naS) { ?>
                <?php $color = random_color(); ?>
                <div class="staffMember droppable" data-userId="<?=$naS->userId?>" style="background:#<?=$color?>" data-colour="<?=$color?>"><?=$naS->name?></div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(function() {
    $("#accordion").accordion();
    $("#accordion2").accordion();
    $("#accordion3").accordion();
    $(".draggable").draggable({ revert: true });
    $(".droppable").droppable({
      drop: function( event, ui ) {
        task = $(ui.draggable);
        target = $(event.target);
        newColour = target.data('colour');
        taskId = task.data('taskid');
        userId = target.data('userid');
        $.ajax({
          url: '<?=base_url()?>admin/set_task',
          data: {taskid: taskId, userid: userId},
          method: 'POST',
          success: function(data) {
            task.css({background: "#" + newColour});
          }
        })
      }
    });
    $('.draggable').each(function(){
      if($(this).data('assigned_user')) {
        console.log($(this).data('assigned_user'));
        found = $('.staffMember[data-userid="'+$(this).data('assigned_user')+'"]');
        $(this).css({background: '#' + $(found).data('colour')});
      }
    })
  });
</script>
