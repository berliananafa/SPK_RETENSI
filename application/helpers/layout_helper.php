<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Layout Helper — versi ringan
 * Fungsi dasar: layout, breadcrumb, asset, dan level user
 */

if (!function_exists('render_layout')) {
    function render_layout($view, $data = [], $layout = 'main') {
        $CI = &get_instance();
        $data['content'] = $CI->load->view($view, $data, TRUE);
        $CI->load->view("layouts/{$layout}", $data);
    }
}

if (!function_exists('set_page_title')) {
    function set_page_title($title) {
        get_instance()->load->vars(['page_title' => $title]);
    }
}

if (!function_exists('set_breadcrumb')) {
    function set_breadcrumb($items = []) {
        get_instance()->load->vars(['breadcrumb' => $items]);
    }
}

if (!function_exists('set_admin_breadcrumb')) {
    function set_admin_breadcrumb($items = []) {
        set_breadcrumb($items);
    }
}

if (!function_exists('enable_datatables')) {
    function enable_datatables() {
        get_instance()->load->vars(['include_datatables' => TRUE]);
    }
}

if (!function_exists('enable_charts')) {
    function enable_charts() {
        get_instance()->load->vars(['include_charts' => TRUE]);
    }
}

if (!function_exists('enable_sweetalert')) {
    function enable_sweetalert() {
        get_instance()->load->vars(['include_sweetalert' => TRUE]);
    }
}

if (!function_exists('include_css')) {
    function include_css($files) {
        $files = (array) $files;
        $tags = '';
        foreach ($files as $file) {
            $tags .= '<link rel="stylesheet" href="' . base_url($file) . '">' . "\n";
        }
        get_instance()->load->vars(['extra_css' => $tags]);
    }
}

if (!function_exists('include_js')) {
    function include_js($files) {
        $files = (array) $files;
        $tags = '';
        foreach ($files as $file) {
            $tags .= '<script src="' . base_url($file) . '"></script>' . "\n";
        }
        get_instance()->load->vars(['extra_js' => $tags]);
    }
}

if (!function_exists('get_user_level')) {
    function get_user_level() {
        $CI = &get_instance();
        return strtolower($CI->session->userdata('user_level') ?? 'guest');
    }
}

if (!function_exists('can_access')) {
    function can_access($levels = []) {
        return in_array(get_user_level(), $levels);
    }
}

if (!function_exists('is_active_menu')) {
    function is_active_menu($path) {
        return trim(uri_string(), '/') === trim($path, '/') ? 'active' : '';
    }
}

if (!function_exists('esc')) {
    /**
     * Escape HTML entities for output
     * Compatible with CodeIgniter 4 style
     * 
     * @param string|array $data
     * @param string $context (html, js, css, url, attr)
     * @return string|array
     */
    function esc($data, $context = 'html') {
        if (is_array($data)) {
            foreach ($data as &$value) {
                $value = esc($value, $context);
            }
            return $data;
        }

        $data = (string) $data;

        switch ($context) {
            case 'html':
                return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            
            case 'js':
                return str_replace(
                    ['\\', "'", '"', "\n", "\r", '</', '<!--', '-->'],
                    ['\\\\', "\\'", '\\"', '\\n', '\\r', '<\\/', '<!--', '-->'],
                    $data
                );
            
            case 'css':
                return preg_replace('/[^a-zA-Z0-9]/', '\\\\$0', $data);
            
            case 'url':
                return rawurlencode($data);
            
            case 'attr':
                return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            
            default:
                return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
}

if (!function_exists('add_css')) {
    function add_css($css) {
        $CI = &get_instance();
        $existing = $CI->load->get_var('additional_css') ?? '';
        $CI->load->vars(['additional_css' => $existing . $css]);
    }
}

if (!function_exists('add_js')) {
    function add_js($js) {
        $CI = &get_instance();
        $existing = $CI->load->get_var('additional_js') ?? '';
        $CI->load->vars(['additional_js' => $existing . $js]);
    }
}
