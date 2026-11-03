=== Claudia Maria React Components ===
Contributors: webservicesro
Tags: react, components, pricing
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: __PLUGIN_VERSION__
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

React components and pages for the Claudia Maria website.

== Installation ==

1. Upload the versioned plugin ZIP in Plugins > Add Plugin > Upload Plugin.
2. Activate Claudia Maria React Components.
3. In Elementor, drag the **Claudia Maria Homepage**, **Claudia Maria Header**, or **Claudia Maria Footer** widget onto the appropriate page or Theme Builder template. You can also use the relevant shortcode in a page or post.

The native Elementor homepage widget exposes every homepage section, card, testimonial, image, link, CTA, colour, and content width in Elementor's Content and Style panels. Its content is rendered as semantic HTML on the server for reliable indexing and no-JavaScript access.

The native Elementor header widget exposes its logo, WordPress menu selector or custom navigation items, account links, WooCommerce cart drawer, booking button, popup ID, colours, typography, width, and spacing in Elementor's Content and Style panels.

Pricing button behavior, Elementor popup ID, and WhatsApp messages can be configured globally from the **Claudia Maria** item in the main WordPress admin sidebar. Shortcode attributes override the saved settings on individual pages.

Use `[cm_header]` and `[cm_footer]` to render the standalone responsive global components, and `[cm_homepage]` to render the homepage content. The homepage does not include the header or footer.

For the pricing-page variant, enable the optional payment-information panel above the footer in Elementor or use `[cm_footer show_payment_info="true"]`. Elementor provides three media-library image slots, alt text fields, and optional links for the consumer-protection, payment-processor, and online-dispute graphics.

`[cm_header home_url="/" booking_url="/contact/" popup_id="123" pricing_url="/preturi/" login_url="/contul-meu/" account_url="/contul-meu/" cart_url="/cos/"]`

The header automatically displays Login or Logout based on the current WordPress session. When WooCommerce is active, the cart URL and item count are detected automatically.

Homepage links and the booking popup can be configured with:

`[cm_homepage booking_url="#contact" popup_id="123" therapies_url="/servicii/" courses_url="/cursuri/" nature_url="https://claudiamaria.ro/shop/"]`

To use images from the WordPress Media Library, copy each image URL and provide it through `hero_image_url`, `about_image_url`, `manual_image_url`, `reflexology_image_url`, and `courses_image_url`. The packaged reference images are used when an image URL is omitted.

Optional destination URLs can be supplied as shortcode attributes:

`[cm_pricing booking_action="whatsapp" whatsapp_number="40712345678" use_module_styling="true"]`

Set `booking_action="whatsapp"` to open WhatsApp, or `booking_action="popup"` to use the Elementor `popup_id`. The default is `popup`. For WhatsApp, use the international number without `+`, spaces, or punctuation. If the WhatsApp number is missing, the buttons safely fall back to the configured popup or booking URL.

Each CTA has its own default message. Override them with `whatsapp_initial_message`, `whatsapp_online_message`, `whatsapp_loyalty_message`, and `whatsapp_generic_message`. The nature button defaults to `https://claudiamaria.ro/shop/` and can be changed with `nature_url`.

Set `use_module_styling="true"` to use the module's built-in colours and fonts instead of Elementor global styling. It defaults to `false`.

== Changelog ==

= __PLUGIN_VERSION__ =
* Added the fully editable, server-rendered **Claudia Maria Homepage** Elementor component.
* Added separate responsive homepage and header components, including a mobile navigation menu.
* Added an optional `use_module_styling` shortcode flag to override Elementor global colours and fonts.
* Added complete plugin information for the WordPress details modal.
* Added direct Elementor Pro popup support through the `popup_id` attribute.
* Added automatic updates from the GitHub release repository.
* Added the complete responsive pricing page.
* Added Elementor global color and typography integration with local fallbacks.
* Added configurable booking, contact, and natural-care links.
