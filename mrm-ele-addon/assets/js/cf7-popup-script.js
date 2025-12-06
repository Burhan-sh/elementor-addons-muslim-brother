/**
 * MRM CF7 Popup Widget JavaScript
 */
(function($) {
    'use strict';

    class MRM_CF7_Popup {
        constructor(element) {
            this.$wrapper = $(element);
            this.$modal = this.$wrapper.find('.mrm-cf7-popup-modal');
            this.$trigger = this.$wrapper.find('.mrm-cf7-popup-trigger');
            this.$close = this.$modal.find('.mrm-cf7-popup-close');
            this.$overlay = this.$modal.find('.mrm-cf7-popup-overlay');
            this.$form = this.$modal.find('.mrm-cf7-popup-form');
            
            this.config = this.$modal.data('popup-config') || {};
            this.googleSheetsData = this.$modal.data('google-sheets') || {};
            this.ccEmail = this.$modal.data('cc-email') || '';
            this.widgetId = this.config.widgetId || '';
            this.cookieName = 'mrm_cf7_popup_' + this.widgetId;
            
            this.init();
        }

        init() {
            this.bindEvents();
            this.handleTrigger();
            this.initCF7Integration();
        }

        bindEvents() {
            // Trigger button click
            this.$trigger.on('click', (e) => {
                e.preventDefault();
                this.openPopup();
            });

            // Close button click
            this.$close.on('click', (e) => {
                e.preventDefault();
                this.closePopup();
            });

            // Overlay click
            this.$overlay.on('click', () => {
                this.closePopup();
            });

            // ESC key press
            $(document).on('keyup', (e) => {
                if (e.key === 'Escape' && this.$modal.hasClass('active')) {
                    this.closePopup();
                }
            });

            // Exit intent
            if (this.config.trigger === 'exit') {
                this.initExitIntent();
            }
        }

        handleTrigger() {
            const frequency = this.config.frequency || 'always';
            
            // Check if popup should be shown based on frequency
            if (!this.shouldShowPopup(frequency)) {
                return;
            }

            // Handle different trigger types
            switch (this.config.trigger) {
                case 'auto':
                    this.autoTrigger();
                    break;
                case 'load':
                    this.loadTrigger();
                    break;
                case 'exit':
                    // Exit intent is handled in bindEvents
                    break;
                default:
                    // Button click (default)
                    break;
            }
        }

        shouldShowPopup(frequency) {
            const now = new Date().getTime();
            
            switch (frequency) {
                case 'once':
                    // Once per session
                    return !sessionStorage.getItem(this.cookieName);
                
                case 'once_user':
                    // Once per user (lifetime)
                    return !this.getCookie(this.cookieName);
                
                case 'interval':
                    // Show after interval
                    const lastShown = this.getCookie(this.cookieName + '_time');
                    if (!lastShown) return true;
                    
                    const interval = (this.config.interval || 5) * 60 * 1000; // Convert to milliseconds
                    return (now - parseInt(lastShown)) >= interval;
                
                default:
                    // Always show
                    return true;
            }
        }

        autoTrigger() {
            const delay = (this.config.delay || 5) * 1000; // Convert to milliseconds
            setTimeout(() => {
                this.openPopup();
            }, delay);
        }

        loadTrigger() {
            // Show popup immediately on page load
            setTimeout(() => {
                this.openPopup();
            }, 500);
        }

        initExitIntent() {
            let hasShown = false;
            
            $(document).on('mouseleave', (e) => {
                if (e.clientY < 0 && !hasShown && this.shouldShowPopup(this.config.frequency)) {
                    this.openPopup();
                    hasShown = true;
                }
            });
        }

        openPopup() {
            this.$modal.addClass('active');
            $('body').addClass('mrm-popup-open');
            
            // Track popup display
            this.trackPopupDisplay();
            
            // Trigger custom event
            $(document).trigger('mrm_cf7_popup_opened', [this.widgetId]);
        }

        closePopup() {
            this.$modal.removeClass('active');
            $('body').removeClass('mrm-popup-open');
            
            // Trigger custom event
            $(document).trigger('mrm_cf7_popup_closed', [this.widgetId]);
        }

        trackPopupDisplay() {
            const frequency = this.config.frequency || 'always';
            const now = new Date().getTime();
            
            switch (frequency) {
                case 'once':
                    sessionStorage.setItem(this.cookieName, 'shown');
                    break;
                
                case 'once_user':
                    this.setCookie(this.cookieName, 'shown', 365);
                    break;
                
                case 'interval':
                    this.setCookie(this.cookieName + '_time', now.toString(), 1);
                    break;
            }
        }

        initCF7Integration() {
            const $cf7Form = this.$modal.find('.wpcf7-form');
            
            if ($cf7Form.length === 0) {
                return;
            }

            // Handle form submission
            $cf7Form.on('submit', (e) => {
                this.$form.addClass('submitting');
            });

            // Listen for CF7 events
            document.addEventListener('wpcf7mailsent', (event) => {
                if ($.contains(this.$modal[0], event.target)) {
                    this.$form.removeClass('submitting');
                    this.handleFormSuccess(event);
                }
            }, false);

            document.addEventListener('wpcf7mailfailed', (event) => {
                if ($.contains(this.$modal[0], event.target)) {
                    this.$form.removeClass('submitting');
                }
            }, false);

            document.addEventListener('wpcf7invalid', (event) => {
                if ($.contains(this.$modal[0], event.target)) {
                    this.$form.removeClass('submitting');
                }
            }, false);

            document.addEventListener('wpcf7spam', (event) => {
                if ($.contains(this.$modal[0], event.target)) {
                    this.$form.removeClass('submitting');
                }
            }, false);
        }

        handleFormSuccess(event) {
            // Send to Google Sheets if enabled
            if (this.googleSheetsData.enabled) {
                this.sendToGoogleSheets(event.detail.inputs);
            }

            // Send CC email if enabled
            if (this.ccEmail) {
                this.sendCCEmail(event.detail.inputs);
            }

            // Close popup after successful submission (optional - after 2 seconds)
            setTimeout(() => {
                this.closePopup();
                // Reset form
                const $form = this.$modal.find('.wpcf7-form');
                if ($form.length) {
                    $form[0].reset();
                }
            }, 2000);

            // Trigger custom event
            $(document).trigger('mrm_cf7_popup_submitted', [this.widgetId, event.detail.inputs]);
        }

        sendToGoogleSheets(formData) {
            // Prepare data for Google Sheets
            const fieldMapping = JSON.parse(this.googleSheetsData.fieldMapping || '{}');
            const mappedData = {};

            // Map form fields to sheet columns
            for (const [formField, sheetColumn] of Object.entries(fieldMapping)) {
                const value = formData.find(item => item.name === formField);
                if (value) {
                    mappedData[sheetColumn] = value.value;
                }
            }

            // Add timestamp
            mappedData['Timestamp'] = new Date().toISOString();

            // Prepare AJAX data based on authentication method
            const authMethod = this.googleSheetsData.authMethod || 'service_account';
            const ajaxData = {
                action: 'mrm_cf7_popup_google_sheets',
                nonce: mrmCF7PopupData.nonce,
                auth_method: authMethod,
                sheet_id: this.googleSheetsData.sheetId,
                sheet_name: this.googleSheetsData.sheetName,
                data: mappedData,
                widget_id: this.widgetId
            };

            // Add method-specific data
            if (authMethod === 'api_key') {
                ajaxData.api_key = this.googleSheetsData.apiKey || '';
            } else if (authMethod === 'service_account') {
                if (this.googleSheetsData.serviceAccountMethod === 'json_content') {
                    ajaxData.service_account_json = this.googleSheetsData.serviceAccountJson || '';
                } else if (this.googleSheetsData.serviceAccountMethod === 'upload_file') {
                    // Uploaded file - pass file ID and widget ID
                    ajaxData.service_account_path = 'uploaded'; // Flag to use uploaded file
                    ajaxData.file_id = this.googleSheetsData.fileId || '';
                    ajaxData.widget_id = this.googleSheetsData.widgetId || '';
                } else {
                    ajaxData.service_account_path = this.googleSheetsData.serviceAccountPath || '';
                }
            } else if (authMethod === 'webhook') {
                ajaxData.webhook_url = this.googleSheetsData.webhookUrl || '';
            }

            // Send to server for Google Sheets integration
            $.ajax({
                url: mrmCF7PopupData.ajaxUrl,
                type: 'POST',
                data: ajaxData,
                success: (response) => {
                    if (response.success) {
                        console.log('✅ Data sent to Google Sheets successfully');
                        console.log('Response:', response.data);
                    } else {
                        console.error('❌ Failed to send data to Google Sheets:', response.data);
                        if (response.data && response.data.message) {
                            console.error('Error message:', response.data.message);
                        }
                        if (response.data && response.data.details) {
                            console.error('Error details:', response.data.details);
                        }
                    }
                },
                error: (xhr, status, error) => {
                    console.error('❌ Google Sheets AJAX error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                }
            });
        }

        sendCCEmail(formData) {
            // Send CC email through WordPress
            $.ajax({
                url: mrmCF7PopupData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'mrm_cf7_popup_send_cc',
                    nonce: mrmCF7PopupData.nonce,
                    cc_email: this.ccEmail,
                    form_data: formData,
                    widget_id: this.widgetId
                },
                success: (response) => {
                    if (response.success) {
                        console.log('CC email sent successfully');
                    } else {
                        console.error('Failed to send CC email:', response.data);
                    }
                },
                error: (xhr, status, error) => {
                    console.error('CC email AJAX error:', error);
                }
            });
        }

        // Cookie helpers
        setCookie(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
        }

        getCookie(name) {
            const nameEQ = name + '=';
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
    }

    // Initialize on document ready
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mrm-cf7-popup.default', function($scope) {
            new MRM_CF7_Popup($scope.find('.mrm-cf7-popup-wrapper')[0]);
        });
    });

    // Fallback for non-Elementor pages
    $(document).ready(function() {
        if (typeof elementorFrontend === 'undefined') {
            $('.mrm-cf7-popup-wrapper').each(function() {
                new MRM_CF7_Popup(this);
            });
        }
    });

})(jQuery);
