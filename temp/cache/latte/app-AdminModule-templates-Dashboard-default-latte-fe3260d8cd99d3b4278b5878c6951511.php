<?php
// source: /data/projects/other/skrz_logger/app/AdminModule/templates/Dashboard/default.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('5730587125', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb7758453595_content')) { function _lb7758453595_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
 ?>

<?php call_user_func(reset($_b->blocks['tableBlock']), $_b, array('errors' => $errorsByTime, 'title' => 'Latest errors') + get_defined_vars()) ;call_user_func(reset($_b->blocks['tableBlock']), $_b, array('errors' => $errorsByPriority, 'title' => 'Errors by priority') + get_defined_vars()) ?>
	
<?php
}}

//
// block tableBlock
//
if (!function_exists($_b->blocks['tableBlock'][] = '_lb8735b8c635_tableBlock')) { function _lb8735b8c635_tableBlock($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>		<div style="width: 45%; margin-right: 10px;" class="pull-left">
			<h2><?php echo Latte\Runtime\Filters::escapeHtml($title, ENT_NOQUOTES) ?></h2>
			
<?php if ($errors) { ?>
			<table class="table table-hover">
				<thead>
				<tr>
					<th>Id</th>
					<th>Service</th>
					<th>Priority</th>
					<th>Title</th>
					<th>Description</th>
					<th>Created</th>
				</tr>
				</thead>
<?php $iterations = 0; foreach ($errors as $error) { ?>				<tr onclick="window.location = <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeJs($_presenter->link("//Dashboard:detail", array($error->getId()))), ENT_COMPAT) ?>">
					<td><?php echo Latte\Runtime\Filters::escapeHtml($error->getId(), ENT_NOQUOTES) ?></td>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($error->getService(), ENT_NOQUOTES) ?></td>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($error->getPriority(), ENT_NOQUOTES) ?></td>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($error->getTitle(), ENT_NOQUOTES) ?></td>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($template->truncate($error->getDescription(), 35), ENT_NOQUOTES) ?></td>
					<td><?php echo Latte\Runtime\Filters::escapeHtml($template->date($error->getCreated(), 'Y-m-d H:i:s'), ENT_NOQUOTES) ?></td>
				</tr>
<?php $iterations++; } ?>
			</table>
<?php } else { ?>
				<p>No errors.</p>
<?php } ?>
		</div>
<?php
}}

//
// block title
//
if (!function_exists($_b->blocks['title'][] = '_lb483f57ffc5_title')) { function _lb483f57ffc5_title($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>Dashboard<?php
}}

//
// end of blocks
//

// template extending

$_l->extends = empty($_g->extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $_g->extended = TRUE;

if ($_l->extends) { ob_start();}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
?>

<?php if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['content']), $_b, get_defined_vars())  ?>

<?php call_user_func(reset($_b->blocks['title']), $_b, get_defined_vars()) ; 