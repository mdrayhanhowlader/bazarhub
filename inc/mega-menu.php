<?php
if ( ! defined('ABSPATH') ) exit;

class BazaarHub_Mega_Menu extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '<div class="bh-mega-dropdown"><div class="bh-mega-inner">';
        } else {
            $output .= '<ul class="bh-sub-menu">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( $depth === 0 ) {
            $output .= '</div></div>';
        } else {
            $output .= '</ul>';
        }
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes   = empty($item->classes) ? [] : (array) $item->classes;
        $has_child = in_array('menu-item-has-children', $classes);
        $is_mega   = in_array('mega-menu', $classes) || ($depth === 0 && $has_child);
        $cls       = implode(' ', array_filter($classes));
        if ( $is_mega ) $cls .= ' has-mega';

        $atts = [];
        $atts['href']   = !empty($item->url) ? $item->url : '#';
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $attr_str = '';
        foreach ($atts as $k => $v) if ($v) $attr_str .= " $k=\"".esc_attr($v).'"';

        $icon = '';
        if ( $depth === 0 ) {
            // Fetch category image if this item links to a WC category
            $term = get_term_by('slug', basename($item->url), 'product_cat');
            if ( $term ) {
                $tid = get_term_meta($term->term_id, 'thumbnail_id', true);
                if ($tid) $icon = '<span class="bh-nav-cat-img">'.wp_get_attachment_image($tid,[32,32]).'</span>';
            }
        }

        $output .= "<li class=\"bh-nav-item {$cls}\">";
        if ( $depth === 1 ) {
            // Mega column header - show category image
            $term2 = get_term_by('slug', basename($item->url), 'product_cat');
            if ($term2) {
                $tid2 = get_term_meta($term2->term_id, 'thumbnail_id', true);
                if ($tid2) $output .= '<div class="bh-mega-col-header">'.wp_get_attachment_image($tid2,[60,60]).'</div>';
            }
        }
        $output .= "<a{$attr_str}>{$icon}<span>".apply_filters('the_title',$item->title,$item->ID)."</span>";
        if ($depth === 0 && $has_child) $output .= '<i class="fas fa-chevron-down bh-nav-arrow"></i>';
        $output .= "</a>";
    }

    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}

function bazaarhub_category_nav() {
    $categories = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'parent'=>0,'number'=>12,'orderby'=>'count','order'=>'DESC']);
    if ( empty($categories) || is_wp_error($categories) ) return;
    echo '<nav class="bh-cat-nav"><ul class="bh-cat-nav__list">';
    foreach ($categories as $cat) {
        $tid   = get_term_meta($cat->term_id, 'thumbnail_id', true);
        $img   = $tid ? wp_get_attachment_image($tid,[20,20]) : '<i class="fas fa-tag"></i>';
        $url   = get_term_link($cat);
        $subs  = get_terms(['taxonomy'=>'product_cat','parent'=>$cat->term_id,'hide_empty'=>true]);
        $has   = !empty($subs) && !is_wp_error($subs);
        echo '<li class="bh-cat-nav__item'.($has?' has-sub':'').'">';
        echo '<a href="'.$url.'" class="bh-cat-nav__link"><span class="bh-cat-nav__icon">'.$img.'</span><span>'.esc_html($cat->name).'</span>'.($has?'<i class="fas fa-chevron-right"></i>':'').'</a>';
        if ($has) {
            echo '<div class="bh-cat-subnav"><ul>';
            foreach ($subs as $sub) {
                $tid2 = get_term_meta($sub->term_id,'thumbnail_id',true);
                $img2 = $tid2 ? wp_get_attachment_image($tid2,[24,24]) : '';
                echo '<li><a href="'.get_term_link($sub).'">'.$img2.'<span>'.esc_html($sub->name).'</span></a></li>';
            }
            echo '</ul></div>';
        }
        echo '</li>';
    }
    echo '</ul></nav>';
}
