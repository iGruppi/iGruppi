<div id="domains_details">
    <h4>Actions log</h4>
    <p>Contact type: <strong><?php echo $this->domain->type; ?></strong></p>
    <div class="sub_menu">
        <a class="menu add" href="javascript:void(0);" onclick="jx_Domains_addContactInfo();">Add action</a>
    </div>

<?php if(isset($this->contact) && !is_null($this->contact)): ?>
    <?php if($this->contact->review != "" && $this->contact->review_dt > 0): ?>
    <h4 class="green">Reviewed on <?php echo $this->date($this->contact->review_dt, "mydate"); ?></h4>
    <p>
        <a href="<?php echo $this->contact->review; ?>">Read the Review</a>
    </p>
    <?php endif; ?>
    
    <ol>
    <?php foreach($this->times AS $time) : ?>
        <li><strong><?php echo $time->name." ".$time->surname; ?></strong> on <strong><?php echo $this->date($time->dt_contact, "mydate"); ?> - <?php echo $this->date($time->dt_contact, "mytime"); ?></strong> (<a id="a_editinfo_<?php echo $time->idadt; ?>" href="javascript:void(0);" onclick="jx_Domains_editContactInfo(<?php echo $time->idadt; ?>)">Edit</a>)
        <?php if($time->action != ""): ?>
            <br /> -> <?php echo $time->action; ?>
        <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ol>
<?php else: ?>
    <h3 class="alert">Never contacted!</h3>
    <p>
        <a href="/appsdomains/contact/iddomain/<?php echo $this->domain->iddomain;?>">Contact now!</a>
    </p>
<?php endif; ?>
</div>
