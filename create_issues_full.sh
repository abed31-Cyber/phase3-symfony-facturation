#!/bin/bash
set -euo pipefail

OWNER="abed31-Cyber"
REPO="phase3-symfony-facturation"
MILESTONE_MVP="MVP"

create_issue() {
    local title="$1"
    local body="$2"
    local labels="$3"
    local milestone="${4:-}"
    
    local cmd="gh issue create --repo \"$OWNER/$REPO\" --title \"$title\" --body \"$body\" --label \"$labels\""
    
    if [[ -n "$milestone" ]]; then
        cmd="$cmd --milestone \"$milestone\""
    fi
    
    echo "📝 Création : $title"
    eval "$cmd"
    sleep 0.5
}

echo "🚀 LANCEMENT DE LA CRÉATION DES 37 ISSUES DETAILLÉES"
echo "=================================================="

# EPIC 1
create_issue "[EPIC 1] Espace Utilisateur (MVP)" "## Description\nEn tant qu'**utilisateur**, je veux disposer d'un espace personnel sécurisé afin de gérer mon profil et accéder au tableau de bord.\n\n## Objectif\nAuthentification, gestion du profil et vue d'ensemble du dashboard.\n\n## User Stories liées\n- [EPIC 1.1] Création de compte\n- [EPIC 1.2] Profil personnel\n- [EPIC 1.3] Dashboard d'accueil\n\n## Tâches Techniques Suggérées\n- [ ] Initialiser le projet Symfony 7 avec Docker\n- [ ] Configurer le bundle Security avec Authenticator personnalisé\n- [ ] Créer l'entité User avec tous les champs métier" "epic,backend,security" "$MILESTONE_MVP"

create_issue "[EPIC 1.1] Création de compte" "## Description\nEn tant qu'**utilisateur non inscrit**, je veux créer un compte afin d'accéder à l'application de facturation.\n\n## Critères d'Acceptation\n- [ ] CA 1 : Fournir email, mot de passe, raison sociale, IBAN, nom et prénom.\n- [ ] CA 2 : Pouvoir se déconnecter (fermer la session).\n- [ ] CA 3 : Déconnexion automatique au bout d'une heure.\n\n## Tâches Techniques\n- [ ] Créer l'entité User\n- [ ] Créer le RegistrationFormType\n- [ ] Configurer le session_handler" "enhancement,backend,security,database" "$MILESTONE_MVP"

create_issue "[EPIC 1.1.1] Entité User & Migrations" "## Description\nCréer l'entité User complète.\n\n## Tâches\n- [ ] make:user\n- [ ] Ajouter champs : nom, prenom, raisonSociale, iban, siret\n- [ ] Migration Doctrine" "backend,database" "$MILESTONE_MVP"

create_issue "[EPIC 1.1.2] Formulaire d'inscription" "## Tâches\n- [ ] Créer RegistrationFormType\n- [ ] Implémenter RegistrationController\n- [ ] Template Twig" "backend,frontend,security" "$MILESTONE_MVP"

create_issue "[EPIC 1.1.3] Déconnexion automatique" "## Tâches\n- [ ] Configurer session cookie_lifetime: 3600\n- [ ] Tester le timeout" "backend,security" "$MILESTONE_MVP"

create_issue "[EPIC 1.2] Profil personnel" "## Description\nEn tant qu'utilisateur connecté, je veux modifier mes informations (IBAN, Raison Sociale)." "enhancement,backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 1.2.1] Page de profil et navigation" "- [ ] ProfileController\n- [ ] Lien dans menu navigation" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 1.2.2] Formulaire d'édition du profil" "- [ ] ProfileEditFormType\n- [ ] Logique de mise à jour" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 1.3] Dashboard d'accueil" "## Description\nVue d'ensemble de l'activité (résumé factures, CA)." "enhancement,frontend,backend" "$MILESTONE_MVP"

create_issue "[EPIC 1.3.1] Dashboard Controller & Template" "- [ ] DashboardController\n- [ ] Cards Bootstrap" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 1.3.2] Requêtes de résumé (Dashboard Data)" "- [ ] Repository countByUser()\n- [ ] getTotalRevenue()" "backend,database" "$MILESTONE_MVP"

# EPIC 2
create_issue "[EPIC 2] Facturation (MVP)" "## Description\nGestion des produits, clients et factures." "epic,backend,database" "$MILESTONE_MVP"

create_issue "[EPIC 2.1] Gestion des produits/services" "- [ ] Créer/Modifier/Supprimer un produit." "enhancement,backend,frontend,database" "$MILESTONE_MVP"

create_issue "[EPIC 2.1.1] Entité Product & Repository" "- [ ] make:entity Product\n- [ ] Relation ManyToOne User" "backend,database" "$MILESTONE_MVP"

