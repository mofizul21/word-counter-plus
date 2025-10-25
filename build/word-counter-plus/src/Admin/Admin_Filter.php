<?php

namespace WCP\Admin;

/**
 * Class Admin_Filter
 * @package WCP\Admin
 */
class Admin_Filter
{
    /**
     * Admin_Filter constructor.
     *
     * @since 1.2.0
     */
    public function __construct()
    {
        add_action('restrict_manage_posts', [$this, 'add_filter_dropdown']);
        add_action('pre_get_posts', [$this, 'filter_query']);
    }

    /**
     * Add the word count filter dropdown to the admin post list.
     *
     * @since 1.2.0
     * @return void
     */
    public function add_filter_dropdown(): void
    {
        global $typenow;

        if ($typenow === 'post') {
            $selected = $_GET['word_count_range'] ?? '';
            ?>
            <select name="word_count_range">
                <option value=""><?php _e('All Word Counts', 'word-counter-plus'); ?></option>
                <option value="0-600" <?php selected($selected, '0-600'); ?>><?php _e('0–600 words', 'word-counter-plus'); ?></option>
                <option value="601-1000" <?php selected($selected, '601-1000'); ?>><?php _e('601–1000 words', 'word-counter-plus'); ?></option>
                <option value="1001-2000" <?php selected($selected, '1001-2000'); ?>><?php _e('1001–2000 words', 'word-counter-plus'); ?></option>
                <option value="2001-3000" <?php selected($selected, '2001-3000'); ?>><?php _e('2001–3000 words', 'word-counter-plus'); ?></option>
                <option value="3001-4000" <?php selected($selected, '3001-4000'); ?>><?php _e('3001–4000 words', 'word-counter-plus'); ?></option>
                <option value="4001+" <?php selected($selected, '4001+'); ?>><?php _e('4001+ words', 'word-counter-plus'); ?></option>
            </select>
            <?php
        }
    }

    /**
     * Modify the query to filter by the selected word count range.
     *
     * @param \WP_Query $query
     *
     * @since 1.2.0
     * @return void
     */
    public function filter_query($query): void
    {
        global $pagenow;

        if (
            is_admin() &&
            $pagenow === 'edit.php' &&
            isset($_GET['post_type']) && $_GET['post_type'] === 'post' &&
            isset($_GET['word_count_range']) && $_GET['word_count_range'] !== ''
        ) {
            $range = $_GET['word_count_range'];
            $value = $this->get_range_values($range);

            $meta_query = [];

            if (is_array($value)) {
                $meta_query[] = [
                    'key' => '_wcp_word_count',
                    'value' => $value,
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ];
            } elseif (is_numeric($value)) {
                $meta_query[] = [
                    'key' => '_wcp_word_count',
                    'value' => $value,
                    'compare' => ' >= ',
                    'type' => 'NUMERIC',
                ];
            }

            if (!empty($meta_query)) {
                $query->set('meta_query', $meta_query);
            }
        }
    }

    /**
     * Get the numeric values for the selected range.
     *
     * @param string $range
     *
     * @since 1.2.0
     * @return array|int
     */
    private function get_range_values(string $range): array|int
    {
        return match ($range) {
            '0-600' => [0, 600],
            '601-1000' => [601, 1000],
            '1001-2000' => [1001, 2000],
            '2001-3000' => [2001, 3000],
            '3001-4000' => [3001, 4000],
            '4001+' => 4001,
            default => [],
        };
    }
}
