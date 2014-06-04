<?php $this->extend('/Layouts/Content/container')?>

<ul class="breadcrumb">
    <li><?php echo $this->Html->link(__('Emails'), array('action'=>'index'))?></li>
</ul>

<h3>
    <?php echo $data['EmailQueue']['subject']; ?>
    <small>
        <span class="label label-default language"><?php echo mb_strtoupper(@$data['EmailQueue']['template_vars']['language'])?></span>
        <?php echo $data['EmailQueue']['to'] ?>
        <?php echo $this->element('email_status', array('r'=>$data))?>
    </small>
</h3>

<ul class="nav nav-tabs" id="email-tabs">
    <li class="active"><?php echo $this->Html->link(__('Message'), '#message', array('data-toggle'=>'tab')); ?></li>
    <li><?php echo $this->Html->link(__('Details'), '#message-details', array('data-toggle'=>'tab')); ?></li>
    <li><?php echo $this->Html->link(__('Headers'), '#message-headers', array('data-toggle'=>'tab')); ?></li>
    <li><?php echo $this->Html->link(__('Vars'), '#template-vars', array('data-toggle'=>'tab')); ?></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="message">
        <pre style="border: none;"><?php echo h($email_text['message']);?></pre>
    </div>

    <div class="tab-pane" id="message-details">
        <br/>&nbsp;
        <dl class='dl-horizontal text-sm'>
            <dt><?php echo __('id'); ?></dt>
                <dd><?php echo $this->Html->link($data['EmailQueue']['id'],array('action'=>'view', $data['EmailQueue']['id']) ); ?>&nbsp;</dd>

            <dt><?php echo __('to'); ?></dt>
                <dd><?php echo $data['EmailQueue']['to_name'] .'<br>'. $data['EmailQueue']['to']; ?>&nbsp;</dd>

            <dt><?php echo __('subject'); ?></dt>
                <dd><?php echo $data['EmailQueue']['subject']; ?>&nbsp;</dd>

            <dt><?php echo __('template'); ?></dt>
                <dd><?php echo $data['EmailQueue']['template']; ?> / <?php echo $data['EmailQueue']['format']; ?></dd>

            <dt><?php echo __('sent / locked / send_tries'); ?></dt>
                <dd><?php echo $data['EmailQueue']['sent'].' / '.$data['EmailQueue']['locked'].' / '.$data['EmailQueue']['send_tries']; ?>&nbsp;</dd>

            <dt><?php echo __('send_at'); ?></dt>
                  <dd><?php echo $this->Time->niceShort($data['EmailQueue']['send_at']); ?>&nbsp;</dd>

            <dt><?php echo __('created'); ?></dt>
                <dd><?php echo $this->Time->niceShort($data['EmailQueue']['created']); ?>&nbsp;</dd>
        </dl>
    </div>

    <div class="tab-pane" id="message-headers">
        <pre style="border: none;"><?php echo h($email_text['headers']);?></pre>
    </div>

    <div class="tab-pane" id="template-vars">
        <pre style="border: none;"><?php print_r($data['EmailQueue']['template_vars']); ?></pre>
    </div>
</div>

<?php $this->start('script'); ?>
<script>
  $(function () {
      console.log($('#email-tabs a:first'));
    $('#email-tabs a:first').tab('show')
  });
</script>
<?php $this->end();?>
