/**
 * Système d'aide contextuelle
 */
document.addEventListener('DOMContentLoaded', function() {
    // Créer le conteneur modal pour l'aide
    const helpModal = document.createElement('div');
    helpModal.id = 'help-modal';
    helpModal.className = 'help-modal';
    helpModal.style.display = 'none';
    
    // Contenu du modal
    helpModal.innerHTML = `
        <div class="help-modal-content">
            <div class="help-modal-header">
                <h2 data-i18n="help_title">Aide</h2>
                <span class="help-modal-close">&times;</span>
            </div>
            <div class="help-modal-body">
                <div id="help-content"></div>
            </div>
            <div class="help-modal-footer">
                <button id="help-close-btn" class="btn btn-primary" data-i18n="close">Fermer</button>
            </div>
        </div>
    `;
    
    // Ajouter le modal au body
    document.body.appendChild(helpModal);
    
    // Récupérer les éléments du modal
    const closeSpan = document.querySelector('.help-modal-close');
    const closeBtn = document.getElementById('help-close-btn');
    const helpContent = document.getElementById('help-content');
    
    // Fermer le modal lorsqu'on clique sur le X
    if (closeSpan) {
        closeSpan.addEventListener('click', function() {
            helpModal.style.display = 'none';
        });
    }
    
    // Fermer le modal lorsqu'on clique sur le bouton Fermer
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            helpModal.style.display = 'none';
        });
    }
    
    // Fermer le modal lorsqu'on clique en dehors du contenu
    window.addEventListener('click', function(event) {
        if (event.target === helpModal) {
            helpModal.style.display = 'none';
        }
    });
    
    // Ajouter des boutons d'aide à tous les éléments avec l'attribut data-help
    document.querySelectorAll('[data-help]').forEach(element => {
        // Créer le bouton d'aide
        const helpBtn = document.createElement('button');
        helpBtn.className = 'help-btn';
        helpBtn.innerHTML = '<i class="fas fa-question-circle"></i>';
        helpBtn.title = 'Aide';
        
        // Positionner le bouton d'aide
        element.style.position = 'relative';
        
        // Ajouter le bouton à l'élément
        element.appendChild(helpBtn);
        
        // Ajouter un écouteur d'événements pour afficher l'aide
        helpBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            // Récupérer la clé d'aide
            const helpKey = element.getAttribute('data-help');
            
            // Afficher l'aide
            showHelp(helpKey);
        });
    });
    
    // Ajouter un bouton d'aide global dans le header
    const headerUserInfo = document.querySelector('.user-info');
    if (headerUserInfo) {
        const helpBtnContainer = document.createElement('div');
        helpBtnContainer.className = 'help-btn-container';
        
        const globalHelpBtn = document.createElement('a');
        globalHelpBtn.href = '#';
        globalHelpBtn.className = 'help-link';
        globalHelpBtn.innerHTML = '<i class="fas fa-question-circle"></i>';
        globalHelpBtn.title = 'Aide';
        
        helpBtnContainer.appendChild(globalHelpBtn);
        headerUserInfo.insertBefore(helpBtnContainer, headerUserInfo.firstChild);
        
        // Ajouter un écouteur d'événements pour afficher l'aide globale
        globalHelpBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Déterminer la page actuelle
            let helpKey = 'help_dashboard';
            const path = window.location.pathname;
            
            if (path.includes('/history')) {
                helpKey = 'help_history';
            } else if (path.includes('/admin')) {
                helpKey = 'help_admin';
            }
            
            // Afficher l'aide
            showHelp(helpKey);
        });
    }
    
    // Fonction pour afficher l'aide
    function showHelp(helpKey) {
        // Récupérer le contenu de l'aide
        const helpText = getHelpContent(helpKey);
        
        // Mettre à jour le contenu du modal
        if (helpContent) {
            helpContent.innerHTML = helpText;
        }
        
        // Afficher le modal
        helpModal.style.display = 'block';
    }
    
    // Fonction pour récupérer le contenu de l'aide
    function getHelpContent(helpKey) {
        // Récupérer les traductions
        const language = localStorage.getItem('language') || 'fr';
        
        // Essayer de récupérer la traduction depuis le DOM
        const element = document.querySelector(`[data-i18n="${helpKey}"]`);
        if (element) {
            return element.innerHTML;
        }
        
        // Contenu d'aide par défaut
        const defaultHelp = {
            'help_dashboard': 'Cette page affiche l\'état actuel du manège et vous permet de contrôler ses composants.',
            'help_history': 'Cette page vous permet de consulter l\'historique des données du manège et d\'appliquer des filtres.',
            'help_admin': 'Cette page vous permet de gérer les utilisateurs du système.',
            'button_help': 'Ce bouton indique si le manège est prêt à fonctionner.',
            'motor_help': 'Ce contrôle permet d\'activer ou de désactiver le moteur du manège.',
            'led_help': 'Ce contrôle permet d\'allumer ou d\'éteindre la LED du manège.',
            'potentiometer_help': 'Ce contrôle permet d\'ajuster la valeur du potentiomètre du manège.'
        };
        
        return defaultHelp[helpKey] || 'Aucune aide disponible pour cet élément.';
    }
}); 