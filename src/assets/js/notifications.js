/**
 * Système de notifications par email/SMS
 */
document.addEventListener('DOMContentLoaded', function() {
    // Créer le conteneur modal pour les paramètres de notification
    const notifModal = document.createElement('div');
    notifModal.id = 'notification-modal';
    notifModal.className = 'notification-modal';
    notifModal.style.display = 'none';
    
    // Contenu du modal
    notifModal.innerHTML = `
        <div class="notification-modal-content">
            <div class="notification-modal-header">
                <h2 data-i18n="notification_title">Notifications</h2>
                <span class="notification-modal-close">&times;</span>
            </div>
            <div class="notification-modal-body">
                <h3 data-i18n="notification_settings">Paramètres de notification</h3>
                <form id="notification-form">
                    <div class="notification-section">
                        <div class="notification-option">
                            <input type="checkbox" id="email-notifications" name="email-notifications">
                            <label for="email-notifications" data-i18n="email_notifications">Notifications par email</label>
                        </div>
                        <div class="notification-field" id="email-field">
                            <label for="email-address" data-i18n="email_address">Adresse email</label>
                            <input type="email" id="email-address" name="email-address" placeholder="exemple@email.com">
                        </div>
                    </div>
                    
                    <div class="notification-section">
                        <div class="notification-option">
                            <input type="checkbox" id="sms-notifications" name="sms-notifications">
                            <label for="sms-notifications" data-i18n="sms_notifications">Notifications par SMS</label>
                        </div>
                        <div class="notification-field" id="sms-field">
                            <label for="phone-number" data-i18n="phone_number">Numéro de téléphone</label>
                            <input type="tel" id="phone-number" name="phone-number" placeholder="+33 6 12 34 56 78">
                        </div>
                    </div>
                    
                    <div class="notification-types">
                        <h4>Types de notifications</h4>
                        <div class="notification-type">
                            <input type="checkbox" id="notif-emergency" name="notif-emergency" checked>
                            <label for="notif-emergency">Urgences</label>
                        </div>
                        <div class="notification-type">
                            <input type="checkbox" id="notif-maintenance" name="notif-maintenance" checked>
                            <label for="notif-maintenance">Maintenance</label>
                        </div>
                        <div class="notification-type">
                            <input type="checkbox" id="notif-status" name="notif-status">
                            <label for="notif-status">Changements d'état</label>
                        </div>
                        <div class="notification-type">
                            <input type="checkbox" id="notif-weather" name="notif-weather">
                            <label for="notif-weather">Alertes météo</label>
                        </div>
                    </div>
                </form>
                
                <div id="notification-status" class="notification-status"></div>
            </div>
            <div class="notification-modal-footer">
                <button id="test-notification-btn" class="btn btn-secondary" data-i18n="test_notification">Tester les notifications</button>
                <button id="save-notification-btn" class="btn btn-primary" data-i18n="save_settings">Enregistrer les paramètres</button>
            </div>
        </div>
    `;
    
    // Ajouter le modal au body
    document.body.appendChild(notifModal);
    
    // Récupérer les éléments du modal
    const closeSpan = notifModal.querySelector('.notification-modal-close');
    const saveBtn = document.getElementById('save-notification-btn');
    const testBtn = document.getElementById('test-notification-btn');
    const emailCheckbox = document.getElementById('email-notifications');
    const smsCheckbox = document.getElementById('sms-notifications');
    const emailField = document.getElementById('email-field');
    const smsField = document.getElementById('sms-field');
    const notificationStatus = document.getElementById('notification-status');
    
    // Fermer le modal lorsqu'on clique sur le X
    if (closeSpan) {
        closeSpan.addEventListener('click', function() {
            notifModal.style.display = 'none';
        });
    }
    
    // Fermer le modal lorsqu'on clique en dehors du contenu
    window.addEventListener('click', function(event) {
        if (event.target === notifModal) {
            notifModal.style.display = 'none';
        }
    });
    
    // Gérer l'affichage des champs en fonction des cases à cocher
    if (emailCheckbox) {
        emailCheckbox.addEventListener('change', function() {
            emailField.style.display = this.checked ? 'block' : 'none';
        });
        
        // Initialiser l'état
        emailField.style.display = emailCheckbox.checked ? 'block' : 'none';
    }
    
    if (smsCheckbox) {
        smsCheckbox.addEventListener('change', function() {
            smsField.style.display = this.checked ? 'block' : 'none';
        });
        
        // Initialiser l'état
        smsField.style.display = smsCheckbox.checked ? 'block' : 'none';
    }
    
    // Gérer le clic sur le bouton d'enregistrement
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            // Récupérer les valeurs du formulaire
            const settings = {
                emailEnabled: emailCheckbox.checked,
                smsEnabled: smsCheckbox.checked,
                emailAddress: document.getElementById('email-address').value,
                phoneNumber: document.getElementById('phone-number').value,
                notifyEmergency: document.getElementById('notif-emergency').checked,
                notifyMaintenance: document.getElementById('notif-maintenance').checked,
                notifyStatus: document.getElementById('notif-status').checked,
                notifyWeather: document.getElementById('notif-weather').checked
            };
            
            // Enregistrer les paramètres dans le localStorage
            localStorage.setItem('notificationSettings', JSON.stringify(settings));
            
            // Afficher un message de confirmation
            showNotificationStatus('success', 'Paramètres enregistrés avec succès');
        });
    }
    
    // Gérer le clic sur le bouton de test
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            // Simuler l'envoi d'une notification de test
            const settings = JSON.parse(localStorage.getItem('notificationSettings') || '{}');
            
            if (settings.emailEnabled && settings.emailAddress) {
                // Simuler l'envoi d'un email
                setTimeout(function() {
                    showNotificationStatus('success', 'Notification de test envoyée à ' + settings.emailAddress);
                }, 1000);
            } else if (settings.smsEnabled && settings.phoneNumber) {
                // Simuler l'envoi d'un SMS
                setTimeout(function() {
                    showNotificationStatus('success', 'SMS de test envoyé à ' + settings.phoneNumber);
                }, 1000);
            } else {
                showNotificationStatus('error', 'Veuillez configurer au moins une méthode de notification');
            }
        });
    }
    
    // Ajouter un bouton de notifications dans le header
    const headerUserInfo = document.querySelector('.user-info');
    if (headerUserInfo) {
        const notifBtnContainer = document.createElement('div');
        notifBtnContainer.className = 'notification-btn-container';
        
        const notifBtn = document.createElement('a');
        notifBtn.href = '#';
        notifBtn.className = 'notification-link';
        notifBtn.innerHTML = '<i class="fas fa-bell"></i>';
        notifBtn.title = 'Notifications';
        
        notifBtnContainer.appendChild(notifBtn);
        headerUserInfo.insertBefore(notifBtnContainer, headerUserInfo.firstChild);
        
        // Ajouter un écouteur d'événements pour afficher les paramètres de notification
        notifBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Charger les paramètres enregistrés
            loadNotificationSettings();
            
            // Afficher le modal
            notifModal.style.display = 'block';
        });
    }
    
    // Fonction pour charger les paramètres de notification
    function loadNotificationSettings() {
        const settings = JSON.parse(localStorage.getItem('notificationSettings') || '{}');
        
        // Appliquer les paramètres au formulaire
        if (emailCheckbox) {
            emailCheckbox.checked = settings.emailEnabled || false;
            emailField.style.display = emailCheckbox.checked ? 'block' : 'none';
        }
        
        if (smsCheckbox) {
            smsCheckbox.checked = settings.smsEnabled || false;
            smsField.style.display = smsCheckbox.checked ? 'block' : 'none';
        }
        
        if (settings.emailAddress) {
            document.getElementById('email-address').value = settings.emailAddress;
        }
        
        if (settings.phoneNumber) {
            document.getElementById('phone-number').value = settings.phoneNumber;
        }
        
        if (settings.notifyEmergency !== undefined) {
            document.getElementById('notif-emergency').checked = settings.notifyEmergency;
        }
        
        if (settings.notifyMaintenance !== undefined) {
            document.getElementById('notif-maintenance').checked = settings.notifyMaintenance;
        }
        
        if (settings.notifyStatus !== undefined) {
            document.getElementById('notif-status').checked = settings.notifyStatus;
        }
        
        if (settings.notifyWeather !== undefined) {
            document.getElementById('notif-weather').checked = settings.notifyWeather;
        }
    }
    
    // Fonction pour afficher un message de statut
    function showNotificationStatus(type, message) {
        if (notificationStatus) {
            notificationStatus.className = 'notification-status ' + type;
            notificationStatus.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
            
            // Masquer le message après 3 secondes
            setTimeout(function() {
                notificationStatus.className = 'notification-status';
                notificationStatus.innerHTML = '';
            }, 3000);
        }
    }
    
    // Fonction pour envoyer une notification (simulation)
    window.sendNotification = function(title, message, type) {
        const settings = JSON.parse(localStorage.getItem('notificationSettings') || '{}');
        
        // Vérifier si les notifications sont activées pour ce type
        let shouldNotify = false;
        
        switch (type) {
            case 'emergency':
                shouldNotify = settings.notifyEmergency;
                break;
            case 'maintenance':
                shouldNotify = settings.notifyMaintenance;
                break;
            case 'status':
                shouldNotify = settings.notifyStatus;
                break;
            case 'weather':
                shouldNotify = settings.notifyWeather;
                break;
        }
        
        if (!shouldNotify) {
            return;
        }
        
        // Créer une notification dans l'interface
        const notifContainer = document.createElement('div');
        notifContainer.className = 'notification-popup ' + type;
        
        notifContainer.innerHTML = `
            <div class="notification-popup-header">
                <h3>${title}</h3>
                <span class="notification-popup-close">&times;</span>
            </div>
            <div class="notification-popup-body">
                <p>${message}</p>
            </div>
        `;
        
        // Ajouter la notification au body
        document.body.appendChild(notifContainer);
        
        // Afficher la notification
        setTimeout(function() {
            notifContainer.classList.add('show');
        }, 100);
        
        // Masquer la notification après 5 secondes
        setTimeout(function() {
            notifContainer.classList.remove('show');
            
            // Supprimer la notification après l'animation
            setTimeout(function() {
                notifContainer.remove();
            }, 300);
        }, 5000);
        
        // Fermer la notification lorsqu'on clique sur le X
        const closeBtn = notifContainer.querySelector('.notification-popup-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                notifContainer.classList.remove('show');
                
                // Supprimer la notification après l'animation
                setTimeout(function() {
                    notifContainer.remove();
                }, 300);
            });
        }
        
        // Simuler l'envoi par email ou SMS
        console.log(`Notification ${type} envoyée: ${title} - ${message}`);
    };
}); 