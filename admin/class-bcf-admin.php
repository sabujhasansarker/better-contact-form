<?php
defined( 'ABSPATH' ) || exit;

/**
 * ! IMPORTANT PAGES
 * * Dashboard Page - Just displays content
 * * Form Builder Page - Contains:
 *   1. Form interface
 *   2. Form save functionality
 */
class BCF_Admin {
	var $prant_slug = 'edit.php?post_type=bcf_submission';
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'wp_ajax_bcf_save_form', [ $this, 'bcf_save_form' ] );
	}
	public function add_admin_menu() {
		add_submenu_page(
			$this->prant_slug,
			'Dashboard',
			'Dashboard',
			'manage_options',
			'bcf-dashboard',
			[ $this, 'render_dashboard_page' ]
		);
		add_submenu_page(
			$this->prant_slug,
			'Form Builder',
			'Form Builder',
			'manage_options',
			'bcf-form-builder',
			[ $this, 'render_form_builder_page' ]
		);
	}
	public function render_dashboard_page() {
		?>
		<h1>Dashboard</h1>
		<?php
	}
	public function render_form_builder_page() {
		$form_data = get_option( 'bcf_form_fields', $this->get_form_fields() );
		?>
		<div class="font-['Noto_Sans_Mono',monospace] py-[50px]" x-init="init(<?= esc_attr( json_encode( $form_data ) ) ?>)"
			x-data="formBuilder">
			<div class="max-w-6xl mx-auto px-4">
				<h1 class="text-[70px] font-[800] text-center text-[#000] mb-8 font-['Poppins',sans-serif]">
					Form Builder
				</h1>
				<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
					<div>
						<div class="border-2 shadow-sm border-gray-200 p-6">
							<h4 class="mb-4 text-[22px] font-semibold text-gray-800 font-['Poppins',sans-serif]">
								Build Your Form
							</h4>
							<template x-for="(field, index) in fields" :key="field.id">
								<div class="border-2 flex flex-col gap-4 p-6 mb-4">
									<div class="flex gap-3">
										<input type="text" placeholder="Field Label" x-model="field.label"
											class="pl-5 py-3 border border-gray-300 focus:outline-none flex-1 text-[16px]" />
										<select
											class="pl-5 py-3 border border-gray-300 focus:outline-none flex-1 text-[16px]"
											x-model="field.type">
											<option value="text">Text</option>
											<option value="email">Email</option>
											<option value="textarea">Textarea</option>
											<option value="tel">Phone</option>
											<option value="number">Number</option>
										</select>
									</div>
									<div class="flex gap-3 items-center">
										<label class="text-[16px] flex gap-[8px]">
											<input type="checkbox" x-model="field.required" class="rounded" />
											Required
										</label>
										<input type="text" placeholder="Placeholder Text" x-model="field.placeholder"
											class="pl-5 py-3 border border-gray-300 focus:outline-none text-[16px]" />
										<button @click="removeField(index)"
											class="px-4 h-[100%] py-3 flex-1 border border-red-500 hover:border-red-600 bg-red-500 text-white hover:bg-red-600 transition">
											Remove
										</button>
									</div>
								</div>
							</template>
							<button class="mt-6 w-full px-8 py-4 bg-blue-500 text-white hover:bg-blue-600 transition"
								@click="addField()">
								Add Field
							</button>
							<div class="flex gap-4">
								<button @click="saveForm()"
									class="mt-6 w-full px-8 py-4 bg-green-600 text-white hover:bg-green-700 transition">
									Save Form
								</button>
								<button @click="resetForm()"
									class="mt-6 w-full px-8 py-4 bg-gray-600 text-white hover:bg-gray-700 transition">
									Reset Form
								</button>
							</div>
						</div>
						<div class="mt-4 py-4 px-8 transition" x-show="message"
							:class="messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
							<p x-text="message"></p>
						</div>
					</div>
					<div class="border-2 shadow-sm border-gray-200 p-6">
						<h4 class="mb-4 text-[22px] font-semibold text-gray-800 font-['Poppins',sans-serif]">
							Form Preview
						</h4>
						<template x-for="(field, index) in fields" :key="field.id">
							<div class="flex flex-col gap-2 mb-4">
								<label x-text="field.label + (field.required ? '*' : '')" class="text-[16px] font-[500]">
								</label>
								<input x-show="field.type !== 'textarea'" :placeholder="field.placeholder" type="text"
									class="pl-5 py-3 w-full border border-gray-300 focus:outline-none text-[16px]" />
								<textarea x-show="field.type === 'textarea'" :placeholder="field.placeholder"
									class="pl-5 py-3 w-full resize-none h-[250px] border border-gray-300 focus:outline-none text-[16px]"></textarea>
							</div>
						</template>
						<button class="w-full px-8 py-4 bg-gray-500 text-white hover:bg-gray-600 transition">
							Submit Message
						</button>
					</div>
				</div>
			</div>
			<script>
				document.addEventListener("alpine:init", () => {
					Alpine.data("formBuilder", () => ({
						fields: [],
						message: "",
						messageType: "success",
						saving: false,
						init(savedFields) {
							this.fields = savedFields && savedFields.length > 0 ? savedFields : this.getDefultField();
						},
						getDefultField() {
							return [
								{
									id: "name",
									label: "Name",
									type: "text",
									required: true,
									placeholder: "Enter your name",
								},
								{
									id: "email",
									label: "Email",
									type: "email",
									required: true,
									placeholder: "Enter your email",
								},
								{
									id: "message",
									label: "Message",
									type: "textarea",
									required: true,
									placeholder: "Enter your message",
								},
							];
						},
						removeField(index) {
							if (confirm("Are you sure you want to remove this field?")) {
								this.fields.splice(index, 1);
							}
						},
						saveForm() {
							this.saving = true;
							this.message = "";
							fetch(ajaxurl, {
								method: 'POST',
								headers: {
									'Content-Type': 'application/x-www-form-urlencoded',
								},
								body: new URLSearchParams({
									action: 'bcf_save_form',
									nononce: '<?php echo wp_create_nonce( 'bcf_save_form_nonce' ); ?>',
									fields: JSON.stringify(this.fields),
								}),
							})
								.then(response => response.json())
								.then(date => {
									if (date.success) {
										this.message = "Form saved successfully!";
										this.messageType = "success";
									} else {
										this.message = "Error saving form: " + date.data;
										this.messageType = "error";
									}
								})
								.catch(error => {
									this.message = "Error saving form: " + error.message;
									this.messageType = "error";
									setTimeout(() => (this.message = ""), 3000);
								})
								.finally(() => {
									this.saving = false;
									setTimeout(() => this.message = '', 3000);
								});

						},
						resetForm() {
							if (confirm("Are you sure you want to reset the form?")) {
								this.fields = this.getDefultField();
								this.message = "Form reset successfully!";
								this.messageType = "info";
								setTimeout(() => (this.message = ""), 3000);
							}
						},
						addField() {
							const newField = {
								id: "field_" + Date.now(),
								label: "New Field",
								type: "text",
								required: false,
								placeholder: "Enter text",
							};
							this.fields.push(newField);
						},
					}));
				});
			</script>
		</div>
		<?php
	}
	public function bcf_save_form() {
		if ( ! wp_verify_nonce( $_POST['nononce'], 'bcf_save_form_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions' );
		}
		$fields = json_decode( stripslashes( $_POST['fields'] ), true );
		if ( ! is_array( $fields ) ) {
			wp_send_json_error( 'Invalid fields data' );
		}
		/** Validate fields */
		$valid_fields = [];
		foreach ( $fields as $field ) {
			if ( ! isset( $field['id'], $field['label'], $field['type'] ) ) {
				continue; // Skip invalid fields
			}

			$valid_fields[] = [ 
				'id' => sanitize_text_field( $field['id'] ),
				'label' => sanitize_text_field( $field['label'] ),
				'type' => sanitize_text_field( $field['type'] ),
				'required' => ! empty( $field['required'] ),
				'placeholder' => isset( $field['placeholder'] ) ?? '',
			];
		}
		update_option( 'bcf_form_fields', $valid_fields );
		wp_send_json_success( 'Form saved successfully' );
	}
	private function get_form_fields() {
		return [ 
			[ 
				'type' => 'text',
				'label' => 'Name',
				'name' => 'name',
				'placeholder' => 'Enter your name',
				'required' => true,
			],
			[ 
				'type' => 'email',
				'label' => 'Email',
				'name' => 'email',
				'placeholder' => 'Enter your email',
				'required' => true,
			],
			[ 
				'type' => 'textarea',
				'label' => 'Message',
				'name' => 'message',
				'placeholder' => 'Enter your message',
				'required' => true,
			],
		];
	}
}
?>