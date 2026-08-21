Audio Icon Grid
Configurable audio‑driven icon grids with optional sub‑grids, transitions, and a clean WordPress admin interface.
This plugin lets you build interactive audio grids using icons, text labels, and optional sub‑grids. Each cell can either play an audio file or link to another grid. Ideal for accessibility tools, soundboards, learning aids, or interactive media layouts.
---
Features
• Main grid with configurable number of columns
• Multiple sub‑grids, each independently editable
• Each cell supports:
	◦ Icon (image)
	◦ Text label
	◦ Audio file
	◦ Action: play audio or switch to a sub‑grid
• Smooth transitions (fade, swap, instant)
• Customisable global appearance:
	◦ Font family, size, weight
	◦ Text colour
	◦ Cell border colour
• Return‑to‑main icon for sub‑grids
• Fully sanitised and escaped admin interface
• Mobile‑responsive grid layout
• Zero external dependencies (no CDNs)
---
Installation
1. Upload the plugin folder to /wp-content/plugins/
2. Activate the plugin in Plugins → Installed Plugins
3. Go to Audio Icon Grid in the WordPress admin menu
4. Configure global settings, main grid, and sub‑grids
5. Add the shortcode to any page or post: [audio_icon_grid]

Usage
Place the shortcode anywhere in your content.
The plugin automatically loads the grid, applies your settings, and handles transitions and audio playback.
---
Known Bugs
• A new blank cell is added to each grid when opening the admin panel.
This does not affect frontend behaviour and is harmless, but it is a known quirk of the current implementation.
---
Support & Updates
This plugin is provided as‑is.
• No support is offered.
• No future updates are planned.
• The plugin remains fully functional and secure in its current state.
---
Optional Icon Pack
An optional AI‑generated icon pack is available for download.
These icons match the plugin’s visual style and can be used for grid cells if desired.
---
Security
This plugin has been fully hardened to WordPress security standards:
Input Sanitization
All user‑provided settings are sanitized using:
• absint() for numeric IDs
• bounded integers for column counts
• allowlists for enumerated values (font weight, transitions, actions)
• sanitize_text_field() for text inputs
Output Escaping
All dynamic output in the admin interface uses:
• esc_attr() for form fields
• esc_url() for image/audio URLs
• esc_html() for filenames and text labels
JSON Hardening
Inline JavaScript data is encoded using: JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS
This prevents:
• </script> injection
• HTML entity confusion
• attribute‑breaking characters
• malformed JSON in inline scripts
Frontend Safety
The JavaScript renderer:
• uses createElement and innerText (no HTML injection)
• never inserts user‑controlled HTML
• never evaluates dynamic scripts
• safely handles audio playback errors
General Hardening
• Direct file access blocked via ABSPATH checks
• No external CDNs
• No unsafe dynamic attributes
• No unbounded loops or resource‑heavy operations
---
Requirements
• WordPress 6.0+
• PHP 7.4+
• Modern browser for frontend grid rendering
---
License
GPLv3
Copyright © 2026 Daniel Baker