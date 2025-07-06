<?php
defined( 'ABSPATH' ) || exit;
$fields = $form_data->get_form_fields();
?>
<form class="font-['Noto_Sans_Mono',monospace]">
	<?php foreach ( $fields as $field ) : ?>
		<div class="flex flex-col gap-2 mb-4">
			<label class="text-[16px] font-[500]">
				<?= esc_html( $field['label'] ) ?>
			</label>
			<?=
				$field['type'] === 'textarea'
				? '<textarea rows="4" 
					class="pl-5 py-3 w-full resize-none h-[250px] border border-gray-300 focus:outline-none text-[16px] resize-none">
				</textarea>'
				: '<input type="' . esc_attr( $field['type'] ) . '" 
						class="pl-5 py-3 w-full border border-gray-300 focus:outline-none text-[16px]" 
					/>'
				?>
		</div>
	<?php endforeach; ?>
	<div class="pt-2">
		<button type="button" class="w-full px-8 py-4 bg-gray-500 text-white hover:bg-gray-600 transition">Send
			Message</button>
	</div>
</form>