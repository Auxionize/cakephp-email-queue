<?php $this->extend('/Layouts/Content/container')?>


<div class="emailQueues index">
    <h2>
        <?php echo __('Email Queue'); ?>
        <small>
            <?php echo $this->element('paging', array('format'=>'Page %page% of %pages%, total %count% record(s)')); ?>
        </small>
    </h2>

    <?php $this->Paginator->options(array('url'=>$this->request->params['named']+$this->request->params['pass']))?>
    <?php echo $this->element('pagination'); ?>


    <?php echo $this->Form->create(null,
        array(
           'inputDefaults' => array(
               'required'=>false,
            ),
           'url' => array('action'=>'filter'),
        )
    ); ?>

    <table class="table table-bordered table-striped table-condensed table-hover text-sm">
        <colgroup>
            <col width="1%" />
            <col />
            <col />
            <col width="1%" />
            <col width="1%" />
        </colgroup>
        <thead>
        <tr>
            <th valign="top">
                <?php
                    echo $this->Form->input('EmailQueue.subject',
                        array(
                            'label' => $this->Paginator->sort('subject', __('Subject')),
                            'required' => false,
                        )
                    );
                ?>
                <div class="form-group">
                    <?php echo $this->Form->button($this->Html->icon('filter') . __('Filter'),
                        array('class'=>'btn btn-primary', 'type'=>'submit')) ?>
                    <?php echo $this->Html->link(__('Reset Filter'), array('action' => 'index')); ?>
                </div>
            </th>

            <th style="vertical-align: top;">
                <?php
                    echo $this->Form->input('EmailQueue.to',
                        array(
                            'label' => $this->Paginator->sort('to', __('To')),
                            'required' => false,
                            'div'=>false,
                        )
                    );
                ?>
            </th>

            <th style="vertical-align: top;">
                <?php
                    echo $this->Form->input('EmailQueue.template',
                    array(
                        'label' => $this->Paginator->sort('template') . ', ' . $this->Paginator->sort('format'),
                        'options' => $templateOptions,
                        'empty' => __('Please Select ...'),
                        'div'=>false,
                    )
                ); ?>
            </th>

            <th style="vertical-align: top;">
                <?php echo $this->Paginator->sort('created'); ?>
            </th>

            <th style="vertical-align: top;">
                <?php echo $this->Paginator->sort('send_at'); ?>
            </th>
            <th style="vertical-align: top;">
            <?php
                $options = array(EmailQueue::EMAIL_STATUS_SENT, EmailQueue::EMAIL_STATUS_SENDING, EmailQueue::EMAIL_STATUS_ERROR, EmailQueue::EMAIL_STATUS_PENDING,EmailQueue::EMAIL_STATUS_BLACK_LISTED);
                echo $this->Form->input('EmailQueue.status',
                    array(
                        'label' => __('Status'),
                        'options' => array_combine($options, $options),
                        'empty' => __('Please Select ...'),
                        'div'=>false,
                    )
                ); ?>
            </th>
        </tr>
        </thead>

        <?php foreach ($emailQueues as $r): ?>
        <tr>
            <td nowrap="nowrap">
                <?php echo $this->Html->link(h($r['EmailQueue']['subject']),array('action'=>'view', $r['EmailQueue']['id']) ); ?>
                <?php if (!empty($r['EmailQueue']['template_vars']['language'])) : ?>
                <span class="text-muted label label-default text-upper"><?php echo $r['EmailQueue']['template_vars']['language'] ?></span>
                <?php endif; ?>
            </td>

            <td>
                <?php if ($r['EmailQueue']['to_name'] != $r['EmailQueue']['to']) : ?>
                    <?php echo h($r['EmailQueue']['to_name']) .'<br>'; ?>
                <?php endif; ?>
                <span class="text-muted"><?php echo h($r['EmailQueue']['to']); ?></span>
            </td>

            <td>
                <?php echo h($r['EmailQueue']['template']); ?> <span class="label label-default"><?php echo h($r['EmailQueue']['format']); ?></span>
            </td>
            <td nowrap="nowrap">
                <?php
                    echo $this->Time->niceShort(h($r['EmailQueue']['created']), new DateTimeZone(date_default_timezone_get()));
                ?>
            </td>
            <td nowrap="nowrap">
                <?php if ($r['EmailQueue']['sent']) : ?>
                    <?php
                        // $r['EmailQueue']['send_at'] is GMT (UTC) date. We need to convert it to default timezone
                        $_tzSentAt = new DateTime($r['EmailQueue']['send_at'], new DateTimeZone('UTC'));
                        $_tzSentAt->setTimezone(new DateTimeZone(date_default_timezone_get()));

                        echo $this->Time->niceShort($_tzSentAt);
                    ?>
                <?php endif; ?>
            </td>

            <td>
            <?php
                echo $a = $this->element('email_status', compact('r'));
            ?>
            &nbsp;
            <?php
                if (strpos($a, 'error') !== false) {
                    echo $this->Html->link('resend', array('action'=>'resetEmailStats', $r['EmailQueue']['id']), array('class'=>'btn btn-warning btn-xs'));
                }
            ?>
            </td>

        </tr>
    <?php endforeach; ?>

    </table>

    <?php echo $this->Form->end(false /* do not submit hidden referrer field */);?>

    <?php echo $this->element('pagination'); ?>
    <div class="pull-right">
        <?php echo $this->element('paging', array('format'=>'Page %page% of %pages%, total %count% record(s)')); ?>
    </div>

</div>

