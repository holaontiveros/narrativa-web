# Advertising system

This theme implements advertising as a small Sage Blade component backed by
the WordPress `ad` custom post type and ACF fields. It does not use a separate
ad service, taxonomy, cache, schedule, impression counter, or click tracker.

## Source of truth

- The `ad` custom post type is registered in `config/post-types.php`. Only
  published Ads are eligible; the post title is its only native WordPress
  field.
- ACF group **Post ad content** (`app/acf-json/group_6a49c86a8ee87.json`)
  defines the targeting and creative fields. WordPress should load this JSON
  group for the `ad` post type.
- `app/View/Components/AddSpace.php` resolves the eligible Ad and creative.
- `resources/views/components/add-space.blade.php` renders it.

## Editorial configuration

Create a published **Ads** item in wp-admin and configure the following ACF
fields.

| Field | Values / meaning |
| --- | --- |
| `ad_type` | Required. `image` or `html`. |
| `scope` | `site` (global fallback), `category`, or `post`. Defaults to `site`. |
| `post` | Required when scope is `post`; a one-item relationship to a standard WordPress post. |
| `tax` | Required when scope is `category`; one or more standard `category` term IDs. |
| `location` | Required checkbox list of the slots in which this Ad can compete. An Ad can target several locations. |
| `ad_spaces` | Optional, unbounded repeater of creative variants. One row is selected randomly after an Ad is selected. |

Each `ad_spaces` row contains either required `html_body` (for HTML ads) or
required `banner` and `banner_mobile` attachment IDs (for image ads). The
field requirements are conditional in wp-admin; the renderer does not reject
an incomplete row before selection.

## Slots and template placement

Every slot is rendered as `<x-add-space space-id="…" />`.

| Slot ID | Placement | Template |
| --- | --- | --- |
| `main_featured` | Inside the Featured Posts shortcode | `resources/views/shortcodes/featured-posts.blade.php` |
| `cat_start` | Above an archive/category listing | `resources/views/index.blade.php` |
| `cat_content` | After the third post in an archive listing | `resources/views/index.blade.php` |
| `post_start` | Before a single post's content | `resources/views/single.blade.php` |
| `footer` | Above the footer area on index, single, and page templates | `resources/views/index.blade.php`, `single.blade.php`, `page.blade.php` |
| `sticky` | Bottom-right fixed ad, included globally from the footer section | `resources/views/sections/footer.blade.php` |
| `popup` | Full-screen overlay, included globally from the footer section | `resources/views/sections/footer.blade.php` |

The PHP component's inline comment omits `cat_content`, `sticky`, and `popup`,
but all three are valid ACF `location` choices and active template slots.

## Matching and random selection

For every component invocation, `AddSpace` loads **all published** `ad` posts,
reads their ACF fields, and first filters to Ads whose `location` includes the
requested slot ID. It then applies context-sensitive priority:

| Request context | Priority, highest to lowest |
| --- | --- |
| Single post | Exact `post` match → any matching `category` → `site` |
| Category archive | Exact queried `category` → `site` |
| All other contexts (home, pages, search, etc.) | `site` only |

Within the first non-empty priority tier, Laravel Collection `random()` picks
one eligible Ad with equal probability. It never merges tiers: an exact post
match suppresses category and site Ads, and a category match suppresses site
Ads. If the post has multiple categories, any overlapping category makes an Ad
eligible.

After the Ad is chosen, `random()` independently selects one `ad_spaces`
repeater row, again with equal probability. Consequently, if an Ad has `n`
creative rows, each row is shown with probability `1/n` whenever that Ad wins
its targeting tier. Selection happens separately for each rendered slot and
each page request; there is no session affinity, rotation history, or frequency
cap.

### Creative preview override

Use a positive, one-based `ad_space` query parameter to preview a particular
creative across every Ad in the winning targeting tier. For example,
`?ad_space=1` renders the first eligible creative and `?ad_space=2` renders
the second. The pool is flattened in ascending Ad ID order, retaining the ACF
repeater order within each Ad, so the same preview URL is deterministic while
the configured Ads and rows remain unchanged.

The override preserves scope, location, and fallback resolution, but bypasses
both normal random steps for the selected slot. This affects every rendered
slot on that request; each slot builds its own eligible pool. An invalid, zero,
negative, or non-numeric value retains normal production rotation; a valid
index beyond the flattened pool renders no creative for that slot.

Without the parameter, production rotation remains intentionally unchanged:
one matching Ad is picked at random, then one of that Ad's creative rows is
picked at random. It does not use the flattened preview pool.

If no eligible Ad exists, or the selected Ad has no usable row, the component
renders nothing.

## Rendering behavior

### HTML

`html_body` is emitted unescaped (`{!! $htmlBody !!}`), so it can run ad
scripts. The component displays a loading indicator and removes it once a
visible non-script element appears, or after 15 seconds. This makes the field a
trusted-admin input: its contents are not sanitized by the template.

### Images

Desktop and mobile attachment images are requested at WordPress `full` size.
The desktop image is visible at `md` and above; the mobile image below `md`.
They lazy-load and fade in after loading. Their displayed boxes use fixed
Tailwind sizes (`w-232 h-45` desktop and `w-75 h-25` mobile) with
`object-cover`, so source assets may be cropped. Image creatives are display
only: the current data model and template have no destination URL or anchor.

### Sticky and popup controls

`sticky` gets a close button and remains fixed at the bottom-right. `popup`
gets a close button, starts hidden, and becomes visible 10 seconds after the
window `load` event. Closing either removes it from the DOM for that page view.

## Constraints to preserve or revisit in an improvement

- Matching is in PHP and fetches every published Ad on every slot render. A
  page with several slots repeats that query and ACF reads.
- An incomplete creative row can win the second random draw. The system does
  not retry another row or another matching Ad, so the slot can be empty even
  when another creative is valid.
- A selected Ad's creative rows are not tied to individual locations. The same
  row can be used in any location selected on that Ad.
- No campaign dates, weights, priorities within a tier, URL/click tracking,
  impression tracking, cache-aware deterministic selection, or accessibility
  alternative text controls are modeled.
- `is_single()` can be true for non-`post` singular types, but the ACF post
  relationship only permits standard posts and category fallback uses the
  standard `category` taxonomy.
