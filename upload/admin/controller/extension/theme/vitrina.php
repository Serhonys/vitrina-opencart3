<?php
class ControllerExtensionThemeVitrina extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/theme/vitrina');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (!isset($this->request->get['store_id'])) {
			$this->request->get['store_id'] = 0;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('theme_vitrina', $this->request->post, $this->request->get['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true));
		}

		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/theme/vitrina', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true)
		);

		$data['action'] = $this->url->link('extension/theme/vitrina', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$setting_info = $this->model_setting_setting->getSetting('theme_vitrina', $this->request->get['store_id']);
		} else {
			$setting_info = array();
		}

		$defaults = $this->defaults();

		$labels = array(
			'heading_title',
			'text_edit',
			'text_enabled',
			'text_disabled',
			'entry_status',
			'entry_product_limit',
			'entry_product_description_length',
			'help_product_limit',
			'help_product_description_length',
			'button_save',
			'button_cancel'
		);

		foreach ($labels as $label) {
			$data[$label] = $this->language->get($label);
		}

		$data['theme_vitrina_status'] = $this->pick('theme_vitrina_status', $setting_info, $defaults['theme_vitrina_status']);
		$data['theme_vitrina_product_limit'] = $this->pick('theme_vitrina_product_limit', $setting_info, $defaults['theme_vitrina_product_limit']);
		$data['theme_vitrina_product_description_length'] = $this->pick('theme_vitrina_product_description_length', $setting_info, $defaults['theme_vitrina_product_description_length']);

		$data['error_product_limit'] = isset($this->error['product_limit']) ? $this->error['product_limit'] : '';
		$data['error_product_description_length'] = isset($this->error['product_description_length']) ? $this->error['product_description_length'] : '';

		$images = array(
			'image_category' => array(480, 480),
			'image_thumb' => array(800, 800),
			'image_popup' => array(1200, 1200),
			'image_product' => array(600, 600),
			'image_additional' => array(140, 140),
			'image_related' => array(400, 400),
			'image_compare' => array(120, 120),
			'image_wishlist' => array(80, 80),
			'image_cart' => array(80, 80),
			'image_location' => array(268, 50)
		);

		$data['images'] = array();

		foreach ($images as $name => $size) {
			$width_key = 'theme_vitrina_' . $name . '_width';
			$height_key = 'theme_vitrina_' . $name . '_height';

			$data['images'][] = array(
				'label' => $this->language->get('entry_' . $name),
				'width_name' => $width_key,
				'height_name' => $height_key,
				'width' => $this->pick($width_key, $setting_info, $size[0]),
				'height' => $this->pick($height_key, $setting_info, $size[1]),
				'error' => isset($this->error[$name]) ? $this->error[$name] : ''
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/theme/vitrina', $data));
	}

	public function install() {
		$this->load->model('setting/setting');

		$this->model_setting_setting->editSetting('theme_vitrina', $this->defaults(), 0);
	}

	public function uninstall() {
		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSetting('theme_vitrina');
	}

	private function defaults() {
		return array(
			'theme_vitrina_status' => 1,
			'theme_vitrina_product_limit' => 12,
			'theme_vitrina_product_description_length' => 160,
			'theme_vitrina_image_category_width' => 480,
			'theme_vitrina_image_category_height' => 480,
			'theme_vitrina_image_thumb_width' => 800,
			'theme_vitrina_image_thumb_height' => 800,
			'theme_vitrina_image_popup_width' => 1200,
			'theme_vitrina_image_popup_height' => 1200,
			'theme_vitrina_image_product_width' => 600,
			'theme_vitrina_image_product_height' => 600,
			'theme_vitrina_image_additional_width' => 140,
			'theme_vitrina_image_additional_height' => 140,
			'theme_vitrina_image_related_width' => 400,
			'theme_vitrina_image_related_height' => 400,
			'theme_vitrina_image_compare_width' => 120,
			'theme_vitrina_image_compare_height' => 120,
			'theme_vitrina_image_wishlist_width' => 80,
			'theme_vitrina_image_wishlist_height' => 80,
			'theme_vitrina_image_cart_width' => 80,
			'theme_vitrina_image_cart_height' => 80,
			'theme_vitrina_image_location_width' => 268,
			'theme_vitrina_image_location_height' => 50
		);
	}

	private function pick($key, $info, $default) {
		if (isset($this->request->post[$key])) {
			return $this->request->post[$key];
		}

		if (isset($info[$key])) {
			return $info[$key];
		}

		return $default;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/theme/vitrina')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['theme_vitrina_product_limit'])) {
			$this->error['product_limit'] = $this->language->get('error_limit');
		}

		if (empty($this->request->post['theme_vitrina_product_description_length'])) {
			$this->error['product_description_length'] = $this->language->get('error_description_length');
		}

		$images = array(
			'image_category',
			'image_thumb',
			'image_popup',
			'image_product',
			'image_additional',
			'image_related',
			'image_compare',
			'image_wishlist',
			'image_cart',
			'image_location'
		);

		foreach ($images as $name) {
			if (empty($this->request->post['theme_vitrina_' . $name . '_width']) || empty($this->request->post['theme_vitrina_' . $name . '_height'])) {
				$this->error[$name] = $this->language->get('error_' . $name);
			}
		}

		return !$this->error;
	}
}
