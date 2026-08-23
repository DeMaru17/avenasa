/**
 * PT Abhipraya Nawasena Sejahtera (ANS)
 * Centralized Client-Side Analytics Engine (GA4 & dataLayer Telemetry)
 *
 * Conforms strictly to SPEC-09-ANALYTICS (docs/feature-specs/analytics.md).
 * Strict No-PII Policy: Never transmit names, emails, phones, messages, or sensitive identifiers.
 */

const ANSAnalytics = {
    /**
     * Safe core tracking dispatcher.
     * Pushes event to window.dataLayer for telemetry and invokes gtag('event') if available.
     *
     * @param {string} eventName
     * @param {Object} params
     */
    track(eventName, params = {}) {
        try {
            window.dataLayer = window.dataLayer || [];

            // 1. Telemetry / dataLayer push
            window.dataLayer.push({
                event: eventName,
                ...params,
            });

            // 2. Official Google tag event invocation (if gtag is active)
            if (typeof window.gtag === 'function') {
                window.gtag('event', eventName, params);
            }
        } catch (e) {
            // Analytics failures must never crash application workflows
            console.warn('[ANS Analytics] Non-blocking tracking error:', e);
        }
    },

    /**
     * 1. View Product Event
     * Triggered on initial product detail page load.
     *
     * @param {Object} ctx
     * @param {number} ctx.productId
     * @param {string} ctx.productName
     * @param {string} ctx.locale ('id' | 'en')
     * @param {string} [ctx.categoryName]
     * @param {string} [ctx.brandName]
     */
    trackViewProduct(ctx = {}) {
        if (!ctx.productId || !ctx.productName) return;

        const payload = {
            product_id: Number(ctx.productId),
            product_name: String(ctx.productName),
            locale: ctx.locale || 'id',
        };

        if (ctx.categoryName) payload.category_name = String(ctx.categoryName);
        if (ctx.brandName) payload.brand_name = String(ctx.brandName);

        this.track('view_product', payload);
    },

    /**
     * 2. Product Filter Event
     * Triggered 1x after meaningful GET navigation of catalog with active filter.
     *
     * @param {Object} ctx
     * @param {string} ctx.filterType ('category' | 'brand' | 'combined')
     * @param {string} ctx.locale ('id' | 'en')
     * @param {string} [ctx.categorySlug]
     * @param {string} [ctx.brandSlug]
     */
    trackProductFilter(ctx = {}) {
        if (!ctx.filterType) return;

        const payload = {
            filter_type: String(ctx.filterType),
            locale: ctx.locale || 'id',
        };

        if (ctx.categorySlug) payload.category_slug = String(ctx.categorySlug);
        if (ctx.brandSlug) payload.brand_slug = String(ctx.brandSlug);

        this.track('product_filter', payload);
    },

    /**
     * 3. Download Brochure Event
     * Triggered on clicking valid official PDF brochure download CTA.
     *
     * @param {Object} ctx
     * @param {number} ctx.productId
     * @param {string} ctx.productName
     * @param {string} ctx.locale ('id' | 'en')
     * @param {string} [ctx.fileFormat] (default 'pdf')
     */
    trackDownloadBrochure(ctx = {}) {
        if (!ctx.productId || !ctx.productName) return;

        const payload = {
            product_id: Number(ctx.productId),
            product_name: String(ctx.productName),
            locale: ctx.locale || 'id',
            file_format: ctx.fileFormat || 'pdf',
        };

        this.track('download_brochure', payload);
    },

    /**
     * 4. Click WhatsApp Event
     * Triggered on clicking official ANS WhatsApp CTA across all pages.
     * Strictly No-PII: No phone numbers or message texts.
     *
     * @param {Object} ctx
     * @param {string} ctx.sourcePage ('footer', 'contact_page', 'product_detail', 'home', 'partners_clients')
     * @param {string} ctx.locale ('id' | 'en')
     * @param {number} [ctx.productId]
     */
    trackWhatsApp(ctx = {}) {
        const payload = {
            source_page: ctx.sourcePage || 'unknown',
            locale: ctx.locale || 'id',
        };

        if (ctx.productId) {
            payload.product_id = Number(ctx.productId);
        }

        this.track('click_whatsapp', payload);
    },

    /**
     * 5. Start Quotation Event
     * Features cross-page journey deduplication via sessionStorage (max 1 event per journey).
     *
     * @param {Object} ctx
     * @param {string} ctx.source ('product_detail' | 'contact_page')
     * @param {string} ctx.locale ('id' | 'en')
     * @param {number} [ctx.productId]
     * @returns {boolean} true if event was sent, false if deduplicated
     */
    trackStartQuotation(ctx = {}) {
        try {
            // Check cross-page journey flag
            const hasStarted = sessionStorage.getItem('quotation_journey_started');
            if (hasStarted === 'true') {
                return false; // Deduplicated: already fired in this quotation journey
            }

            // Set journey flag
            sessionStorage.setItem('quotation_journey_started', 'true');

            const payload = {
                source: ctx.source || 'contact_page',
                locale: ctx.locale || 'id',
            };

            if (ctx.productId) {
                payload.product_id = Number(ctx.productId);
            }

            this.track('start_quotation', payload);
            return true;
        } catch (e) {
            return false;
        }
    },

    /**
     * 6. Submit Quotation (Primary Key Event / Conversion)
     * Resets quotation journey deduplication flag and tracks conversion.
     *
     * @param {Object} ctx
     * @param {boolean} ctx.hasCompany
     * @param {string} ctx.source ('general_inquiry' | 'product_specific')
     * @param {string} ctx.locale ('id' | 'en')
     * @param {number} [ctx.productId]
     */
    trackSubmitQuotation(ctx = {}) {
        try {
            // Clear quotation journey flag upon successful submission
            sessionStorage.removeItem('quotation_journey_started');
        } catch (e) {}

        const payload = {
            has_company: Boolean(ctx.hasCompany),
            source: ctx.source || 'general_inquiry',
            locale: ctx.locale || 'id',
        };

        if (ctx.productId) {
            payload.product_id = Number(ctx.productId);
        }

        this.track('submit_quotation', payload);
    },

    /**
     * 7. Language Switch Event
     * Triggered on clicking ID/EN switcher before navigation.
     *
     * @param {Object} ctx
     * @param {string} ctx.sourceLocale ('id' | 'en')
     * @param {string} ctx.targetLocale ('id' | 'en')
     * @param {string} ctx.currentPath (clean path without query string)
     */
    trackLanguageSwitch(ctx = {}) {
        const payload = {
            source_locale: ctx.sourceLocale || 'id',
            target_locale: ctx.targetLocale || 'en',
            current_path: ctx.currentPath || window.location.pathname,
        };

        this.track('language_switch', payload);
    },

    /**
     * 8. Hero CTA Click Event
     * Triggered on clicking Hero Banner action buttons on Home page.
     *
     * @param {Object} ctx
     * @param {number} ctx.bannerId
     * @param {string} ctx.locale ('id' | 'en')
     * @param {string} ctx.ctaType
     * @param {string} ctx.destinationType ('internal_catalog' | 'internal_page' | 'external')
     */
    trackHeroCta(ctx = {}) {
        const payload = {
            banner_id: Number(ctx.bannerId) || 0,
            locale: ctx.locale || 'id',
            cta_type: ctx.ctaType || 'primary_cta',
            destination_type: ctx.destinationType || 'internal_catalog',
        };

        this.track('hero_cta_click', payload);
    },
};

// Expose globally
window.ANSAnalytics = ANSAnalytics;

export default ANSAnalytics;
