<?php
/**
 * Cookie Consent Banner
 * Affiche un pop-up pour demander le consentement aux cookies
 *
 * @package Bunbukan
 */

if (!isset($_COOKIE['bunbukan_cookie_consent'])) :
    ?>
    <div id="cookie-consent-banner" class="cookie-consent-banner" role="dialog" aria-labelledby="cookie-consent-title" aria-live="polite">
        <div class="cookie-consent-content">
            <h3 id="cookie-consent-title" class="cookie-consent-title">
                <?php echo esc_html__('Cookie Consent', 'bunbukan'); ?>
            </h3>
            <p class="cookie-consent-text">
                <?php echo esc_html__('We use cookies to enhance your browsing experience and remember your language preference. By clicking "Accept", you consent to our use of cookies.', 'bunbukan'); ?>
            </p>
            <div class="cookie-consent-actions">
                <button id="cookie-accept" class="cookie-btn cookie-btn--accept" aria-label="<?php echo esc_attr__('Accept cookies', 'bunbukan'); ?>">
                    <?php echo esc_html__('Accept', 'bunbukan'); ?>
                </button>
                <button id="cookie-decline" class="cookie-btn cookie-btn--decline" aria-label="<?php echo esc_attr__('Decline cookies', 'bunbukan'); ?>">
                    <?php echo esc_html__('Decline', 'bunbukan'); ?>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>
