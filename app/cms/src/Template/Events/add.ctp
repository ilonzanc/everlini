<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Event $event
 */

$today = date("Y");
?>
<div class="events form large-9 medium-8 columns content">
    <?= $this->Form->create($event, ['enctype' => 'multipart/form-data']) ?>
    <h3><?= __('Add Event') ?></h3>
    <?php
        echo $this->Form->control('name');
        echo $this->Form->control('description');
        echo $this->Form->input( 'startdate', [
            'templates' => ['dateWidget' => '{{day}}{{month}}{{year}}'],
            'label' => 'Event Start Date',
            'minYear' => $today,
            'maxYear' => $today + 10,
            'orderYear' => 'asc',
            'empty' => [
                'year' => 'Choose year...',
                'month' => 'Choose month...',
                'day' => 'Choose day...'
            ]
        ]);
        echo $this->Form->text('starttime', [
            'value' => '00:00',
            'placeholder' => '00:00'
        ]);
        echo $this->Form->input( 'enddate', [
            'templates' => ['dateWidget' => '{{day}}{{month}}{{year}}'],
            'label' => 'Event End Date',
            'minYear' => $today,
            'maxYear' => $today + 10,
            'orderYear' => 'asc',
            'empty' => [
                'year' => 'Choose year...',
                'month' => 'Choose month...',
                'day' => 'Choose day...'
            ]
        ]);
        echo $this->Form->text('endtime', [
            'value' => '00:00',
            'placeholder' => '00:00'
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