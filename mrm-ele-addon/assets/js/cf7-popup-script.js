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
            
            // Add flags to prevent duplicate submissions
            this.isSubmitting = false;
            this.isFileUploading = false;
            this.googleSheetsSent = false;
            this.uploadedFiles = {};
            
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
            
            // Reset flags when opening popup
            this.resetSubmissionFlags();
            
            // Track popup display
            this.trackPopupDisplay();
            
            // Trigger custom event
            $(document).trigger('mrm_cf7_popup_opened', [this.widgetId]);
        }

        closePopup() {
            this.$modal.removeClass('active');
            $('body').removeClass('mrm-popup-open');
            
            // Reset all flags when closing
            this.resetSubmissionFlags();
            
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

            // Handle form submission - intercept to upload files first
            $cf7Form.on('submit', (e) => {
                // Prevent duplicate submissions
                if (this.isSubmitting) {
                    console.warn('⚠️ Form is already submitting, preventing duplicate submission');
                    e.preventDefault();
                    return false;
                }

                const hasFiles = this.hasFileUploads($cf7Form);
                
                if (hasFiles && !this.isFileUploading) {
                    console.log('📁 Files detected, uploading first...');
                    e.preventDefault();
                    this.isSubmitting = true;
                    this.isFileUploading = true;
                    this.$form.addClass('submitting');
                    this.uploadFilesBeforeSubmit($cf7Form);
                } else if (!hasFiles) {
                    // No files, allow normal submission
                    this.isSubmitting = true;
                    this.$form.addClass('submitting');
                }
            });

            // Listen for CF7 events - use one-time handler to prevent multiple calls
            const handleMailSent = (event) => {
                if (!$.contains(this.$modal[0], event.target)) {
                    return;
                }

                console.log('✅ CF7 mail sent event received');
                
                // Prevent duplicate handling
                if (this.googleSheetsSent) {
                    console.warn('⚠️ Google Sheets data already sent, skipping duplicate');
                    return;
                }

                this.$form.removeClass('submitting');
                this.handleFormSuccess(event);
            };

            const handleMailFailed = (event) => {
                if ($.contains(this.$modal[0], event.target)) {
                    console.error('❌ CF7 mail failed');
                    this.$form.removeClass('submitting');
                    this.resetSubmissionFlags();
                }
            };

            const handleInvalid = (event) => {
                if ($.contains(this.$modal[0], event.target)) {
                    console.warn('⚠️ CF7 form validation failed');
                    this.$form.removeClass('submitting');
                    this.resetSubmissionFlags();
                }
            };

            const handleSpam = (event) => {
                if ($.contains(this.$modal[0], event.target)) {
                    console.warn('⚠️ CF7 spam detected');
                    this.$form.removeClass('submitting');
                    this.resetSubmissionFlags();
                }
            };

            // Remove any existing listeners first
            document.removeEventListener('wpcf7mailsent', handleMailSent);
            document.removeEventListener('wpcf7mailfailed', handleMailFailed);
            document.removeEventListener('wpcf7invalid', handleInvalid);
            document.removeEventListener('wpcf7spam', handleSpam);

            // Add new listeners
            document.addEventListener('wpcf7mailsent', handleMailSent, false);
            document.addEventListener('wpcf7mailfailed', handleMailFailed, false);
            document.addEventListener('wpcf7invalid', handleInvalid, false);
            document.addEventListener('wpcf7spam', handleSpam, false);
        }

        resetSubmissionFlags() {
            this.isSubmitting = false;
            this.isFileUploading = false;
            this.googleSheetsSent = false;
            this._googleSheetsSending = false;
            this.uploadedFiles = {};
            console.log('🔄 All submission flags reset');
        }

        hasFileUploads($form) {
            return $form.find('input[type="file"]').length > 0 && 
                   $form.find('input[type="file"]').filter(function() {
                       return this.files && this.files.length > 0;
                   }).length > 0;
        }

        uploadFilesBeforeSubmit($form) {
            const $fileInputs = $form.find('input[type="file"]');
            const uploadPromises = [];

            console.log('📤 Starting file uploads...');
            console.log('🔄 Clearing previous uploaded files...');
            this.uploadedFiles = {}; // Clear previous uploads

            $fileInputs.each((index, input) => {
                const $input = $(input);
                const files = input.files;

                if (files && files.length > 0) {
                    const fieldName = $input.attr('name');
                    
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        console.log('📁 Uploading file:', file.name, '(' + (file.size / 1024).toFixed(2) + ' KB) for field:', fieldName);
                        
                        const promise = this.uploadSingleFile(file, fieldName);
                        uploadPromises.push(promise);
                    }
                }
            });

            if (uploadPromises.length === 0) {
                console.warn('⚠️ No files to upload');
                this.isFileUploading = false;
                this.submitFormAfterUpload($form);
                return;
            }

            console.log('⏳ Waiting for ' + uploadPromises.length + ' file(s) to upload...');

            // Wait for ALL uploads to complete
            Promise.all(uploadPromises)
                .then((results) => {
                    console.log('✅ All files uploaded successfully:', results);
                    
                    // Store uploaded file URLs
                    results.forEach(result => {
                        if (result.success && result.data) {
                            // Normalize field name - remove brackets [] if present
                            // CF7 file fields use name like "file-225[]" but form data uses "file-225"
                            const normalizedFieldName = result.data.field_name.replace(/\[\]$/, '');
                            this.uploadedFiles[normalizedFieldName] = result.data.url;
                            console.log('💾 Stored file URL for field:', normalizedFieldName, '→', result.data.url);
                        }
                    });

                    console.log('📦 Final uploaded files object:', this.uploadedFiles);
                    this.isFileUploading = false;

                    // Now submit the form normally (CF7 will handle it)
                    this.submitFormAfterUpload($form);
                })
                .catch((error) => {
                    console.error('❌ File upload failed:', error);
                    this.$form.removeClass('submitting');
                    this.resetSubmissionFlags();
                    
                    // Show error message
                    const $response = $form.find('.wpcf7-response-output');
                    $response.addClass('wpcf7-validation-errors').text('File upload failed. Please try again.');
                });
        }

        uploadSingleFile(file, fieldName) {
            return new Promise((resolve, reject) => {
                // Validate file size (max 5MB as per user requirement)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    const errorMsg = 'File ' + file.name + ' exceeds 5MB limit (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    console.error('❌', errorMsg);
                    reject(errorMsg);
                    return;
                }

                const formData = new FormData();
                formData.append('action', 'mrm_cf7_popup_upload_file');
                formData.append('nonce', mrmCF7PopupData.nonce);
                formData.append('file', file);
                formData.append('field_name', fieldName);

                console.log('⏳ Uploading:', file.name, '(' + (file.size / 1024).toFixed(2) + ' KB)');

                $.ajax({
                    url: mrmCF7PopupData.ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    timeout: 60000, // 60 second timeout for large files
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        // Upload progress
                        xhr.upload.addEventListener('progress', function(evt) {
                            if (evt.lengthComputable) {
                                const percentComplete = (evt.loaded / evt.total) * 100;
                                console.log('📊 Upload progress:', file.name, percentComplete.toFixed(1) + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: (response) => {
                        if (response.success) {
                            console.log('✅ File uploaded successfully!');
                            console.log('   File:', response.data.file_name);
                            console.log('   URL:', response.data.url);
                            console.log('   Field:', response.data.field_name);
                            resolve(response);
                        } else {
                            console.error('❌ Upload failed:', response.data ? response.data.message : 'Unknown error');
                            reject(response.data ? response.data.message : 'Upload failed');
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error('❌ Upload AJAX error for', file.name);
                        console.error('   Status:', status);
                        console.error('   Error:', error);
                        reject(error || 'Network error during upload');
                    }
                });
            });
        }

        submitFormAfterUpload($form) {
            console.log('📨 Submitting form after file uploads complete...');
            console.log('📦 Files ready to be included:', this.uploadedFiles);
            
            // Set flag to indicate we're submitting after upload
            // This prevents the submit handler from intercepting again
            this.isFileUploading = false;
            
            // Trigger CF7 submission programmatically
            const submitButton = $form.find('input[type="submit"]')[0];
            if (submitButton) {
                // Temporarily disable our submit handler
                $form.off('submit.mrmFileUpload');
                
                // Click submit button
                submitButton.click();
                
                // Re-attach after a delay
                setTimeout(() => {
                    $form.on('submit.mrmFileUpload', (e) => {
                        if (this.isSubmitting) {
                            e.preventDefault();
                            return false;
                        }
                    });
                }, 500);
            } else {
                console.error('❌ Submit button not found');
                this.resetSubmissionFlags();
                this.$form.removeClass('submitting');
            }
        }

        handleFormSuccess(event) {
            console.log('🎉 Form submission successful!');
            
            // Prevent duplicate submissions - this is the KEY fix
            if (this.googleSheetsSent) {
                console.warn('⚠️ Already processed this submission, skipping duplicate');
                return;
            }
            
            // Mark as sent IMMEDIATELY to prevent any race conditions
            this.googleSheetsSent = true;
            
            // Send to Google Sheets if enabled - ONLY ONCE
            if (this.googleSheetsData.enabled) {
                console.log('📊 Sending to Google Sheets (ONE TIME ONLY)...');
                this.sendToGoogleSheets(event.detail.inputs);
            } else {
                console.log('ℹ️ Google Sheets integration is disabled');
            }

            // Send CC email if enabled
            if (this.ccEmail) {
                console.log('📧 Sending CC email...');
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
                // Reset flags for next submission
                this.resetSubmissionFlags();
            }, 2000);

            // Trigger custom event
            $(document).trigger('mrm_cf7_popup_submitted', [this.widgetId, event.detail.inputs]);
        }

        sendToGoogleSheets(formData) {
            // Additional check to prevent duplicate calls
            if (this._googleSheetsSending) {
                console.error('❌ DUPLICATE CALL DETECTED - Google Sheets AJAX already in progress!');
                return;
            }
            
            this._googleSheetsSending = true;
            
            // Debug logging
            console.log('========================================');
            console.log('📊 SENDING TO GOOGLE SHEETS (ONE TIME)');
            console.log('========================================');
            console.log('📊 Google Sheets Config:', this.googleSheetsData);
            console.log('📁 Uploaded Files Ready:', this.uploadedFiles);
            console.log('📋 Form Data from CF7:', formData);
            
            // Prepare data for Google Sheets
            const fieldMapping = JSON.parse(this.googleSheetsData.fieldMapping || '{}');
            const mappedData = {};

            console.log('🗺️ Field Mapping:', fieldMapping);

            // Map form fields to sheet columns
            for (const [formField, sheetColumn] of Object.entries(fieldMapping)) {
                let value = formData.find(item => item.name === formField);
                
                // Check if this field has an uploaded file - PRIORITIZE uploaded file URL
                // Try both with and without brackets (e.g., "file-225" and "file-225[]")
                const normalizedFormField = formField.replace(/\[\]$/, '');
                const formFieldWithBrackets = normalizedFormField + '[]';
                
                if (this.uploadedFiles && (this.uploadedFiles[normalizedFormField] || this.uploadedFiles[formFieldWithBrackets])) {
                    const fileUrl = this.uploadedFiles[normalizedFormField] || this.uploadedFiles[formFieldWithBrackets];
                    mappedData[sheetColumn] = fileUrl;
                    console.log('✅ 📎 Using UPLOADED file URL for', formField, '→', sheetColumn, ':', fileUrl);
                } else if (value) {
                    // IMPORTANT: Only use scalar values, not File/Blob objects
                    // If value.value is a File object or Blob, skip it
                    if (value.value instanceof File || value.value instanceof Blob || 
                        (typeof value.value === 'object' && value.value !== null && value.value.constructor.name === 'File')) {
                        console.warn('⚠️ Skipping File/Blob object for field:', formField);
                        mappedData[sheetColumn] = ''; // Use empty string for file fields without URLs
                    } else {
                        // Use regular form field value (ensure it's a string)
                        mappedData[sheetColumn] = String(value.value || '');
                        console.log('✅ 📝 Using text value for', formField, '→', sheetColumn, ':', value.value);
                    }
                } else {
                    console.warn('⚠️ Field', formField, 'not found in form data or uploaded files');
                    mappedData[sheetColumn] = '';
                }
            }

            // Add timestamp
            mappedData['Timestamp'] = new Date().toISOString();
            
            console.log('📦 Final Mapped Data for Google Sheets:', mappedData);

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
                    
                    // Debug logging
                    console.log('📤 Sending uploaded file data:', {
                        fileId: ajaxData.file_id,
                        widgetId: ajaxData.widget_id
                    });
                } else {
                    ajaxData.service_account_path = this.googleSheetsData.serviceAccountPath || '';
                }
            } else if (authMethod === 'webhook') {
                ajaxData.webhook_url = this.googleSheetsData.webhookUrl || '';
            }

            // Send to server for Google Sheets integration
            // Convert data to plain object to avoid jQuery serialization issues
            const plainAjaxData = {
                action: String(ajaxData.action),
                nonce: String(ajaxData.nonce),
                auth_method: String(ajaxData.auth_method),
                sheet_id: String(ajaxData.sheet_id),
                sheet_name: String(ajaxData.sheet_name),
                data: JSON.stringify(ajaxData.data), // Serialize data as JSON string
                widget_id: String(ajaxData.widget_id)
            };

            // Add method-specific data as plain strings
            if (ajaxData.api_key) plainAjaxData.api_key = String(ajaxData.api_key);
            if (ajaxData.service_account_json) plainAjaxData.service_account_json = String(ajaxData.service_account_json);
            if (ajaxData.service_account_path) plainAjaxData.service_account_path = String(ajaxData.service_account_path);
            if (ajaxData.file_id) plainAjaxData.file_id = String(ajaxData.file_id);
            if (ajaxData.webhook_url) plainAjaxData.webhook_url = String(ajaxData.webhook_url);

            console.log('📤 Sending AJAX request to Google Sheets...');
            console.log('📤 Request Data:', plainAjaxData);

            // Send AJAX - with timeout to prevent hanging
            $.ajax({
                url: mrmCF7PopupData.ajaxUrl,
                type: 'POST',
                data: plainAjaxData,
                dataType: 'json',
                timeout: 30000, // 30 second timeout
                success: (response) => {
                    this._googleSheetsSending = false; // Reset flag
                    
                    if (response.success) {
                        console.log('========================================');
                        console.log('✅ ✅ ✅ SUCCESS! Data sent to Google Sheets!');
                        console.log('========================================');
                        console.log('Response:', response.data);
                    } else {
                        console.error('========================================');
                        console.error('❌ Failed to send data to Google Sheets');
                        console.error('========================================');
                        console.error('Error:', response.data);
                        if (response.data && response.data.message) {
                            console.error('Error message:', response.data.message);
                        }
                        if (response.data && response.data.details) {
                            console.error('Error details:', response.data.details);
                        }
                    }
                },
                error: (xhr, status, error) => {
                    this._googleSheetsSending = false; // Reset flag
                    
                    console.error('========================================');
                    console.error('❌ Google Sheets AJAX Error');
                    console.error('========================================');
                    console.error('Error:', error);
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
