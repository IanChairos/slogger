<?php
// source: /data/projects/other/skrz_logger/app/AdminModule/templates/Sign/../components/form.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('3588031264', 'html')
;
// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
Nette\Bridges\FormsLatte\FormMacros::renderFormBegin($form = $_form = is_object($form) ? $form : $_control[$form], array()) ;if ($form->ownErrors) { ?><ul class=error>
<?php $iterations = 0; foreach ($form->ownErrors as $error) { ?>	<li><?php echo Latte\Runtime\Filters::escapeHtml($error, ENT_NOQUOTES) ?></li>
<?php $iterations++; } ?>
</ul>
<?php } ?>

<table>
<?php $iterations = 0; foreach ($form->controls as $input) { ?><tr<?php if ($_l->tmp = array_filter(array($input->required ? 'required' : NULL))) echo ' class="' . Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT) . '"' ?>>
	<th><?php $_input = is_object($input) ? $input : $_form[$input]; if ($_label = $_input->getLabel()) echo $_label  ?></th>
	<td><?php $_input = is_object($input) ? $input : $_form[$input]; echo $_input->getControl() ?>
 <?php ob_start() ?><span class=error><?php ob_start() ;echo Latte\Runtime\Filters::escapeHtml($input->error, ENT_NOQUOTES) ;$_l->ifcontent = ob_get_contents(); ob_end_flush() ?>
</span><?php rtrim($_l->ifcontent) === "" ? ob_end_clean() : ob_end_flush() ?>
</td>
</tr>
<?php $iterations++; } ?>
</table>
<?php Nette\Bridges\FormsLatte\FormMacros::renderFormEnd($_form) ;