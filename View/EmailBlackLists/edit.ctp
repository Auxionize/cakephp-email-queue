<?php echo $this->extend('/Layouts/Content/container'); ?>

<?php echo $this->Session->flash(); ?>
<?php echo $this->Form->create('EmailBlackList',
    array(
        'autocomplete' => 'off',
    )
) ?>
<?php echo $this->Form->input('id'); ?>
<?php echo $this->Html->panelHeading($this->Form->value('id') ? __('Edit') : __('Create')); ?>.
<div class="panel-body">

<?php echo $this->Form->input('email',
    array(
        'autocomplete' => 'off',
        'error' => array(
            'isEmail' => __('Please enter valid email'),
            'isUnique' => __('This email is already registered'),
        )
    )
)?>

<div class="row">

<?php echo $this->Form->input('note',
    array(
        'div' => array('class'=>array('col-sm-12')),
    )
)?>
</div>

<div class="form-group">
    <?php echo $this->Form->button($this->Html->icon('save') . __('Save'),
        array('class'=>'btn btn-primary', 'type'=>'submit')
    ) ?>
    <?php echo $this->Form->cancel(); ?>
</div>
</div>
<?php echo $this->Form->end();?>

