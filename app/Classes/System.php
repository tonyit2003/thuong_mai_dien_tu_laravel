<?php

namespace App\Classes;

class System
{
    public function config()
    {
        $data['homepage'] = [
            'label' => __('form.general_info'),
            'description' => __('form.system_description'),
            'value' => [
                'company' => [
                    'type' => 'text',
                    'label' => __('form.company_name')
                ],
                'brand' => [
                    'type' => 'text',
                    'label' => __('form.brand_name')
                ],
                'slogan' => [
                    'type' => 'text',
                    'label' => __('form.company_slogan')
                ],
                'logo' => [
                    'type' => 'images',
                    'label' => __('form.company_logo'),
                    'title' => __('form.click_to_add_logo')
                ],
                'favicon' => [
                    'type' => 'images',
                    'label' => __('form.company_favicon'),
                    'title' => __('form.click_to_add_favicon')
                ],
                'copyright' => [
                    'type' => 'text',
                    'label' => __('form.copyright')
                ],
                'website' => [
                    'type' => 'select',
                    'label' => __('form.website_status'),
                    'option' => [
                        'open' => __('form.website_open'),
                        'close' => __('form.website_close'),
                    ],
                ],
                'short' => [
                    'type' => 'editor',
                    'label' => __('form.short_intro')
                ],
            ],
        ];

        $data['contact'] = [
            'label' => __('form.contact_info'),
            'description' => __('form.contact_description'),
            'value' => [
                'office' => [
                    'type' => 'text',
                    'label' => __('form.contact_office')
                ],
                'address' => [
                    'type' => 'text',
                    'label' => __('form.contact_address')
                ],
                'hotline' => [
                    'type' => 'text',
                    'label' => __('form.hotline')
                ],
                'technical_phone' => [
                    'type' => 'text',
                    'label' => __('form.technical_phone')
                ],
                'sell_phone' => [
                    'type' => 'text',
                    'label' => __('form.sell_phone')
                ],
                'phone' => [
                    'type' => 'text',
                    'label' => __('form.contact_phone')
                ],
                'fax' => [
                    'type' => 'text',
                    'label' => __('form.fax')
                ],
                'email' => [
                    'type' => 'text',
                    'label' => __('form.email')
                ],
                'tax' => [
                    'type' => 'text',
                    'label' => __('form.tax')
                ],
                'website' => [
                    'type' => 'text',
                    'label' => __('form.website')
                ],
                'map' => [
                    'type' => 'textarea',
                    'label' => __('form.map'),
                    'link' => [
                        'text' => __('form.instruct_map'),
                        'href' => "https://manhan.vn/hoc-website-nang-cao/huong-dan-nhung-ban-do-vao-website/",
                        'target' => "_blank",
                    ],
                ],
            ],
        ];

        $data['seo'] = [
            'label' => __('form.seo_home_title'),
            'description' => __('form.seo_home_description'),
            'value' => [
                'meta_title' => [
                    'type' => 'text',
                    'label' => __('form.seo_title')
                ],
                'meta_keyword' => [
                    'type' => 'text',
                    'label' => __('form.seo_keyword')
                ],
                'meta_description' => [
                    'type' => 'text',
                    'label' => __('form.seo_description')
                ],
                'meta_image' => [
                    'type' => 'images',
                    'label' => __('form.seo_image'),
                ],
            ],
        ];

        $data['social'] = [
            'label' => __('form.social_home_title'),
            'description' => __('form.social_home_description'),
            'value' => [
                'facebook' => [
                    'type' => 'text',
                    'label' => __('form.facebook')
                ],
                'youtube' => [
                    'type' => 'text',
                    'label' => __('form.youtube')
                ],
                'twitter' => [
                    'type' => 'text',
                    'label' => __('form.twitter')
                ],
                'tiktok' => [
                    'type' => 'text',
                    'label' => __('form.tiktok')
                ],
                'instagram' => [
                    'type' => 'text',
                    'label' => __('form.instagram')
                ],
            ],
        ];

        return $data;
    }
}
