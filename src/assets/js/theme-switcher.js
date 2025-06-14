/**
 * Gestionnaire de thème clair/sombre
 */
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'utilisateur a déjà une préférence
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Appliquer le thème au chargement
    document.body.classList.add('theme-' + currentTheme);
    
    // Mettre à jour l'état du bouton de changement de thème
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.checked = (currentTheme === 'dark');
        
        // Ajouter un écouteur d'événements pour le changement de thème
        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                changeTheme('dark');
            } else {
                changeTheme('light');
            }
        });
    }
    
    // Fonction pour changer le thème
    window.changeTheme = function(theme) {
        document.body.classList.remove('theme-light', 'theme-dark');
        document.body.classList.add('theme-' + theme);
        localStorage.setItem('theme', theme);
        
        // Mettre à jour l'état du bouton si nécessaire
        if (themeToggle) {
            themeToggle.checked = (theme === 'dark');
        }
        
        // Déclencher un événement personnalisé pour que d'autres scripts puissent réagir
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: theme } }));
    };
}); 