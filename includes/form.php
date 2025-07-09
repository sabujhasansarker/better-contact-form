<?php
defined( 'ABSPATH' ) || exit;
$fields = $form_data;
?>
<div id="bcf-messages"></div>
<form class="font-['Noto_Sans_Mono',monospace]" id="bcf-form">
	<?php foreach ( $fields as $field ) : ?>
		<div class="flex flex-col gap-2 mb-4">
			<label class="text-[16px] font-[500]">
				<?= esc_html( $field['label'] ) ?>
			</label>
			<?=
				$field['type'] === 'textarea'
				? '<textarea rows="4"  name="bcf_' . esc_attr( $field['id'] ) . '"
					class="pl-5 py-3 w-full resize-none h-[250px] border border-gray-300 focus:outline-none text-[16px] resize-none">
				</textarea>'
				: '<input name="bcf_' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" 
						class="pl-5 py-3 w-full border border-gray-300 focus:outline-none text-[16px]" 
					/>'
				?>
		</div>
	<?php endforeach; ?>
	<div class="pt-2">
		<button type="submit" class="w-full px-8 py-4 bg-gray-500 text-white hover:bg-gray-600 transition">Send
			Message</button>
	</div>
</form>