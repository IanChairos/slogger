<?php
// source: /data/projects/other/skrz_logger/app/AdminModule/templates/Dashboard/detail.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('6823243093', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb961b3b5753_content')) { function _lb961b3b5753_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>	<h1><?php echo Latte\Runtime\Filters::escapeHtml($error->getTitle(), ENT_NOQUOTES) ?></h1>
	<table class='table'>
		<tr>
			<td>ID:</td><td><?php echo Latte\Runtime\Filters::escapeHtml($error->getId(), ENT_NOQUOTES) ?></td>
		</tr>
		<tr>
			<td>Service:</td><td><?php echo Latte\Runtime\Filters::escapeHtml($error->getService(), ENT_NOQUOTES) ?></td>
		</tr>
		<tr>
			<td>Priority:</td><td><?php echo Latte\Runtime\Filters::escapeHtml($error->getPriority(), ENT_NOQUOTES) ?></td>
		</tr>
		<tr>
			<td>Description:</td><td><?php echo Latte\Runtime\Filters::escapeHtml($error->getDescription(), ENT_NOQUOTES) ?></td>
		</tr>
		<tr>
			<td>Created:</td><td><?php echo Latte\Runtime\Filters::escapeHtml($template->date($error->getCreated(), 'Y-m-d H:i:s'), ENT_NOQUOTES) ?></td>
		</tr>
		</tr>
		<tr>
			<td>Hash:</td><td><?php echo Latte\Runtime\Filters::escapeHtml($error->getHash(), ENT_NOQUOTES) ?></td>
		</tr>
		<tr>
			<td>Reported:</td><td><?php echo Latte\Runtime\Filters::escapeHtml($error->getReported() ? 'yes' : 'no', ENT_NOQUOTES) ?></td>
		</tr>
	</table>
<?php
}}

//
// block title
//
if (!function_exists($_b->blocks['title'][] = '_lb8fbfb981cf_title')) { function _lb8fbfb981cf_title($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
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
if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['content']), $_b, get_defined_vars())  ?>

<?php call_user_func(reset($_b->blocks['title']), $_b, get_defined_vars()) ; 