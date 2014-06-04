<?php $this->extend('/Layouts/Content/container')?>


<div class="emailQueues index">
    <h2>
        <?php echo __('Black Listed Emails'); ?>
    </h2>

    <?php echo $this->Html->link(__('Add new email'), array('plugin'=>'email_queue','controller'=>'email_black_lists','action'=>'add'), array('class'=>'btn btn-success btn-xs'))?>
    
    
        <?php $this->Paginator->options(array('url'=>$this->request->params['named']+$this->request->params['pass']))?>
        <p>
            <?php echo $this->element('pagination'); ?>
        </p>
    
        <?php echo $this->Form->create(null,
            array(
               'inputDefaults' => array(
                   'required'=>false,
                ),
               'url' => array('plugin'=>'email_queue','controller'=>'email_black_lists','action'=>'filter'),
            )
        ); ?>
    
        <table class="table table-bordered table-striped table-condensed table-hover text-sm">
            <colgroup>
                <col width="1%" />
                <col width="1%" />
                <col width="1%" />
                <col />
                <col width="1%" />
            </colgroup>
            <thead>
            <tr>
                <th style="vertical-align: top;">
                    <?php
                        echo $this->Form->input('EmailBlackList.email',
                            array(
                                'label' => $this->Paginator->sort('email', __('Blocked email')),
                                'required' => false,
                                'div'=>false,
                            )
                        );
                    ?>
                </th>
    
                <th style="vertical-align: top;"  class="text-center">
                    <?php echo $this->Paginator->sort('created'); ?>
                </th>
    
                <th style="vertical-align: top;" class="text-center">
                    <?php echo $this->Paginator->sort('blocked_by'); ?>
                </th>
                <th style="vertical-align: top;" class="text-center">
                    <?php echo $this->Paginator->sort('note'); ?>
                </th>
                
                 <th style="vertical-align: top;" class="text-center">
                </th>
            </tr>
            </thead>
            <?php foreach ($dataBlackListedEmails as $r): ?>
            <tr>
                <td nowrap="nowrap">
                    <span><?php echo $r['EmailBlackList']['email'] ?></span>
                </td>
    
                <td nowrap="nowrap" class="text-center">
                    <span class="text-muted"><?php  echo $this->Time->niceShort(h($r['EmailBlackList']['created']), new DateTimeZone(date_default_timezone_get())); ?></span>
                </td>
    
                <td nowrap="nowrap" class="text-center">
                    <span class="text-muted"><?php echo h($r['User']['Person']['full_name']); ?></span>
                </td>
               
                <td  <?php if(empty($r['EmailBlackList']['note'])) echo ' class="text-center"';?>>
                    <?php if(!empty($r['EmailBlackList']['note'])) :?>
                        <span class="text-muted"><?php  echo ($r['EmailBlackList']['note']); ?></span>
                    <?php else :?>
                        <span class="text-muted">- - -</span>
                    <?php endif; ?>
                 </td>
                 
                 <td nowrap="nowrap">
                        <?php echo $this->Html->link($this->Html->tag('i', '', array('class'=>'icon-edit')), ['plugin'=>'email_queue','controller'=>'email_black_lists','action'=>'edit',$r['EmailBlackList']['id']],
                        array('class'=>array('btn btn-warning btn-xs'), 'escape'=>false, 'rel'=>'edit', 'title'=>__('Edit'))
                        );?>
                        
                        <?php echo $this->Html->link($this->Html->tag('i', '', array('class'=>'icon-remove')), ['plugin'=>'email_queue','controller'=>'email_black_lists','action'=>'delete',$r['EmailBlackList']['id']],
                        array('class'=>array('btn btn-danger btn-xs'), 'escape'=>false, 'rel'=>'delete', 'title'=>__('Delete')),
                        __('The email will be deleted from black list. Are you sure?')
                        );?>
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

