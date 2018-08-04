<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event $event
 */
$today = date("Y");
?>
<div class="events form large-9 medium-8 columns content">
<?php $startdate = $this->Time->format($event->startdate,'YYYY-MM-dd');?>
<?php $starttime = $this->Time->format($event->startdate,'HH:mm');?>
<?php $enddate = $this->Time->format($event->enddate,'YYYY-MM-dd');?>
<?php $endtime = $this->Time->format($event->enddate,'HH:mm');?>
    <?= $this->Form->create($event, ['enctype' => 'multipart/form-data']) ?>

    <h2><?= __('Edit Event') ?></h2>
    <?php
        echo $this->Form->control('user_id', ['options' => $users, 'empty' => true, 'type' => 'hidden']);
        echo $this->Form->control('name');
        echo $this->Form->control('description');
        echo $this->Form->label('startdate');
        echo $this->Form->text('startdate', array(
            'type' => 'date',
            'value' => $startdate
        ));
        echo $this->Form->text('starttime', [
            'placeholder' => '00:00',
            'value' => $starttime
        ]);
        echo $this->Form->label('enddate');
        echo $this->Form->text('enddate', array(
            'type' => 'date',
            'value' => $enddate
        ));
        echo $this->Form->text('endtime', [
            'placeholder' => '00:00',
            'value' => $endtime
        ]);
        echo $this->Form->control('address', array(
            'id' => 'address'
        ));
        echo $this->Form->control('place_id', array(
            'id' => 'place_id',
            'label' => false,
            'type' => 'hidden'
        ));?>
        <div id="suggestion-box"></div>

    <?php echo $this->Form->file('submittedfile');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $(document).ready(function(){
        $("#address").keyup(function(){
            let search_text = $("#address").val();
            $.ajax({
                type: "POST",
                url: "<?php echo $this->request->webroot;?>Events/geoCity",
                data:{'search_text':search_text},
                beforeSend: function(){
                    $("#address").css("background","#fff no-repeat 380px");
                },
                success: function(data){
                    $("#suggestion-box").show();
                    $("#suggestion-box").html(data);
                    $("#search-box").css("background", "#fff");
                    $("#address").css("background", "none");
                    if(data.length==0)
                    {
                        $("#place_id").val("");
                    }
                }
            });
        });
    });
    function selectCountry(city_name, place_id) {
        $("#address").val(city_name);
        $("#place_id").val(place_id);
        $("#suggestion-box").hide();
    }
</script>
