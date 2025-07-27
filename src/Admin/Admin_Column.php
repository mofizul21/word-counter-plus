<?php

namespace WCP\Admin;

/**
 * Class Admin_Column
 *
 * @package WCP\Admin
 *
 * @since 1.2.0
 */
class Admin_Column
{
    /**
     * Admin_Column constructor.
     *
     * @since 1.2.0
     */
    public function __construct()
    {
        add_filter('manage_posts_columns', [$this, 'add_column']);
        add_action('manage_posts_custom_column', [$this, 'display_column'], 10, 2);
        add_filter('manage_edit-post_sortable_columns', [$this, 'sortable_column']);
        add_action('save_post', [$this, 'save_word_count_meta']);
    }

    /**
     * Add the word count column to the admin post list.
     *
     * @param array $columns
     *
     * @since 1.2.0
     * @return array
     */
    public function add_column(array $columns): array
    {
        $columns['word_count'] = __('Word Count', 'word-counter-plus');
        return $columns;
    }

    /**
     * Display the word count in the custom column.
     *
     * @param string $column_name
     * @param int $post_id
     *
     * @since 1.2.0
     * @return void
     */
    public function display_column(string $column_name, int $post_id): void
    {
        if ('word_count' === $column_name) {
            $content = get_post_field('post_content', $post_id);
            $word_count = $this->count_words($content);
            echo esc_attr($word_count);
        }
    }

    /**
     * Make the word count column sortable.
     *
     * @param array $columns
     *
     * @since 1.2.0
     * @return array
     */
    public function sortable_column(array $columns): array
    {
        $columns['word_count'] = 'word_count';
        return $columns;
    }

    /**
     * Count the words in the given content.
     *
     * @param string $content
     *
     * @since 1.2.0
     * @return int
     */
    public function count_words(string $content): int
    {
        $content = strip_shortcodes($content);
        $content = wp_strip_all_tags($content);
        $content = preg_replace("/\s+/", ' ', $content);
        $content = explode(' ', $content);
        $content = array_filter($content);
        return count($content);
    }

    /**
     * Save the word count as post meta.
     *
     * @param int $post_id
     *
     * @since 1.2.0
     * @return void
     */
    public function save_word_count_meta(int $post_id): void
    {
        if (get_post_type($post_id) !== 'post') {
            return;
        }

        $content = get_post_field('post_content', $post_id);
        $word_count = $this->count_words($content);
        update_post_meta($post_id, '_wcp_word_count', $word_count);
    }
}