create_issue "[EPIC 2.1.2] CRUD Produits (Controller & Templates)" "- [ ] ProductController CRUD\n- [ ] Filtre par utilisateur connecté" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.2] Gestion des clients" "- [ ] Annuaire clients (Nom, SIRET, Email)." "enhancement,backend,frontend,database" "$MILESTONE_MVP"

create_issue "[EPIC 2.2.1] Entité Client & Repository" "- [ ] make:entity Client\n- [ ] Migration" "backend,database" "$MILESTONE_MVP"

create_issue "[EPIC 2.2.2] CRUD Clients (Controller & Templates)" "- [ ] ClientController CRUD\n- [ ] Validation SIRET/Email" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.3] Création de facture détaillée" "## Description\nCœur du projet : lignes de factures dynamiques et calculs." "enhancement,backend,frontend,database" "$MILESTONE_MVP"

create_issue "[EPIC 2.3.1] Entités Invoice & InvoiceLine" "- [ ] Relation OneToMany Invoice -> InvoiceLine" "backend,database" "$MILESTONE_MVP"

create_issue "[EPIC 2.3.2] Formulaire de facture dynamique" "- [ ] CollectionType Symfony\n- [ ] JS pour ajout de lignes" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.3.3] Service de calcul automatique" "- [ ] InvoiceCalculatorService\n- [ ] Calcul HT/TTC" "backend" "$MILESTONE_MVP"

create_issue "[EPIC 2.3.4] Logique de validation et numérotation" "- [ ] Générateur de numéro (FACT-YYYY...)\n- [ ] Statut immuable après validation" "backend" "$MILESTONE_MVP"

create_issue "[EPIC 2.4] Liste et suivi des factures" "- [ ] Liste filtrable par statut." "enhancement,backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.4.1] Liste des factures avec filtres" "- [ ] QueryBuilder filtres statut" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.4.2] Page de détail d'une facture" "- [ ] Affichage complet avant impression" "backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.5] Génération PDF" "## Description\nTransformer la facture validée en PDF." "enhancement,backend,pdf" "$MILESTONE_MVP"

create_issue "[EPIC 2.5.1] Installation et configuration PDF" "- [ ] DomPDF installation\n- [ ] PdfGeneratorService" "backend,pdf" "$MILESTONE_MVP"

create_issue "[EPIC 2.5.2] Template PDF et contenu légal" "- [ ] Twig PDF layout\n- [ ] Footer CGV/SIRET" "backend,pdf,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.6] Paiement des factures" "- [ ] Marquer comme payée." "enhancement,backend,frontend" "$MILESTONE_MVP"

create_issue "[EPIC 2.6.1] Action de marquage comme payée" "- [ ] Update status -> 'payee'" "backend" "$MILESTONE_MVP"

create_issue "[EPIC 2.6.2] Confirmation et section payée" "- [ ] Modal de confirmation\n- [ ] Badges de couleur" "backend,frontend" "$MILESTONE_MVP"

# EPIC 3
create_issue "[EPIC 3] Emailing" "## Description\nEnvoi de factures et relances." "epic,backend,email"

create_issue "[EPIC 3.1] Envoi de facture" "- [ ] Envoi PDF par mail au client." "enhancement,backend,email"

create_issue "[EPIC 3.1.1] Configuration Symfony Mailer" "- [ ] MAILER_DSN .env" "backend,email"

create_issue "[EPIC 3.1.2] Service d'envoi de facture" "- [ ] EmailService attach PDF" "backend,email"

create_issue "[EPIC 3.2] Relance client" "- [ ] Mail de rappel pour impayés." "enhancement,backend,email,frontend"

create_issue "[EPIC 3.2.1] Bouton de relance et formulaire" "- [ ] Personnalisation message relance" "backend,frontend"

create_issue "[EPIC 3.2.2] Service de relance automatique" "- [ ] ReminderService" "backend,email"

# EPIC 4
create_issue "[EPIC 4] Statistiques" "## Description\nVisualisation CA." "epic,backend,chart,frontend"

create_issue "[EPIC 4.1] Graphique du Chiffre d'Affaires" "- [ ] Graphique mensuel Chart.js." "enhancement,backend,frontend,chart"

create_issue "[EPIC 4.1.1] Installation UX Chart.js" "- [ ] symfony/ux-chartjs" "backend,chart,frontend"

create_issue "[EPIC 4.1.2] Service de données statistiques" "- [ ] Requêtes agrégées CA par mois" "backend,database,chart"

create_issue "[EPIC 4.1.3] Dashboard statistiques (Template & Filtres)" "- [ ] Filtre par année\n- [ ] Vue graphique" "backend,frontend,chart"

echo "✅ TOUT EST TERMINÉ !"
